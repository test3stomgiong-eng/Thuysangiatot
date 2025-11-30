<div class="auth-container" style="max-width: 500px; margin: 50px auto; padding: 30px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.05);">
    <h2 style="text-align: center; margin-bottom: 20px; color: #28a745;">ĐĂNG KÝ THÀNH VIÊN</h2>
    
    <?php if (isset($error)): ?>
        <div style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form id="form-register" action="/auth/registerPost" method="POST" onsubmit="return validateRegister()">
        
        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight:bold;">Họ và tên <span style="color:red">*</span></label>
            <input type="text" id="fullname" name="fullname" placeholder="Nguyễn Văn A"
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            <small id="err-name" style="color: red; display: none;"></small>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight:bold;">Số điện thoại <span style="color:red">*</span></label>
            <input type="text" id="phone" name="phone" placeholder="09xxxxxxxx"
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            <small id="err-phone" style="color: red; display: none;"></small>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight:bold;">Email</label>
            <input type="text" id="email" name="email" placeholder="email@example.com"
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            <small id="err-email" style="color: red; display: none;"></small>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight:bold;">Mật khẩu <span style="color:red">*</span></label>
            <input type="password" id="password" name="password" placeholder="******"
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            <small id="err-pass" style="color: red; display: none;"></small>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight:bold;">Nhập lại mật khẩu <span style="color:red">*</span></label>
            <input type="password" id="repassword" name="repassword" placeholder="******"
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            <small id="err-repass" style="color: red; display: none;"></small>
        </div>

        <button type="submit" style="width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer;">
            ĐĂNG KÝ NGAY
        </button>
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <p>Đã có tài khoản? <a href="/auth/login" style="color: #007bff; text-decoration: none; font-weight: bold;">Đăng nhập</a></p>
    </div>
</div>

<script>
    function validateRegister() {
        let isValid = true;

        // Lấy giá trị
        const name = document.getElementById('fullname').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const email = document.getElementById('email').value.trim();
        const pass = document.getElementById('password').value;
        const repass = document.getElementById('repassword').value;

        // Reset lỗi
        document.querySelectorAll('small').forEach(el => el.style.display = 'none');

        // 1. Check Tên
        if (name === "") {
            showError('err-name', "Vui lòng nhập họ tên.");
            isValid = false;
        }

        // 2. Check SĐT (Phải là số, bắt đầu bằng 0, có 10 số)
        const phoneRegex = /^0[0-9]{9}$/;
        if (phone === "") {
            showError('err-phone', "Vui lòng nhập số điện thoại.");
            isValid = false;
        } else if (!phoneRegex.test(phone)) {
            showError('err-phone', "Số điện thoại không hợp lệ (Phải có 10 số, bắt đầu là 0).");
            isValid = false;
        }

        // 3. Check Email (Nếu có nhập)
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email !== "" && !emailRegex.test(email)) {
            showError('err-email', "Email không đúng định dạng.");
            isValid = false;
        }

        // 4. Check Mật khẩu
        if (pass.length < 6) {
            showError('err-pass', "Mật khẩu phải có ít nhất 6 ký tự.");
            isValid = false;
        }

        // 5. Check Nhập lại mật khẩu
        if (pass !== repass) {
            showError('err-repass', "Mật khẩu nhập lại không khớp.");
            isValid = false;
        }

        return isValid; // Nếu true thì cho gửi form, false thì chặn lại
    }

    function showError(id, message) {
        const el = document.getElementById(id);
        el.innerText = message;
        el.style.display = 'block';
    }
</script>