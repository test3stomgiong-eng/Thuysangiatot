<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Chi tiết liên hệ <span style="color:#007bff">#<?php echo $contact->id; ?></span></h2>
        <div class="actions">
            <a href="/admin/contact" class="btn-action btn-gray"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
            
            <a href="mailto:<?php echo $contact->email; ?>" class="btn-action btn-blue">
                <i class="fa-solid fa-reply"></i> Trả lời qua Email
            </a>
            
            <a href="/admin/contact/delete/<?php echo $contact->id; ?>" 
               class="btn-action btn-red" 
               onclick="return confirm('Xóa tin nhắn này?');" 
               style="background: #dc3545; color: white;">
                <i class="fa-solid fa-trash"></i> Xóa
            </a>
        </div>
    </div>

    <div class="data-card form-card" style="max-width: 800px; margin: 0 auto;">
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #eee;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <strong style="font-size: 16px; color: #333;"><?php echo $contact->fullname; ?></strong>
                <span style="color: #888; font-size: 13px;">
                    <i class="fa-regular fa-clock"></i> <?php echo date('d/m/Y H:i', strtotime($contact->created_at)); ?>
                </span>
            </div>
            
            <div style="display: flex; gap: 30px; font-size: 14px;">
                <span>
                    <i class="fa-solid fa-envelope" style="color: #007bff;"></i> 
                    <a href="mailto:<?php echo $contact->email; ?>" style="color: #555; text-decoration: none;"><?php echo $contact->email; ?></a>
                </span>
                <span>
                    <i class="fa-solid fa-phone" style="color: #28a745;"></i> 
                    <a href="tel:<?php echo $contact->phone; ?>" style="color: #555; text-decoration: none;"><?php echo $contact->phone; ?></a>
                </span>
                <span>
                    Trạng thái: 
                    <?php if($contact->status == 1): ?>
                        <span class="badge bg-success">Đã xem</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Chưa xem</span>
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <div class="message-content">
            <label style="font-weight: bold; display: block; margin-bottom: 10px; color: #555;">Nội dung tin nhắn:</label>
            <div style="padding: 20px; border: 1px solid #ddd; border-radius: 4px; background: #fff; min-height: 200px; line-height: 1.6;">
                <?php echo nl2br(htmlspecialchars($contact->message)); ?>
            </div>
        </div>

    </div>
</div>