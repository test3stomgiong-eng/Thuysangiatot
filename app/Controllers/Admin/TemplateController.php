<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

class TemplateController extends Controller
{

    public function __construct()
    {
        if (!isset($_SESSION['customer_user']) || $_SESSION['customer_user']['role'] !== 'admin') {
            header("Location: /");
            exit();
        }
    }

    // 1. DANH SÁCH MẪU IN
    public function index()
    {
        $db = new Database();
        $stmt = $db->query("SELECT * FROM print_templates ORDER BY id DESC");
        $stmt->execute();
        $templates = $stmt->fetchAll();

        $data = [
            'title'     => 'Quản lý Mẫu in',
            'templates' => $templates
        ];

        // 👇 ĐÃ SỬA: Gọi view 'viewPrintf' thay vì 'index'
        $this->view('Admin/Templates/viewPrintf', $data, 'admin_layout');
    }
    // 2. FORM THIẾT KẾ (Sửa view thành print-form)
    public function printForm($id = null)
    {
        $template = null;
        if ($id) {
            $db = new Database();
            $stmt = $db->query("SELECT * FROM print_templates WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $template = $stmt->fetch();
        }

        $data = [
            'title'    => $id ? 'Chỉnh sửa mẫu in' : 'Thêm mẫu in mới',
            'template' => $template
        ];

        // 👇 ĐÃ SỬA: Gọi view 'print-form' thay vì 'form'
        $this->view('Admin/Templates/print-form', $data, 'admin_layout');
    }

    // 3. LƯU (Giữ nguyên)
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id         = !empty($_POST['id']) ? $_POST['id'] : null;
            $name       = $_POST['name'];
            $content    = $_POST['content'];
            $is_default = isset($_POST['is_default']) ? 1 : 0;

            $db = new Database();

            if ($is_default == 1) {
                $db->query("UPDATE print_templates SET is_default = 0")->execute();
            }

            if ($id) {
                $sql = "UPDATE print_templates SET name=:name, content=:content, is_default=:def WHERE id=:id";
                $stmt = $db->query($sql);
                $stmt->execute([':name' => $name, ':content' => $content, ':def' => $is_default, ':id' => $id]);
            } else {
                $sql = "INSERT INTO print_templates (name, content, is_default) VALUES (:name, :content, :def)";
                $stmt = $db->query($sql);
                $stmt->execute([':name' => $name, ':content' => $content, ':def' => $is_default]);
            }

            echo "<script>alert('Lưu mẫu in thành công!'); window.location.href='/admin/template';</script>";
        }
    }

    // 4. XÓA (Giữ nguyên)
    public function delete($id)
    {
        $db = new Database();
        $sql = "DELETE FROM print_templates WHERE id = :id AND is_default = 0";
        $stmt = $db->query($sql);
        $stmt->execute([':id' => $id]);
        header("Location: /admin/template");
        exit();
    }
}
