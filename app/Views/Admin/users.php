<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Danh sách Nhân sự</h2>
        <a href="/admin/user/add" class="btn-action btn-blue"><i class="fa-solid fa-user-plus"></i> Thêm nhân viên</a>
    </div>

    <form action="/admin/user" method="GET">
        <div class="filter-bar">
            <div class="search-wrapper" style="width: 100%;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="keyword" 
                       value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>" 
                       placeholder="Tìm theo tên, email...">
            </div>
        </div>
    </form>

    <div class="data-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Họ tên</th>
                        <th>Liên hệ</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th width="100">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td data-label="ID">#<?php echo $u->id; ?></td>
                                <td data-label="Họ tên"><strong><?php echo $u->fullname; ?></strong></td>
                                
                                <td data-label="Liên hệ">
                                    <small><i class="fa-solid fa-envelope"></i> <?php echo $u->email; ?></small><br>
                                    <small><i class="fa-solid fa-phone"></i> <?php echo $u->phone; ?></small>
                                </td>
                                
                                <td data-label="Vai trò">
                                    <?php 
                                        switch($u->role) {
                                            case 'admin': echo '<span class="badge bg-danger">Quản trị viên</span>'; break;
                                            case 'sale': echo '<span class="badge bg-info text-dark">Sale Online</span>'; break;
                                            case 'warehouse': echo '<span class="badge bg-warning text-dark">Thủ kho</span>'; break;
                                            case 'editor': echo '<span class="badge bg-secondary">Biên tập viên</span>'; break;
                                        }
                                    ?>
                                </td>

                                <td data-label="Trạng thái">
                                    <?php echo ($u->status == 1) ? '<span style="color:green; font-weight:bold;">Hoạt động</span>' : '<span style="color:red;">Đã nghỉ</span>'; ?>
                                </td>

                                <td data-label="Hành động">
                                    <div class="action-btns">
                                        <a href="/admin/user/edit/<?php echo $u->id; ?>" class="btn-icon edit"><i class="fa-solid fa-pen"></i></a>
                                        
                                        <?php if ($u->id != $_SESSION['customer_user']['id']): ?>
                                            <a href="/admin/user/delete/<?php echo $u->id; ?>" 
                                               class="btn-icon delete" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?');">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>