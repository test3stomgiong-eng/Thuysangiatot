<div class="breadcrumb container">
    <a href="/">Trang chủ</a> <i class="fa-solid fa-angle-right"></i>
    <span>Giới thiệu</span>
</div>

<section class="about-page container section-padding">

    <div class="about-intro">
        <div class="intro-img">
            <img src="https://placehold.co/600x400?text=Cong+Ty+Thuy+San" alt="Về chúng tôi">
        </div>

        <div class="intro-text">
            <h2 class="section-title" style="margin-top: 0; color: #0056b3; font-size: 28px; text-transform: uppercase;">
                <?php echo isset($site_title) ? $site_title : 'THUỶ SẢN GIÁ TỐT'; ?>
            </h2>

            <p class="desc-text">
                Được thành lập với sứ mệnh mang lại những giải pháp nuôi trồng thủy sản hiệu quả và bền vững,
                <strong><?php echo $site_title ?? 'Chúng tôi'; ?></strong> tự hào là đơn vị cung cấp thuốc, men vi sinh và chế phẩm sinh học hàng đầu tại Việt Nam.
            </p>
            <p class="desc-text">
                Chúng tôi hiểu rằng, vụ mùa thắng lợi của bà con chính là niềm vui của chúng tôi.
                Tất cả sản phẩm đều được kiểm định nghiêm ngặt, đảm bảo chính hãng và giá thành cạnh tranh nhất thị trường.
            </p>

            <div class="contact-info">
                <p class="info-row">
                    <i class="fa-solid fa-location-dot icon"></i>
                    <span><?php echo $site_address ?? 'Đang cập nhật địa chỉ'; ?></span>
                </p>
                <p class="info-row">
                    <i class="fa-solid fa-phone icon"></i>
                    Hotline: <a href="tel:<?php echo $site_hotline ?? ''; ?>" class="link"> <?php echo $site_hotline ?? '---'; ?></a>
                </p>
                <p class="info-row">
                    <i class="fa-solid fa-envelope icon"></i>
                    Email: <a href="mailto:<?php echo $site_email ?? ''; ?>" class="link"> <?php echo $site_email ?? '---'; ?></a>
                </p>
            </div>

            <!-- <div class="stats-row">
                <div class="stat-item">
                    <strong>10+</strong>
                    <span>Năm kinh nghiệm</span>
                </div>
                <div class="stat-item">
                    <strong>5000+</strong>
                    <span>Khách hàng</span>
                </div>
                <div class="stat-item">
                    <strong>100%</strong>
                    <span>Chính hãng</span>
                </div>
            </div> -->
        </div>
    </div>

    <div class="why-choose-us">
        <h2 class="section-title" style="text-align: center; margin-bottom: 30px;">TẠI SAO CHỌN CHÚNG TÔI?</h2>

        <div class="features-grid">
            <div class="feature-item">
                <i class="fa-solid fa-flask feature-icon" style="color: #007bff;"></i>
                <h3>Chất Lượng Hàng Đầu</h3>
                <p>Sản phẩm nguồn gốc rõ ràng, được kiểm nghiệm thực tế tại các ao nuôi mô hình công nghệ cao.</p>
            </div>

            <div class="feature-item">
                <i class="fa-solid fa-user-doctor feature-icon" style="color: #28a745;"></i>
                <h3>Chuyên Gia Tư Vấn</h3>
                <p>Đội ngũ kỹ sư thủy sản giàu kinh nghiệm hỗ trợ kỹ thuật trọn đời vụ nuôi.</p>
            </div>

            <div class="feature-item">
                <i class="fa-solid fa-truck-fast feature-icon" style="color: #ff9800;"></i>
                <h3>Giao Hàng Thần Tốc</h3>
                <p>Giao hàng nhanh toàn quốc 24/7. Nhận hàng, kiểm tra đúng hàng rồi mới thanh toán.</p>
            </div>
        </div>
    </div>

    <div class="connect-section">
        <h2 class="section-title" style="text-align: center; margin-bottom: 40px; text-transform: uppercase; color: #0056b3;">
            KẾT NỐI VỚI CHÚNG TÔI
        </h2>

        <div class="connect-grid">

            <?php if (!empty($social_facebook) && isset($status_facebook) && $status_facebook == 1): ?>
                <a href="<?php echo $social_facebook; ?>" target="_blank" class="connect-item fb">
                    <div class="icon-box"><i class="fa-brands fa-facebook-f"></i></div>
                    <div class="text-box">
                        <h3>Fanpage Facebook</h3>
                        <p>Theo dõi tin tức</p>
                    </div>
                </a>
            <?php endif; ?>

            <?php if (!empty($social_zalo) && isset($status_zalo) && $status_zalo == 1): ?>
                <a href="https://zalo.me/<?php echo $social_zalo; ?>" target="_blank" class="connect-item zalo">
                    <div class="icon-box"><i class="fa-solid fa-comment-dots"></i></div>
                    <div class="text-box">
                        <h3>Chat Zalo</h3>
                        <p>Tư vấn kỹ thuật</p>
                    </div>
                </a>
            <?php endif; ?>

            <?php if (!empty($social_youtube) && isset($status_youtube) && $status_youtube == 1): ?>
                <a href="<?php echo $social_youtube; ?>" target="_blank" class="connect-item youtube">
                    <div class="icon-box"><i class="fa-brands fa-youtube"></i></div>
                    <div class="text-box">
                        <h3>Kênh Youtube</h3>
                        <p>Video hướng dẫn</p>
                    </div>
                </a>
            <?php endif; ?>

            <?php if (!empty($social_tiktok) && isset($status_tiktok) && $status_tiktok == 1): ?>
                <a href="<?php echo $social_tiktok; ?>" target="_blank" class="connect-item tiktok">
                    <div class="icon-box"><i class="fa-brands fa-tiktok"></i></div>
                    <div class="text-box">
                        <h3>Kênh TikTok</h3>
                        <p>Video ngắn</p>
                    </div>
                </a>
            <?php endif; ?>

        </div>
    </div>

