<div class="breadcrumb container">
    <a href="/">Trang chủ</a> <i class="fa-solid fa-angle-right"></i>
    <span>Tài khoản của tôi</span>
</div>

<section class="profile-page container section-padding" style="margin: 40px auto;">
    <div class="row" style="display: flex; gap: 30px; flex-wrap: wrap;">

        <div class="col-profile" style="flex: 1; min-width: 300px;">
            <div class="data-card" style="background: #fff; padding: 30px; border-radius: 8px; border: 1px solid #eee; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                <h3 style="margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 15px; color: #007bff;">
                    <i class="fa-solid fa-user-pen"></i> Thông tin cá nhân
                </h3>

                <form action="/user/update" method="POST">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Họ và tên</label>
                        <input type="text" name="fullname" value="<?php echo $user->fullname; ?>" required
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Số điện thoại</label>
                        <input type="text" name="phone" value="<?php echo $user->phone; ?>" required
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Email</label>
                        <input type="email" name="email" value="<?php echo $user->email; ?>"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Địa chỉ giao hàng mặc định</label>
                        <textarea name="address" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"><?php echo $user->address; ?></textarea>
                    </div>

                    <button type="submit" class="btn-save"
                        style="width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                        CẬP NHẬT THÔNG TIN
                    </button>
                </form>

                <div style="margin-top: 20px; text-align: center;">
                    <a href="/auth/logout" style="color: #dc3545; text-decoration: none; font-weight: bold;">
                        <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                    </a>
                </div>

                <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">

                <h3 style="margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 15px; color: #dc3545;">
                    <i class="fa-solid fa-key"></i> Đổi mật khẩu
                </h3>

                <form action="/user/changePassword" method="POST">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Mật khẩu hiện tại</label>
                        <input type="password" name="old_password" required placeholder="******"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Mật khẩu mới</label>
                        <input type="password" name="new_password" required placeholder="******"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Xác nhận mật khẩu mới</label>
                        <input type="password" name="confirm_password" required placeholder="******"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>

                    <button type="submit"
                        style="width: 100%; padding: 12px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                        ĐỔI MẬT KHẨU
                    </button>
                </form>

            </div>
        </div>

        <div class="col-history" style="flex: 2; min-width: 300px;">
            <div class="data-card" style="background: #fff; padding: 30px; border-radius: 8px; border: 1px solid #eee; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                <h3 style="margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 15px; color: #ff9800;">
                    <i class="fa-solid fa-clock-rotate-left"></i> Lịch sử đơn hàng
                </h3>

                <?php if (!empty($orders)): ?>
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 500px;">
                            <thead>
                                <tr style="background: #f9f9f9; border-bottom: 2px solid #eee;">
                                    <th style="padding: 10px; text-align: left;">Mã đơn</th>
                                    <th style="padding: 10px; text-align: left;">Ngày đặt</th>
                                    <th style="padding: 10px; text-align: right;">Tổng tiền</th>
                                    <th style="padding: 10px; text-align: center;">Trạng thái</th>
                                    <th style="padding: 10px; text-align: center;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr style="border-bottom: 1px solid #eee;">
                                        <td style="padding: 10px; color: #007bff; font-weight: bold;">#<?php echo $order->code; ?></td>
                                        <td style="padding: 10px;"><?php echo date('d/m/Y', strtotime($order->created_at)); ?></td>
                                        <td style="padding: 10px; text-align: right; font-weight: bold; color: #d0021b;">
                                            <?php echo number_format($order->total_money, 0, ',', '.'); ?>đ
                                        </td>
                                        <td style="padding: 10px; text-align: center;">
                                            <?php
                                            switch ($order->status) {
                                                case 'pending':
                                                    echo '<span style="color:orange;">Chờ xử lý</span>';
                                                    break;
                                                case 'shipping':
                                                    echo '<span style="color:blue;">Đang giao</span>';
                                                    break;
                                                case 'completed':
                                                    echo '<span style="color:green;">Hoàn thành</span>';
                                                    break;
                                                case 'cancelled':
                                                    echo '<span style="color:red;">Đã hủy</span>';
                                                    break;
                                            }
                                            ?>
                                        </td>
                                        <td style="padding: 10px; text-align: center;">
                                            <a href="/user/orderDetail/<?php echo $order->id; ?>"
                                                class="btn-action-view" title="Xem chi tiết">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>

                                            <?php if ($order->status == 'pending'): ?>
                                                <a href="/user/cancelOrder/<?php echo $order->id; ?>"
                                                    onclick="return confirm('Bạn muốn hủy đơn hàng này?');"
                                                    class="btn-action-cancel" title="Hủy đơn">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p style="text-align: center; padding: 30px; color: #999;">Bạn chưa có đơn hàng nào.</p>
                    <div style="text-align: center;">
                        <a href="/product" class="btn-save" style="display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">Mua sắm ngay</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>