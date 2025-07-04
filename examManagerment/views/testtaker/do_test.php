<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/database.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'taker') {
    header("Location: ../../index.php");
    exit();
}

// Lấy ID đề thi
$test_id = intval($_GET['id'] ?? 0);

// Lấy thông tin thời gian làm bài
$stmt = $conn->prepare("SELECT duration FROM tests WHERE id = ?");
$stmt->bind_param("i", $test_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $duration_minutes = intval($row['duration']);
    if ($duration_minutes <= 0) {
        $duration_minutes = 30;
    }
} else {
    echo "<p style='color:red;'>❌ Không tìm thấy đề thi!</p>";
    exit();
}
$duration_seconds = $duration_minutes * 60;

// Lấy trạng thái force_reset_time
$stmt = $conn->prepare("SELECT force_reset_time FROM test_responses WHERE test_id = ? AND test_taker_id = ?");
$stmt->bind_param("ii", $test_id, $_SESSION['user']['id']);
$stmt->execute();
$response = $stmt->get_result()->fetch_assoc();
$forceResetTime = $response && $response['force_reset_time'] ? 1 : 0;

// Kiểm tra biến câu hỏi
if (!isset($questions) || !is_array($questions)) {
    echo "<p style='color:red;'>❌ Không có dữ liệu câu hỏi được truyền vào!</p>";
    return;
}

$total = count($questions);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Làm bài thi</title>
<link rel="stylesheet" href="../src/css/layout.css">
<style>
body { background:#f4f6f9; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin:0; padding:0; }
.test-wrapper { display:flex; gap:30px; margin-top:20px; align-items:flex-start; }
#questions-container { flex:1; display:flex; justify-content:center; }
.question-block { background:#fff; border:1px solid #ddd; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.1); padding:20px; width:100%; max-width:600px; }
.question-block p { font-size:18px; font-weight:600; }
.question-block label { display:block; padding:10px; margin-bottom:8px; background:#f7f9fa; border:1px solid #ccc; border-radius:6px; cursor:pointer; font-size:16px; }
.question-block label:hover { background:#e6f0fa; }
#navigator { display:grid; grid-template-columns:repeat(5,1fr); gap:10px; width:auto; max-width:500px; }
.nav-item { padding:10px; border:1px solid #ccc; text-align:center; cursor:pointer; border-radius:4px; background:#fafafa; font-size:14px; }
.nav-item:hover { background:#eee; }
.nav-item.active { background:#3498db; color:white; font-weight:bold; }
.nav-item.answered { background:#2ecc71; color:white; }
.main2 button { background:#3498db; color:white; border:none; padding:10px 20px; margin:5px; border-radius:5px; font-size:15px; cursor:pointer; }
.main2 button:hover { background:#2980b9; }
#timer { font-weight:bold; color:#e74c3c; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📝 Làm bài thi</h1>
        <div class="menu-container">
            <div class="hamburger">&#9776;</div>
            <div class="menu-items">
                <a href="../index.php">Trang chủ</a>
                <a href="../logout.php">Đăng xuất</a>
            </div>
        </div>
    </div>
    <div class="main">
        <div class="main1">
            <div class="menu-items1"><a href="javascript:history.back()">🔙 Quay lại</a></div>
            <div class="menu-items1"><a href="../logout.php">🚪 Đăng xuất</a></div>
        </div>
        <div class="line"></div>
        <div class="main2">
            <form method="POST" action="../../controllers/DotestController.php" id="test-form">
                <input type="hidden" name="submit_test" value="1">
                <input type="hidden" name="test_id" value="<?= $test_id ?>">
                <div style="margin-top:10px; font-weight:bold;">
                    ⏱️ Thời lượng: <?= $duration_minutes ?> phút
                </div>
                <div style="margin-top:10px;">
                    ⏳ Còn lại: <span id="timer">--:--</span>
                </div>
                <div class="test-wrapper">
                    <div id="questions-container">
                        <?php foreach ($questions as $index => $q): ?>
                        <div class="question-block" id="question-<?= $index ?>" style="<?= $index===0?'':'display:none;' ?>">
                            <p><strong>Câu <?= $index+1 ?>:</strong> <?= htmlspecialchars($q['content']) ?></p>
                            <?php foreach(['A','B','C','D'] as $opt): ?>
                            <label>
                                <input type="radio" name="answers[<?= $q['id'] ?>]" value="<?= $opt ?>" onclick="markAnswered(<?= $index ?>)">
                                <?= $opt ?>. <?= htmlspecialchars($q['option_'.strtolower($opt)]) ?>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div id="navigator">
                        <?php for ($i=0;$i<$total;$i++): ?>
                        <div class="nav-item<?= $i===0?' active':'' ?>" id="nav-<?= $i ?>" onclick="goToQuestion(<?= $i ?>)">
                            <?= $i+1 ?>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <div style="margin-top:15px; text-align:right;">
                    <span id="question-number">1 / <?= $total ?></span>
                    <button type="button" onclick="prevQuestion()">⬅ Trước</button>
                    <button type="button" onclick="nextQuestion()">Tiếp ➡</button>
                </div>
                <div style="margin-top:15px; text-align:right;">
                    <button type="submit" onclick="return confirm('Bạn chắc chắn muốn nộp bài?')">📝 Nộp bài</button>
                </div>
            </form>
        </div>
    </div>
    <div class="footer">
        ©2025 Hệ thống thi trắc nghiệm
    </div>
</div>
<script>
const forceResetTime = <?= $forceResetTime ?>;
const duration = <?= $duration_seconds ?>;
const storageKey = "startTime_test_<?= $test_id ?>";
let startTime = localStorage.getItem(storageKey);

if (forceResetTime) {
    startTime = Date.now();
    localStorage.setItem(storageKey, startTime);
    fetch("../../controllers/DotestController.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            action: "clear_force_reset",
            test_id: <?= $test_id ?>
        })
    });
} else if (!startTime) {
    startTime = Date.now();
    localStorage.setItem(storageKey, startTime);
} else {
    startTime = parseInt(startTime);
}

function updateTimer() {
    const now = Date.now();
    const elapsed = Math.floor((now - startTime) / 1000);
    const remaining = duration - elapsed;

    if (remaining <= 0) {
        document.getElementById("timer").textContent = "00:00";
        alert("⏰ Hết thời gian! Bài sẽ tự động nộp.");
        localStorage.removeItem(storageKey);
        document.getElementById("test-form").submit();
    } else {
        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;
        document.getElementById("timer").textContent = String(minutes).padStart(2,'0') + ":" + String(seconds).padStart(2,'0');
        setTimeout(updateTimer, 1000);
    }
}

updateTimer();

let current = 0;
const total = <?= $total ?>;

function showQuestion(index) {
    for (let i = 0; i < total; i++) {
        document.getElementById("question-" + i).style.display = (i === index) ? "" : "none";
        document.getElementById("nav-" + i).classList.remove("active");
    }
    document.getElementById("question-number").textContent = (index + 1) + " / " + total;
    document.getElementById("nav-" + index).classList.add("active");
    current = index;
}
function nextQuestion() { if (current < total - 1) showQuestion(current + 1); }
function prevQuestion() { if (current > 0) showQuestion(current - 1); }
function goToQuestion(index) { showQuestion(index); }
function markAnswered(index) {
    document.getElementById("nav-" + index).classList.add("answered");
}
</script>
</body>
</html>
