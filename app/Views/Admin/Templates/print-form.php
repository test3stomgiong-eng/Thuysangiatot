<div class="content-body" style="background: #f0f2f5; min-height: 100vh;">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>

    <div class="page-header-row" style="background: #fff; padding: 15px 20px; border-bottom: 1px solid #ddd; margin: -20px -20px 20px -20px; position: sticky; top: 0; z-index: 1000; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <h2 class="page-title" style="margin: 0; font-size: 18px;">
            <?php echo isset($template) ? 'Chỉnh sửa mẫu in' : 'Thêm mẫu in mới'; ?>
        </h2>
        <div class="actions">
            <a href="/admin/template" class="btn-action btn-gray" style="background: #fff; border: 1px solid #ddd; color: #333; padding: 8px 15px; text-decoration:none; margin-right:5px;">Hủy</a>
            <button class="btn-action btn-green" onclick="document.getElementById('form-template').submit()" style="background: #28a745; color: white; border: none; padding: 8px 15px; cursor: pointer;">
                <i class="fa-solid fa-floppy-disk"></i> Lưu mẫu
            </button>
        </div>
    </div>

    <form id="form-template" action="/admin/template/save" method="POST" style="display: flex; gap: 20px; align-items: flex-start;">
        
        <?php if(isset($template)): ?>
            <input type="hidden" name="id" value="<?php echo $template->id; ?>">
        <?php endif; ?>

        <div style="flex: 3; background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Tên mẫu in <span style="color:red">*</span></label>
                <input type="text" name="name" required class="form-control" 
                       value="<?php echo isset($template) ? $template->name : ''; ?>" 
                       placeholder="Ví dụ: Hóa đơn bán lẻ A4..."
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-top:5px;">
            </div>
            
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Nội dung thiết kế</label>
            <textarea name="content" id="myTextarea">
                <?php echo isset($template) ? $template->content : ''; ?>
            </textarea>
            
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                <label style="cursor: pointer; user-select: none;">
                    <input type="checkbox" name="is_default" value="1" <?php echo (isset($template) && $template->is_default) ? 'checked' : ''; ?>>
                    <strong>Đặt làm mẫu mặc định</strong> (Sẽ được chọn ưu tiên khi in)
                </label>
            </div>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <div class="setting-box" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 15px; position: sticky; top: 80px; max-height: 85vh; overflow-y: auto;">
                
                <h4 style="margin-top:0; font-size:14px; border-bottom:1px solid #eee; padding-bottom:10px; color:#007bff; text-transform:uppercase;">
                    <i class="fa-solid fa-code"></i> Từ khóa dữ liệu
                </h4>
                
                <div id="list-keywords">
                    
                    <div class="key-group">
                        <strong class="group-title">1. THÔNG TIN CHUNG</strong>
                        <div class="key-list">
                            <span class="badge-key" onclick="insertVar('{MA_DON}')">Mã Đơn</span>
                            <span class="badge-key" onclick="insertVar('{NGAY_TAO}')">Ngày Tạo</span>
                        </div>
                    </div>

                    <div class="key-group">
                        <strong class="group-title">2. KHÁCH HÀNG</strong>
                        <div class="key-list">
                            <span class="badge-key" onclick="insertVar('{TEN_KHACH}')">Tên Khách</span>
                            <span class="badge-key" onclick="insertVar('{SDT_KHACH}')">SĐT</span>
                            <span class="badge-key" onclick="insertVar('{DIA_CHI}')">Địa Chỉ</span>
                        </div>
                    </div>

                    <div class="key-group highlight-box">
                        <strong class="group-title" style="color:#856404;">3. CHI TIẾT SẢN PHẨM (TỰ VẼ)</strong>
                        <p style="font-size:11px; color:#666; margin:5px 0;">
                            <i class="fa-solid fa-lightbulb"></i> <b>HD:</b> Vẽ bảng 2 dòng. Dòng 1 ghi tiêu đề. Dòng 2 chèn các key dưới đây vào các ô. Hệ thống sẽ tự lặp lại dòng 2.
                        </p>
                        
                        <div class="key-list">
                            <span class="badge-key orange" onclick="insertVar('{SP_STT}')" title="Số thứ tự">STT</span>
                            <span class="badge-key orange" onclick="insertVar('{SP_MA}')" title="Mã sản phẩm">Mã SP</span>
                            <span class="badge-key orange" onclick="insertVar('{SP_TEN}')" title="Tên hàng hóa">Tên Hàng</span>
                            <span class="badge-key orange" onclick="insertVar('{SP_DVT}')" title="Đơn vị tính">ĐVT</span>
                            <span class="badge-key orange" onclick="insertVar('{SP_SL}')" title="Số lượng mua">SL</span>
                            <span class="badge-key orange" onclick="insertVar('{SP_GIA_LE}')" title="Giá bán lẻ">Giá Lẻ</span>
                            <span class="badge-key orange" onclick="insertVar('{SP_GIA_CK}')" title="Giá đã chiết khấu">Giá CK</span>
                            <span class="badge-key orange" onclick="insertVar('{SP_THANH_TIEN}')" title="Thành tiền">Thành Tiền</span>
                        </div>
                    </div>

                    <div class="key-group">
                        <strong class="group-title">4. HOẶC DÙNG BẢNG CÓ SẴN</strong>
                        <div class="key-item btn-block" onclick="insertVar('{BANG_HANG_CHI_TIET}')" style="background:#007bff; color:white; text-align:center;">
                            <i class="fa-solid fa-table"></i> CHÈN BẢNG TỰ ĐỘNG
                        </div>
                    </div>

                    <div class="key-group">
                        <strong class="group-title">5. TỔNG KẾT</strong>
                        <div class="key-list">
                            <span class="badge-key" onclick="insertVar('{TONG_TIEN_HANG}')">Tiền Hàng</span>
                            <span class="badge-key" onclick="insertVar('{PHI_SHIP}')">Ship</span>
                            <span class="badge-key red" onclick="insertVar('{TONG_CONG}')">TỔNG CỘNG</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .key-group { margin-bottom: 15px; border-bottom: 1px dashed #eee; padding-bottom: 10px; }
    .key-group:last-child { border-bottom: none; }
    
    .group-title { font-size: 11px; color: #888; font-weight: bold; display: block; margin-bottom: 8px; }
    
    .key-list { display: flex; flex-wrap: wrap; gap: 5px; }
    
    .badge-key {
        display: inline-block; padding: 5px 8px; background: #fff; border: 1px solid #ccc;
        border-radius: 4px; font-size: 11px; font-weight: bold; color: #333; cursor: pointer; user-select: none;
        transition: all 0.2s;
    }
    .badge-key:hover { background: #e2e6ea; border-color: #adb5bd; transform: translateY(-1px); }
    
    .badge-key.orange { background: #fff3cd; border-color: #ffeeba; color: #856404; }
    .badge-key.orange:hover { background: #ffe8a1; }
    
    .badge-key.red { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }
    
    .highlight-box { background: #fffcf5; padding: 10px; border: 1px solid #faeec7; border-radius: 5px; }
    
    .key-item.btn-block {
        display: block; padding: 8px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: bold;
    }
</style>

<script>
    tinymce.init({
        selector: '#myTextarea',
        height: 750,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | ' +
                 'bold italic forecolor backcolor | alignleft aligncenter alignright | ' +
                 'table | bullist numlist | ' +
                 'removeformat code preview',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; line-height:1.5; } table { width: 100%; border-collapse: collapse; } td, th { border: 1px solid #ccc; padding: 5px; }'
    });

    // Hàm chèn biến
    function insertVar(text) {
        tinymce.activeEditor.insertContent(text);
    }
</script>