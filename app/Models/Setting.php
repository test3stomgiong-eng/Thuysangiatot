<?php

namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    public function getSettings()
    {
        // 1. Lấy tất cả dòng trong bảng settings
        $data = $this->all('settings');

        // 2. Chuyển đổi sang dạng Key => Value
        // Mặc định nó trả về: [0] => {config_key: 'site_title', config_value: 'TS-Aqua...'}
        // Mình muốn đổi thành: ['site_title' => 'TS-Aqua...', 'site_logo' => '...']

        $settings = [];
        foreach ($data as $row) {
            $settings[$row->config_key] = $row->config_value;
            $settings[$row->config_key . '_desc'] = $row->description;
        }

        return $settings;
    }

    // public function updateValue($key, $value)
    // {
    //     // Cập nhật giá trị dựa theo config_key
    //     $sql = "UPDATE settings SET config_value = :val WHERE config_key = :key";

    //     $stmt = $this->query($sql);
    //     return $stmt->execute([
    //         ':val' => $value,
    //         ':key' => $key
    //     ]);
    // }
}
