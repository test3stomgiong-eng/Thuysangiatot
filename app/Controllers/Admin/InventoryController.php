<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Inventory;
use App\Models\Product;

class InventoryController extends Controller
{

    public function __construct()
    {
        if (!isset($_SESSION['customer_user']) || $_SESSION['customer_user']['role'] !== 'admin') {
            header("Location: /");
            exit();
        }
    }

    // 1. Danh sách phiếu nhập
    public function index()
    {
        $invModel = new Inventory();
        $receipts = $invModel->getAllReceipts();

        $data = ['title' => 'Quản lý Nhập kho', 'receipts' => $receipts];
        $this->view('Admin/Inventory/index', $data, 'admin_layout');
    }

    // 2. Form tạo phiếu nhập mới
    public function create()
    {
        $prodModel = new Product();
        $products = $prodModel->getAllAdmin(); // Lấy list thuốc để chọn

        $data = ['title' => 'Tạo phiếu nhập kho', 'products' => $products];
        $this->view('Admin/Inventory/create', $data, 'admin_layout');
    }

    // 3. Xử lý lưu
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $invModel = new Inventory();
            $prodModel = new Product();

            // Tạo mã phiếu tự động: PN + Timestamp (VD: PN173849)
            $code = 'PN' . time();
            $note = $_POST['note'];
            $userId = $_SESSION['customer_user']['id'];

            // Tính tổng tiền phiếu
            $totalMoney = 0;
            $productIds = $_POST['product_id']; // Mảng ID
            $quantities = $_POST['quantity'];   // Mảng SL
            $prices     = $_POST['price'];      // Mảng Giá vốn

            for ($i = 0; $i < count($productIds); $i++) {
                $totalMoney += $quantities[$i] * $prices[$i];
            }

            // A. Tạo phiếu cha
            $receiptId = $invModel->createReceipt([
                'code' => $code,
                'user_id' => $userId,
                'total_money' => $totalMoney,
                'note' => $note
            ]);

            // B. Lưu chi tiết và Cộng kho
            for ($i = 0; $i < count($productIds); $i++) {
                $pid = $productIds[$i];
                $qty = $quantities[$i];
                $price = $prices[$i];

                // Lấy tên SP để lưu log
                $prodInfo = $prodModel->find($pid);
                $prodName = $prodInfo ? $prodInfo->name : 'Unknown';

                $invModel->addDetailAndUpdateStock($receiptId, $pid, $prodName, $qty, $price);
            }

            echo "<script>alert('Nhập kho thành công!'); window.location.href='/admin/inventory';</script>";
        }
    }

    // 4. XEM CHI TIẾT PHIẾU NHẬP (MỚI)
    public function detail($id)
    {
        $invModel = new \App\Models\Inventory();

        // Lấy thông tin phiếu
        $receipt = $invModel->findReceipt($id);
        if (!$receipt) {
            header("Location: /admin/inventory");
            exit();
        }

        // Lấy danh sách hàng hóa trong phiếu
        $details = $invModel->getReceiptDetails($id);

        $data = [
            'title'   => 'Chi tiết phiếu nhập ' . $receipt->code,
            'receipt' => $receipt,
            'details' => $details
        ];
        $this->view('Admin/Inventory/detail', $data, 'admin_layout');
    }
}
