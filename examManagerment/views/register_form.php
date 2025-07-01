<form method="POST" action="register.php">
    <input type="text" name="fullname" placeholder="Họ tên đầy đủ" required>
    <input type="text" name="username" placeholder="Tên đăng nhập" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mật khẩu" required>
    <select name="role" required>
        <option value="taker">Thí sinh</option>
        <option value="creator">Người tạo đề</option>
    </select>
    <button type="submit" name="register">Đăng ký</button>
</form>