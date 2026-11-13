<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait Auditable
{
    /**
     * Registra os eventos de auditoria no boot do Model (convenção boot{Trait}).
     */
    public static function bootAuditable(): void
    {
        static::created(function (Model $model): void {
            AuditLog::record(
                action: 'created',
                auditable: $model,
                newValues: Arr::except($model->getAttributes(), array_merge($model->getHidden(), ['created_at', 'updated_at'])),
            );
        });

        static::updated(function (Model $model): void {
            // Filtra apenas os atributos que realmente sofreram alteração (ignorando campos ocultos e updated_at)
            $changes = Arr::except($model->getChanges(), array_merge($model->getHidden(), ['updated_at']));

            if ($changes === []) {
                return;
            }

            AuditLog::record(
                action: 'updated',
                auditable: $model,
                oldValues: Arr::only($model->getOriginal(), array_keys($changes)),
                newValues: $changes,
            );
        });
    }
}