</section>

<style>
    /* --- Phần 1: Intro --- */
    .about-intro {
        display: flex;
        gap: 40px;
        align-items: center;
        margin-bottom: 60px;
    }

    .intro-img {
        flex: 1;
    }

    .intro-img img {
        width: 100%;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .intro-text {
        flex: 1;
    }

    .desc-text {
        line-height: 1.6;
        color: #555;
        font-size: 16px;
        margin-bottom: 15px;
    }

    .contact-info {
        margin-top: 20px;
        font-size: 15px;
        color: #333;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #28a745;
    }

    .info-row {
        margin: 8px 0;
        display: flex;
        align-items: center;
    }

    .info-row .icon {
        color: #28a745;
        width: 25px;
        text-align: center;
        margin-right: 10px;
    }

    .info-row .link {
        color: #0056b3;
        font-weight: bold;
        text-decoration: none;
    }

    .stats-row {
        margin-top: 30px;
        display: flex;
        gap: 30px;
    }

    .stat-item strong {
        font-size: 30px;
        color: #28a745;
        display: block;
    }

    .stat-item span {
        color: #666;
    }

    /* --- Phần 2: Why Choose Us --- */
    .why-choose-us {
        background: #f0f8ff;
        padding: 40px;
        border-radius: 10px;
        margin-bottom: 60px;
    }

    .features-grid {
        display: flex;
        gap: 20px;
        text-align: center;
    }

    .feature-item {
        flex: 1;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: 0.3s;
    }

    .feature-item:hover {
        transform: translateY(-5px);
    }

    .feature-icon {
        font-size: 40px;
        margin-bottom: 15px;
        display: inline-block;
    }

    .feature-item h3 {
        margin: 10px 0;
        font-size: 18px;
    }

    .feature-item p {
        color: #666;
        font-size: 14px;
        line-height: 1.5;
    }

    /* --- Phần 3: Connect Section (Mạng xã hội) --- */
    .connect-section {
        margin-bottom: 60px;
    }

    .connect-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .connect-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 30px 20px;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        text-decoration: none;
        transition: 0.3s;
        border: 1px solid #eee;
    }

    .connect-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Icon Box Tròn */
    .connect-item .icon-box {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        color: white;
        margin-bottom: 15px;
    }

    /* Màu sắc từng kênh */
    .connect-item.fb .icon-box {
        background: #1877f2;
        box-shadow: 0 4px 10px rgba(24, 119, 242, 0.3);
    }

    .connect-item.zalo .icon-box {
        background: #0068ff;
        box-shadow: 0 4px 10px rgba(0, 104, 255, 0.3);
    }

    .connect-item.youtube .icon-box {
        background: #ff0000;
        box-shadow: 0 4px 10px rgba(255, 0, 0, 0.3);
    }

    .connect-item.phone .icon-box {
        background: #28a745;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
    }

    .connect-item .text-box h3 {
        margin: 0 0 5px;
        font-size: 18px;
        font-weight: 700;
        color: #333;
    }

    .connect-item .text-box p {
        margin: 0;
        font-size: 14px;
        color: #666;
    }

    .connect-item .text-box .hotline-number {
        font-weight: bold;
        font-size: 18px;
        color: #d0021b;
        margin-top: 5px;
    }

    .connect-item.tiktok .icon-box {
        background: #000000;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    /* Mobile Responsive */
    @media (max-width: 992px) {
        .connect-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .about-intro {
            flex-direction: column;
        }

        .features-grid {
            flex-direction: column;
        }

        .connect-grid {
            grid-template-columns: 1fr;
        }
    }
</style>