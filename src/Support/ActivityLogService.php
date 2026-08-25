<?php

namespace App\Support;

use App\Repository\ActivityLogRepository;

class ActivityLogService
{
    public function __construct(
        private ActivityLogRepository $activityLogRepository,
        private AuthService $authService
    ) {}

    public function log(
        string $recordType,
        int $recordId,
        string $recordLabel,
        string $action
    ): bool {
        $userId = $this->authService->getUserId();

        if ($userId === null) {
            return false;
        }

        return $this->activityLogRepository->create(
            $recordType,
            $recordId,
            $recordLabel,
            $action,
            $userId
        );
    }
}