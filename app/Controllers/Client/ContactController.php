<?php
namespace App\Controllers\Client;
use App\Core\Controller;
use App\Models\Contact;

class ContactController extends Controller {
    
    // Hiện form liên hệ
    public function index() {
        $data = ['title' => 'Liên hệ với chúng tôi', 'css_files' => ['style.css']];
        $this->view('Client/contact', $data, 'client_layout');
    }

    // Xử lý gửi
    public function send() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $contactModel = new Contact();
            
            $data = [
                'fullname' => $_POST['fullname'],
                'email'    => $_POST['email'],
                'phone'    => $_POST['phone'],
                'message'  => $_POST['message']
            ];

            $contactModel->create($data);

            echo "<script>alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm.'); window.location.href='/contact';</script>";
        }
    }
}