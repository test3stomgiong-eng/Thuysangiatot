<?php
namespace App\Helpers;

// 👇 QUAN TRỌNG: Nhúng thủ công 3 file bạn vừa copy vào
// Thứ tự bắt buộc: Exception -> PHPMailer -> SMTP
require_once __DIR__ . '/../Libs/PHPMailer/Exception.php';
require_once __DIR__ . '/../Libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../Libs/PHPMailer/SMTP.php';

// Khai báo sử dụng Namespace của thư viện
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Gọi Model Setting để lấy cấu hình từ Database
use App\Models\Setting;

class Mailer {

    /**
     * Hàm gửi mail chung cho toàn hệ thống
     * @param string $toEmail : Email người nhận
     * @param string $subject : Tiêu đề
     * @param string $body    : Nội dung (HTML)
     */
    public static function send($toEmail, $subject, $body) {
        
        // 1. Lấy cấu hình SMTP từ Database (Bảng settings)
        $settingModel = new Setting();
        $config = $settingModel->getSettings();

        // Kiểm tra xem đã cấu hình trong Admin chưa
        if (empty($config['smtp_host']) || empty($config['smtp_username'])) {
            return "Lỗi: Chưa cấu hình Email trong Admin!";
        }

        // 2. Khởi tạo PHPMailer
        $mail = new PHPMailer(true);

        try {
            // --- Cấu hình Server ---
            // $mail->SMTPDebug = 2; // Bỏ comment dòng này nếu muốn xem lỗi chi tiết
            $mail->isSMTP();
            $mail->Host       = $config['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $config['smtp_username'];
            $mail->Password   = $config['smtp_password'];
            $mail->SMTPSecure = $config['smtp_secure']; // tls hoặc ssl
            $mail->Port       = $config['smtp_port'];     // 587 hoặc 465
            $mail->CharSet    = 'UTF-8';

            // --- Người gửi & Người nhận ---
            // Tên người gửi: Lấy tên Web hoặc mặc định
            $senderName = $config['site_title'] ?? 'Thuỷ Sản Giá Tốt';
            
            $mail->setFrom($config['smtp_username'], $senderName);
            $mail->addAddress($toEmail);

            // --- Nội dung ---
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body); // Nội dung rút gọn cho trình duyệt cũ

            // --- Gửi ---
            $mail->send();
            return true; // Thành công

        } catch (Exception $e) {
            // Thất bại -> Trả về thông báo lỗi
            return "Gửi mail thất bại. Lỗi: {$mail->ErrorInfo}";
        }
    }
}