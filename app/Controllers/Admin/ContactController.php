<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Contact;

class ContactController extends Controller
{

    public function __construct()
    {
        if (!isset($_SESSION['customer_user']) || $_SESSION['customer_user']['role'] !== 'admin') {
            header("Location: /");
            exit();
        }
    }

    public function index()
    {
        $contactModel = new Contact();
        $contacts = $contactModel->getAll();

        $data = ['title' => 'Danh sách liên hệ', 'contacts' => $contacts];
        $this->view('Admin/contacts', $data, 'admin_layout');
    }

    public function delete($id)
    {
        $contactModel = new Contact();
        $contactModel->delete($id);
        header("Location: /admin/contact");
    }

    public function detail($id)
    {
        $contactModel = new \App\Models\Contact();

        // Lấy thông tin
        $contact = $contactModel->find($id);

        if (!$contact) {
            header("Location: /admin/contact");
            exit();
        }

        // Đánh dấu là Đã xem (nếu đang là chưa xem)
        if ($contact->status == 0) {
            $contactModel->markAsRead($id);
            $contact->status = 1; // Cập nhật biến tạm để view hiển thị đúng
        }

        $data = [
            'title'   => 'Chi tiết liên hệ #' . $contact->id,
            'contact' => $contact
        ];

        $this->view('Admin/contact-detail', $data, 'admin_layout');
    }
}
