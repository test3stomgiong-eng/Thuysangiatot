<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Lịch sử Nhập kho</h2>
        <a href="/admin/inventory/create" class="btn-action btn-blue"><i class="fa-solid fa-plus"></i> Nhập hàng mới</a>
    </div>

    <form action="/admin/inventory" method="GET">
        <div class="filter-bar">
            <div class="search-wrapper" style="width: 100%;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="keyword" 
                       value="<?php echo $keyword ?? ''; ?>" 
                       placeholder="Nhập mã phiếu nhập (VD: PN1764...)">
            </div>
            </div>
    </form>

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
                        <th width="100">Hành động</th> </tr>
                </thead>
                <tbody>
                    <?php if(!empty($receipts)): foreach($receipts as $r): ?>
                    <tr>
                        <td data-label="Mã phiếu">
                            <a href="/admin/inventory/detail/<?php echo $r->id; ?>" style="font-weight:bold; color:#007bff;">
                                <?php echo $r->code; ?>
                            </a>
                        </td>
                        <td data-label="Người nhập"><?php echo $r->user_name; ?></td>
                        <td data-label="Ngày nhập"><?php echo date('d/m/Y H:i', strtotime($r->created_at)); ?></td>
                        <td data-label="Tổng tiền" style="color:red; font-weight:bold;">
                            <?php echo number_format($r->total_money); ?>đ
                        </td>
                        <td data-label="Ghi chú" style="font-size:12px; color:#666;">
                            <?php echo $r->note; ?>
                        </td>
                        <td data-label="Hành động">
                            <a href="/admin/inventory/detail/<?php echo $r->id; ?>" class="btn-icon edit" title="Xem chi tiết">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" style="text-align:center; padding:20px;">Không tìm thấy phiếu nhập nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>