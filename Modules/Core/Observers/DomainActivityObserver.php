<?php

namespace Modules\Core\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Services\DomainActivityLogger;

class DomainActivityObserver implements ShouldHandleEventsAfterCommit
{
    public function __construct(private readonly DomainActivityLogger $logger) {}

    public function created(Model $model): void
    {
        $this->logger->lifecycle($model, 'created');
    }

    public function updated(Model $model): void
    {
        if ($model->getChanges() !== []) {
            $this->logger->lifecycle($model, 'updated');
        }
    }

    public function deleted(Model $model): void
    {
        $this->logger->lifecycle($model, 'deleted');
    }

    public function restored(Model $model): void
    {
        $this->logger->lifecycle($model, 'restored');
    }
}
