<form action="register.php" method="POST">
    <input type="text" name="fullname" placeholder="Họ và tên" required> <!-- thêm 1 dòng code ở đây -->
    <input type="text" name="username" placeholder="Tên đăng nhập" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mật khẩu" required>
    <select name="role">
        <option value="creator">Test Creator</option>
        <option value="taker">Test Taker</option>
    </select>
    <button type="submit" name="register">Đăng ký</button>
</form>