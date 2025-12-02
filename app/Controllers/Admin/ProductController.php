<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{

    public function __construct()
    {
        if (!isset($_SESSION['customer_user']) || $_SESSION['customer_user']['role'] !== 'admin') {
            header("Location: /auth/login");
            exit();
        }
    }

    // 1. Danh sách sản phẩm
    public function index()
    {
        $prodModel = new Product();
        $cateModel = new Category();

        // 1. Nhận dữ liệu từ URL (Ví dụ: ?keyword=abc&cat_id=1)
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;
        $cat_id  = isset($_GET['cat_id']) ? $_GET['cat_id'] : null;

        // 2. Truyền vào hàm getAllAdmin để lọc
        $products = $prodModel->getAllAdmin($keyword, $cat_id);

        $categories = $cateModel->getAll();

        $data = [
            'title'      => 'Quản lý Sản phẩm',
            'products'   => $products,
            'categories' => $categories
        ];

        $this->view('Admin/products', $data, 'admin_layout');
    }

    // 2. Form thêm mới

    public function add()
    {
        $cateModel = new Category();
        $prodModel = new Product(); // Gọi thêm Model Product

        $categories = $cateModel->getTreeProductCategories();

        // 👇 LẤY DANH SÁCH SẢN PHẨM (ĐỂ CHỌN LÀM QUÀ)
        $all_products = $prodModel->getAllAdmin();

        $data = [
            'title'        => 'Thêm sản phẩm mới',
            'categories'   => $categories,
            'all_products' => $all_products // Truyền sang View
        ];
        $this->view('Admin/product-add', $data, 'admin_layout');
    }

    // 3. Xử lý LƯU (Quan trọng)
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name        = trim($_POST['name']);
            $price       = trim($_POST['price']);
            $category_id = $_POST['category_id'];
            $errors = [];

            if (empty($name)) {
                $errors[] = "Tên sản phẩm không được để trống.";
            }
            if (empty($category_id)) {
                $errors[] = "Vui lòng chọn danh mục.";
            }
            if (empty($price)) {
                $errors[] = "Giá bán không được để trống.";
            }

            // 3. NẾU CÓ LỖI -> TRẢ VỀ FORM CŨ
            if (!empty($errors)) {
                $cateModel = new Category();
                $categories = $cateModel->getAll(); // Lấy lại danh mục để đổ vào select

                $data = [
                    'title'      => 'Thêm sản phẩm mới',
                    'categories' => $categories,
                    'errors'     => $errors,  // Gửi danh sách lỗi sang View
                    'old'        => $_POST    // Gửi lại dữ liệu vừa nhập (để điền lại vào ô)
                ];

                // Load lại view thêm mới (kèm lỗi)
                $this->view('Admin/product-add', $data, 'admin_layout');
                return; // Dừng lại, không chạy code lưu bên dưới
            }

            // A. Upload Ảnh Chính
            $main_image = '';
            if (!empty($_FILES['main_image']['name'])) {
                $main_image = $this->uploadFile($_FILES['main_image']);
            }
            // --- XỬ LÝ MÃ SKU TỰ ĐỘNG (THÊM ĐOẠN NÀY) ---
            $skuInput = trim($_POST['sku']);

            if (empty($skuInput)) {
                // Nếu để trống: Tự sinh mã (VD: TS839201)
                // Bạn có thể đổi 'TS' thành tên viết tắt shop của bạn
                $sku = 'TS' . rand(100000, 999999);
            } else {
                // Nếu có nhập: Chuyển thành chữ in hoa (VD: sp01 -> SP01)
                $sku = strtoupper($skuInput);
            }
            // B. Gom dữ liệu
            $data = [
                'name'              => $_POST['name'],
                'sku'               => $sku,
                'category_id'       => $_POST['category_id'],
                'price'             => $_POST['price'],
                'sale_price'        => !empty($_POST['sale_price']) ? $_POST['sale_price'] : 0,
                'stock'             => $_POST['stock'],
                'status'            => $_POST['status'],
                'ingredients'       => $_POST['ingredients'],
                'uses'              => $_POST['uses'],
                'usage_instruction' => $_POST['usage_instruction'],
                'note'              => $_POST['note'],
                'main_image'        => $main_image,

                'promo_type'        => $_POST['promo_type'],
                'promo_buy'         => !empty($_POST['promo_buy']) ? $_POST['promo_buy'] : 0,
                'promo_get'         => !empty($_POST['promo_get']) ? $_POST['promo_get'] : 0,
                'promo_gift_id'     => !empty($_POST['promo_gift_id']) ? $_POST['promo_gift_id'] : 0
            ];

            // C. Gọi Model lưu
            $prodModel = new Product();
            $newProductId = $prodModel->createGetId($data);

            // D. Xử lý Ảnh Phụ (Gallery) - Chạy vòng lặp
            if ($newProductId && !empty($_FILES['gallery']['name'][0])) {
                $files = $_FILES['gallery'];
                $count = count($files['name']);

                for ($i = 0; $i < $count; $i++) {
                    if ($files['error'][$i] == 0) {
                        // Giả lập 1 file đơn để tái sử dụng hàm upload
                        $singleFile = [
                            'name'     => $files['name'][$i],
                            'type'     => $files['type'][$i],
                            'tmp_name' => $files['tmp_name'][$i],
                            'error'    => $files['error'][$i],
                            'size'     => $files['size'][$i]
                        ];
                        $imgName = $this->uploadFile($singleFile);
                        if ($imgName) {
                            $prodModel->addGalleryImage($newProductId, $imgName);
                        }
                    }
                }
            }

            header("Location: /admin/product");
        }
    }

    // 4. Xóa
    public function delete($id)
    {
        $prodModel = new Product();

        // A. Lấy thông tin sản phẩm để biết tên ảnh đại diện
        $product = $prodModel->find($id);

        if ($product) {
            // 1. Xóa ảnh đại diện chính (Nếu có)
            if (!empty($product->main_image)) {
                $main_path = ROOT_PATH . '/public/assets/uploads/products/' . $product->main_image;
                if (file_exists($main_path)) {
                    unlink($main_path); // Hàm xóa file của PHP
                }
            }

            // 2. Xóa album ảnh phụ (Gallery)
            // Lấy danh sách ảnh phụ
            $gallery = $prodModel->getGallery($id);
            if (!empty($gallery)) {
                foreach ($gallery as $img) {
                    $gal_path = ROOT_PATH . '/public/assets/uploads/products/' . $img->image_url;
                    if (file_exists($gal_path)) {
                        unlink($gal_path);
                    }
                }
            }

            // 3. Cuối cùng mới xóa trong Database
            // (Database có thiết lập ON DELETE CASCADE thì gallery trong DB tự mất, 
            // nhưng mình vẫn gọi hàm delete cho chắc)
            $prodModel->delete($id);
        }

        // Quay về danh sách
        header("Location: /admin/product");
        exit();
    }
    // 4. HIỆN FORM SỬA (GET)

    public function edit($id)
    {
        $prodModel = new Product();
        $cateModel = new Category();

        $product = $prodModel->find($id);
        if (!$product) {
            header("Location: /admin/product");
            exit();
        }
        $gallery = $prodModel->getGallery($id);
        $categories = $cateModel->getTreeProductCategories();

        // LẤY DANH SÁCH SẢN PHẨM
        $all_products = $prodModel->getAllAdmin();

        $data = [
            'title'        => 'Chỉnh sửa sản phẩm',
            'product'      => $product,
            'gallery'      => $gallery,
            'categories'   => $categories,
            'all_products' => $all_products // Truyền sang View
        ];
        $this->view('Admin/product-edit', $data, 'admin_layout');
    }

    // 5. XỬ LÝ CẬP NHẬT (POST)
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $prodModel = new Product();

            // --- A. XỬ LÝ ẢNH CHÍNH ---
            $main_image = $_POST['old_main_image']; // Mặc định lấy ảnh cũ

            // Nếu người dùng có chọn ảnh mới
            if (!empty($_FILES['main_image']['name'])) {
                // 1. Upload ảnh mới
                $new_image = $this->uploadFile($_FILES['main_image']);
                if ($new_image) {
                    $main_image = $new_image; // Gán ảnh mới

                    // 2. Xóa ảnh cũ vật lý (Nếu có và không phải là ảnh mẫu)
                    if (!empty($_POST['old_main_image'])) {
                        $old_file_path = ROOT_PATH . '/public/assets/uploads/products/' . $_POST['old_main_image'];
                        if (file_exists($old_file_path)) {
                            unlink($old_file_path); // Xóa file cũ
                        }
                    }
                }
            }

            // --- B. GOM DỮ LIỆU CẬP NHẬT ---
            $data = [
                'id'                => $id, // Quan trọng
                'name'              => $_POST['name'],
                'sku'               => $_POST['sku'],
                'category_id'       => $_POST['category_id'],
                'price'             => $_POST['price'],
                'sale_price'        => !empty($_POST['sale_price']) ? $_POST['sale_price'] : 0,
                'stock'             => $_POST['stock'],
                'status'            => $_POST['status'],
                'ingredients'       => $_POST['ingredients'],
                'uses'              => $_POST['uses'],
                'usage_instruction' => $_POST['usage_instruction'],
                'note'              => $_POST['note'],
                'main_image'        => $main_image,

                'promo_type'        => $_POST['promo_type'],
                'promo_buy'         => !empty($_POST['promo_buy']) ? $_POST['promo_buy'] : 0,
                'promo_get'         => !empty($_POST['promo_get']) ? $_POST['promo_get'] : 0,
                'promo_gift_id'     => !empty($_POST['promo_gift_id']) ? $_POST['promo_gift_id'] : 0
            ];

            // --- C. GỌI MODEL UPDATE ---
            $prodModel->update($data);

            // --- D. XỬ LÝ THÊM ẢNH PHỤ MỚI (Nếu có) ---
            if (!empty($_FILES['gallery']['name'][0])) {
                $files = $_FILES['gallery'];
                $count = count($files['name']);
                for ($i = 0; $i < $count; $i++) {
                    if ($files['error'][$i] == 0) {
                        $singleFile = [
                            'name' => $files['name'][$i],
                            'type' => $files['type'][$i],
                            'tmp_name' => $files['tmp_name'][$i],
                            'error' => $files['error'][$i],
                            'size' => $files['size'][$i]
                        ];
                        $imgName = $this->uploadFile($singleFile);
                        if ($imgName) {
                            $prodModel->addGalleryImage($id, $imgName);
                        }
                    }
                }
            }

            // Xong xuôi -> Quay về trang edit và báo thành công
            echo "<script>alert('Cập nhật thành công!'); window.location.href='/admin/product/edit/$id';</script>";
        }
    }

    // 6. XÓA 1 ẢNH GALLERY
    public function deleteGallery($image_id)
    {
        $prodModel = new Product();

        // 1. Tìm thông tin ảnh để lấy tên file
        $image = $prodModel->findGalleryImage($image_id);

        if ($image) {
            // 2. Xóa file vật lý trên server
            $file_path = ROOT_PATH . '/public/assets/uploads/products/' . $image->image_url;
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // 3. Xóa trong database
            $prodModel->deleteGalleryImage($image_id);
        }

        // Quay lại trang sửa sản phẩm (Dùng HTTP Referer để quay lại trang trước)
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // --- HÀM PHỤ: UPLOAD FILE ---
    private function uploadFile($file)
    {
        $targetDir = ROOT_PATH . "/public/assets/uploads/products/";
        $fileName = 'sp_' . time() . '_' . rand(100, 999) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $fileName;
        }
        return '';
    }

    /**
     * Hàm xử lý Upload ảnh cho CKEditor 4
     * URL: /admin/product/uploadCkEditor
     */
    /**
     * Hàm xử lý Upload ảnh cho CKEditor 4
     */
    public function uploadCkEditor()
    {
        if (isset($_FILES['upload']) && $_FILES['upload']['error'] == 0) {

            $file = $_FILES['upload'];
            $path = ROOT_PATH . '/public/assets/uploads/content/';

            // Tạo thư mục nếu chưa có
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                $newFileName = 'post_' . time() . '_' . rand(100, 999) . '.' . $ext;
                $targetFile = $path . $newFileName;

                if (move_uploaded_file($file['tmp_name'], $targetFile)) {

                    // Đường dẫn ảnh để hiển thị
                    $url = '/assets/uploads/content/' . $newFileName;
                    $message = 'Tải ảnh thành công!';

                    // --- QUAN TRỌNG: KIỂM TRA LOẠI REQUEST ---

                    // Trường hợp 1: CKEditor 4 Standard (Dùng filebrowserUploadUrl)
                    // Nó sẽ gửi kèm tham số CKEditorFuncNum trên URL
                    if (isset($_GET['CKEditorFuncNum'])) {
                        $funcNum = $_GET['CKEditorFuncNum'];
                        // Trả về đoạn script để điền URL vào ô input
                        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
                        exit();
                    }

                    // Trường hợp 2: CKEditor 5 hoặc Upload Adapter JSON
                    echo json_encode([
                        "uploaded" => 1,
                        "fileName" => $newFileName,
                        "url"      => $url
                    ]);
                    exit();
                }
            }
        }

        // Trả về lỗi
        $errorMsg = 'Upload thất bại (File không hợp lệ)';
        if (isset($_GET['CKEditorFuncNum'])) {
            $funcNum = $_GET['CKEditorFuncNum'];
            echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '', '$errorMsg');</script>";
        } else {
            echo json_encode(["uploaded" => 0, "error" => ["message" => $errorMsg]]);
        }
        exit();
    }
}
