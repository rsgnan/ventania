<?php

namespace App\Repository;

use PDO;

class ActivityLogRepository
{
    public function __construct(private PDO $pdo) {}

    public function create(
        string $recordType,
        int $recordId,
        string $recordLabel,
        string $action,
        int $userId
    ): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO `activity_logs`
            (`record_type`, `record_id`, `record_label`, `action`, `user_id`)
            VALUES
            (:record_type, :record_id, :record_label, :action, :user_id)'
        );

        $stmt->bindValue(':record_type', $recordType);
        $stmt->bindValue(':record_id', $recordId, PDO::PARAM_INT);
        $stmt->bindValue(':record_label', $recordLabel);
        $stmt->bindValue(':action', $action);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }
}