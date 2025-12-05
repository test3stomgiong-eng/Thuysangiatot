<style>
    .inventory-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #eee;
    }
    .card-header-custom {
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
        font-weight: 600;
        color: #333;
        font-size: 16px;
    }
    .card-body-custom {
        padding: 20px;
    }
    /* Table styling */
    .inventory-table thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        padding: 12px;
    }
    .inventory-table tbody td {
        vertical-align: middle !important;
        padding: 10px 12px;
        border-bottom: 1px solid #eee;
    }
    /* Form controls trong bảng */
    .table-input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        transition: border-color 0.15s ease-in-out;
    }
    .table-input:focus {
        border-color: #80bdff;
        outline: 0;
    }
    .row-total {
        font-weight: bold;
        color: #333;
    }
    /* Nút xóa */
    .btn-del-row {
        color: #dc3545;
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px 10px;
        font-size: 16px;
        transition: all 0.2s;
        border-radius: 4px;
    }
    .btn-del-row:hover {
        background-color: #dc3545;
        color: white;
    }
    /* Cột bên phải */
    .summary-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
    }
    .grand-total-box {
        text-align: right;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px dashed #dee2e6;
    }
    .grand-total-label {
        font-size: 16px; color: #666;
    }
    .grand-total-value {
        font-size: 28px;
        font-weight: bold;
        color: #28a745;
        display: block;
        margin-top: 5px;
    }
    /* Nút thêm dòng */
    .btn-add-row {
        background-color: #e9ecef;
        color: #333;
        border: 1px solid #ced4da;
        font-weight: 600;
    }
    .btn-add-row:hover {
        background-color: #dee2e6;
    }
</style>

<div class="content-body">
    <div class="page-header-row">
        <div>
            <h2 class="page-title" style="margin-bottom: 5px;">Tạo Phiếu Nhập Kho</h2>
            <p style="color: #777; margin: 0;">Nhập hàng hóa vào kho và ghi nhận giá vốn.</p>
        </div>
        <a href="/admin/inventory" class="btn-action btn-gray"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
    </div>

    <form action="/admin/inventory/store" method="POST" id="inventory-form">
        <div class="row" style="display: flex; gap: 20px;">
            
            <div class="col-main" style="flex: 3;">
                <div class="inventory-card">
                    <div class="card-header-custom">
                        <i class="fa-solid fa-list"></i> Danh sách sản phẩm nhập
                    </div>
                    <div class="card-body-custom" style="padding: 0;">
                        <table class="table inventory-table" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th width="45%">Sản phẩm <span style="color:red">*</span></th>
                                    <th width="15%">Số lượng <span style="color:red">*</span></th>
                                    <th width="20%">Giá vốn nhập (đ) <span style="color:red">*</span></th>
                                    <th width="15%" style="text-align: right;">Thành tiền</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody id="import-body">
                                <tr class="item-row">
                                    <td>
                                        <select name="product_id[]" class="table-input select-prod" required>
                                            <option value="">-- Chọn sản phẩm --</option>
                                            <?php foreach($products as $p): ?>
                                                <option value="<?php echo $p->id; ?>">
                                                    [<?php echo $p->sku; ?>] <?php echo $p->name; ?> (Tồn: <?php echo $p->stock; ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity[]" class="table-input qty" value="1" min="1" required oninput="calcTotal()">
                                    </td>
                                    <td>
                                        <input type="number" name="price[]" class="table-input price" value="" min="0" placeholder="VD: 150000" required oninput="calcTotal()">
                                    </td>
                                    <td class="row-total" style="text-align: right;">0đ</td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn-del-row" onclick="removeRow(this)" title="Xóa dòng">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                     <div class="card-body-custom" style="background: #f9f9f9; border-top: 1px solid #eee;">
                        <button type="button" class="btn-action btn-add-row" onclick="addRow()">
                            <i class="fa-solid fa-plus-circle"></i> Thêm dòng sản phẩm
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-side" style="flex: 1;">
                <div class="inventory-card summary-section">
                    <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px;">Thông tin phiếu nhập</h3>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="font-weight: 600; margin-bottom: 5px; display: block;">Người nhập:</label>
                        <input type="text" class="form-control" value="<?php echo $_SESSION['customer_user']['fullname']; ?>" disabled style="background: #eee;">
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 600; margin-bottom: 5px; display: block;">Ghi chú / Chứng từ kèm theo:</label>
                        <textarea name="note" rows="5" class="form-control" placeholder="VD: Nhập hàng từ nhà cung cấp ABC, theo hóa đơn số 123..."></textarea>
                    </div>
                    
                    <div class="grand-total-box">
                        <span class="grand-total-label">Tổng tiền thanh toán:</span>
                        <span id="grand-total" class="grand-total-value">0đ</span>
                    </div>

                    <button type="submit" class="btn-action btn-green" style="width:100%; padding: 15px; font-size: 18px; margin-top: 20px;">
                        <i class="fa-solid fa-floppy-disk"></i> HOÀN TẤT NHẬP KHO
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Format số tiền sang dạng 1.234.567đ
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    }

    // Hàm tính toán tổng tiền
    function calcTotal() {
        var grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(function(row) {
            var qty = parseFloat(row.querySelector('.qty').value) || 0;
            var price = parseFloat(row.querySelector('.price').value) || 0;
            var total = qty * price;
            
            // Cập nhật thành tiền từng dòng
            row.querySelector('.row-total').innerText = formatCurrency(total);
            grandTotal += total;
        });
        // Cập nhật tổng cộng
        document.getElementById('grand-total').innerText = formatCurrency(grandTotal);
    }

    // Hàm thêm dòng mới
    function addRow() {
        // Clone dòng đầu tiên
        var templateRow = document.querySelector('.item-row');
        var newRow = templateRow.cloneNode(true);
        
        // Reset giá trị các ô input trong dòng mới
        newRow.querySelector('.select-prod').value = "";
        newRow.querySelector('.qty').value = 1;
        newRow.querySelector('.price').value = ""; // Để trống giá vốn để người dùng tự nhập
        newRow.querySelector('.row-total').innerText = '0đ';
        
        // Thêm vào cuối bảng
        document.getElementById('import-body').appendChild(newRow);
        
        // Focus vào ô chọn sản phẩm của dòng mới cho tiện
        newRow.querySelector('.select-prod').focus();
    }

    // Hàm xóa dòng
    function removeRow(btn) {
        var rows = document.querySelectorAll('.item-row');
        if(rows.length > 1) {
            // Hiệu ứng mờ dần trước khi xóa (tùy chọn)
            btn.closest('tr').style.opacity = '0.5';
            setTimeout(() => {
                btn.closest('tr').remove();
                calcTotal(); // Tính lại tổng sau khi xóa
            }, 200);
        } else {
            alert('Phiếu nhập phải có ít nhất 1 dòng sản phẩm!');
        }
    }

    // Gọi tính toán lần đầu khi trang vừa tải
    document.addEventListener('DOMContentLoaded', function() {
        calcTotal();
    });
</script>