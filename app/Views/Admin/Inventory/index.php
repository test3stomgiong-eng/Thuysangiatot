<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Lịch sử Nhập kho</h2>
        <a href="/admin/inventory/create" class="btn-action btn-blue"><i class="fa-solid fa-plus"></i> Nhập hàng mới</a>
    </div>

    <div class="data-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Mã Phiếu</th>
                        <th>Người nhập</th>
                        <th>Ngày nhập</th>
                        <th>Tổng tiền vốn</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($receipts)): foreach($receipts as $r): ?>
                    <tr>
                        <td><strong><?php echo $r->code; ?></strong></td>
                        <td><?php echo $r->user_name; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($r->created_at)); ?></td>
                        <td style="color:red; font-weight:bold;"><?php echo number_format($r->total_money); ?>đ</td>
                        <td><?php echo $r->note; ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>