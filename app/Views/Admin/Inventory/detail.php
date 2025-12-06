<div class="content-body">
    <div class="page-header-row">
        <div>
            <h2 class="page-title">Phiếu nhập kho: <span style="color:#007bff"><?php echo $receipt->code; ?></span></h2>
            <span class="sub-text">Ngày nhập: <?php echo date('d/m/Y H:i', strtotime($receipt->created_at)); ?></span>
        </div>
        <div class="actions">
            <a href="/admin/inventory" class="btn-action btn-gray"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
            <button class="btn-action btn-blue" onclick="window.print()"><i class="fa-solid fa-print"></i> In phiếu</button>
        </div>
    </div>

    <div class="row" style="display: flex; gap: 20px; flex-wrap: wrap;">
        
        <div class="col-main" style="flex: 3; min-width: 300px;">
            <div class="data-card">
                <h3 class="card-title" style="border-bottom:1px solid #eee; padding-bottom:10px; margin-bottom:15px;">Danh sách sản phẩm nhập</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr style="background:#f9f9f9;">
                                <th>Sản phẩm</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-right">Giá vốn</th>
                                <th class="text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($details as $item): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $item->product_name; ?></strong><br>
                                    <small style="color:#888">SKU: <?php echo $item->sku; ?></small>
                                </td>
                                <td class="text-center" style="font-weight:bold;">
                                    <?php echo $item->quantity; ?>
                                </td>
                                <td class="text-right">
                                    <?php echo number_format($item->import_price); ?>đ
                                </td>
                                <td class="text-right" style="color:#d0021b; font-weight:bold;">
                                    <?php echo number_format($item->quantity * $item->import_price); ?>đ
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right" style="padding:15px; font-weight:bold;">TỔNG TIỀN VỐN:</td>
                                <td class="text-right" style="padding:15px; font-size:18px; font-weight:bold; color:red;">
                                    <?php echo number_format($receipt->total_money); ?>đ
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-side" style="flex: 1; min-width: 250px;">
            <div class="data-card">
                <h3 class="card-title">Thông tin chung</h3>
                
                <div class="info-row" style="margin-bottom:15px;">
                    <strong style="display:block; color:#555;">Người nhập:</strong>
                    <span><i class="fa-solid fa-user"></i> <?php echo $receipt->user_name; ?></span>
                </div>

                <div class="info-row" style="margin-bottom:15px;">
                    <strong style="display:block; color:#555;">Ghi chú:</strong>
                    <div style="background:#f9f9f9; padding:10px; border-radius:4px; font-style:italic; color:#666; border:1px solid #eee;">
                        <?php echo !empty($receipt->note) ? $receipt->note : 'Không có ghi chú.'; ?>
                    </div>
                </div>
                
                <div class="alert alert-info" style="background:#e3f2fd; color:#0d47a1; padding:10px; border-radius:4px; font-size:13px;">
                    <i class="fa-solid fa-circle-info"></i> Phiếu nhập này đã được ghi nhận và cộng dồn vào kho hàng.
                </div>
            </div>
        </div>

    </div>
</div>