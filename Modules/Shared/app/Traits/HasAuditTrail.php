<?php

namespace Modules\Shared\Traits;

use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * غلاف فوق spatie LogsActivity: أي Entity بتحتاج تسجيل بتضيف هالـ Trait بس.
 * نفس سلوك الإعدادات الحالية (defaults + logOnlyDirty + اسم السجل).
 *
 * اختياري بالـ Entity:
 *   protected string $auditLogName = 'student';          // الافتراضي: snake_case لاسم الكلاس
 *   protected array  $auditExcept  = ['password'];       // حقول ما بدنا نسجلها
 *
 * يمكن للـ Entity تتجاوز getActivitylogOptions() لو احتاجت إعداد خاص.
 */
trait HasAuditTrail
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        $options = LogOptions::defaults()
            ->logOnlyDirty()
            ->useLogName($this->resolveAuditLogName());

        $except = $this->auditExcept ?? [];
        if ($except !== []) {
            $options->logExcept($except);
        }

        return $options;
    }

    protected function resolveAuditLogName(): string
    {
        return $this->auditLogName ?? Str::snake(class_basename($this));
    }
}
