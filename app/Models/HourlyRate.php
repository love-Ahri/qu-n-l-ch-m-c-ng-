<?php
namespace App\Models;

use App\Core\Model;

class HourlyRate extends Model
{
    protected $table = 'hourly_rates';

    public function getByUser($userId)
    {
        return $this->query(
            "SELECT hr.*, r.display_name as role_display
             FROM hourly_rates hr
             LEFT JOIN roles r ON hr.role_id = r.id
             WHERE hr.user_id = :uid
             ORDER BY hr.effective_from DESC",
            ['uid' => $userId]
        );
    }

    public function getByRole($roleId)
    {
        return $this->query(
            "SELECT * FROM hourly_rates WHERE role_id = :rid AND user_id IS NULL ORDER BY effective_from DESC",
            ['rid' => $roleId]
        );
    }
}
