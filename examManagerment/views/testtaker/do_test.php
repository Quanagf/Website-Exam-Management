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
// Kiểm tra xem biến có được truyền từ controller chưa
if (!isset($questions) || !is_array($questions)) {
    echo "<p style='color:red;'>❌ Không có dữ liệu câu hỏi được truyền vào!</p>";
    return;
}

$total = count($questions);
$test_id = intval($_GET['id'] ?? 0);
?>


<style>
    .test-wrapper {
        display: flex;
        gap: 40px;
    }

    #navigator {
        width: 150px;
        border-left: 2px solid #ccc;
        padding-left: 20px;
    }

    .nav-item {
        padding: 8px;
        border: 1px solid #ccc;
        margin-bottom: 5px;
        text-align: center;
        cursor: pointer;
        border-radius: 6px;
        background: #f0f0f0;
    }

    .nav-item.active {
        background: #3498db;
        color: white;
        font-weight: bold;
    }
</style>


<h2>Làm bài thi</h2>
<form method="POST" action="../controllers/DotestController.php" id="test-form">
    <input type="hidden" name="submit_test" value="1">
    <input type="hidden" name="test_id" value="<?= $test_id ?>">

    <div id="questions-container">
        <?php foreach ($questions as $index => $q): ?>
            <div class="question-block" id="question-<?= $index ?>" style="<?= $index === 0 ? '' : 'display: none;' ?>">
                <p><strong>Câu <?= $index + 1 ?>:</strong> <?= htmlspecialchars($q['content']) ?></p>
                <?php foreach (['A', 'B', 'C', 'D'] as $opt): ?>
                    <label>
                        <input type="radio" name="answers[<?= $q['id'] ?>]" value="<?= $opt ?>">
                        <?= $opt ?>. <?= htmlspecialchars($q['option_' . strtolower($opt)]) ?>
                    </label><br>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

        <!-- 🔹 Navigator bên phải -->
        <div id="navigator">
            <h4>🧭 Câu hỏi</h4>
            <?php for ($i = 0; $i < $total; $i++): ?>
                <div class="nav-item <?= $i === 0 ? 'active' : '' ?>" onclick="goToQuestion(<?= $i ?>)" id="nav-<?= $i ?>">
                    Câu <?= $i + 1 ?>
                </div>
            <?php endfor; ?>
        </div>

    <div style="margin-top: 20px;">
        <button type="button" onclick="prevQuestion()">⬅ Trước</button>
        <span id="question-number">1 / <?= $total ?></span>
        <button type="button" onclick="nextQuestion()">Tiếp ➡</button>
    </div>

    <div style="margin-top: 20px;">
        <button type="submit" onclick="return confirm('Bạn chắc chắn muốn nộp bài?')">📝 Nộp bài</button>
    </div>
</form>


<script>
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

function nextQuestion() {
    if (current < total - 1) {
        showQuestion(current + 1);
    }
}

function prevQuestion() {
    if (current > 0) {
        showQuestion(current - 1);
    }
}

function goToQuestion(index) {
    showQuestion(index);
}
</script>
