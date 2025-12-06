<div class="breadcrumb container">
    <a href="/">Trang chủ</a> <i class="fa-solid fa-angle-right"></i> <span>Liên hệ</span>
</div>

<section class="contact-page container section-padding" style="margin: 40px auto; max-width: 800px;">
    <h2 class="section-title" style="text-align: center; margin-bottom: 30px;">GỬI TIN NHẮN CHO CHÚNG TÔI</h2>
    
    <div class="data-card" style="padding: 40px; border: 1px solid #eee; border-radius: 8px; background: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
        <form action="/contact/send" method="POST">
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight:bold;">Họ và tên</label>
                <input type="text" name="fullname" required placeholder="Nhập họ tên..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;">
            </div>
            
            <div class="row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label style="font-weight:bold;">Email</label>
                    <input type="email" name="email" required placeholder="Email..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label style="font-weight:bold;">Số điện thoại</label>
                    <input type="text" name="phone" required placeholder="SĐT..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight:bold;">Nội dung</label>
                <textarea name="message" rows="5" required placeholder="Bạn cần tư vấn gì?..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;"></textarea>
            </div>

            <button type="submit" class="btn-action btn-green" style="width: 100%; padding: 12px; font-size: 16px; font-weight: bold;">GỬI TIN NHẮN</button>
        </form>
    </div>
</section>