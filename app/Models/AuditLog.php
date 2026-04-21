<?php
namespace App\Models;

use App\Core\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    /**
     * Get recent logs for an entity
     */
    public function getByEntity($entityType, $entityId, $limit = 20)
    {
        return $this->query(
            "SELECT al.*, u.name as user_name
             FROM audit_logs al
             LEFT JOIN users u ON al.user_id = u.id
             WHERE al.entity_type = :etype AND al.entity_id = :eid
             ORDER BY al.created_at DESC
             LIMIT " . (int)$limit,
            ['etype' => $entityType, 'eid' => $entityId]
        );
    }

    /**
     * Get recent logs
     */
    public function getRecent($limit = 50)
    {
        return $this->query(
            "SELECT al.*, u.name as user_name
             FROM audit_logs al
             LEFT JOIN users u ON al.user_id = u.id
             ORDER BY al.created_at DESC
             LIMIT " . (int)$limit
        );
    }
}
