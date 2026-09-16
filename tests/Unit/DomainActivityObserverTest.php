<?php

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Observers\DomainActivityObserver;
use Modules\Core\Services\DomainActivityLogger;

test('domain activity observer forwards lifecycle events to the canonical logger', function () {
    $logger = new class extends DomainActivityLogger
    {
        public array $calls = [];

        public function lifecycle(Model $model, string $verb): void
        {
            $this->calls[] = [$model, $verb];
        }
    };

    $model = new class extends Model {};
    $model->setRawAttributes(['name' => 'before']);
    $model->syncOriginal();
    $model->setAttribute('name', 'after');
    $model->syncChanges();

    $observer = new DomainActivityObserver($logger);
    $observer->created($model);
    $observer->updated($model);
    $observer->deleted($model);
    $observer->restored($model);

    expect(array_column($logger->calls, 1))
        ->toBe(['created', 'updated', 'deleted', 'restored']);
});

test('domain activity observer does not log an update without changes', function () {
    $logger = new class extends DomainActivityLogger
    {
        public int $calls = 0;

        public function lifecycle(Model $model, string $verb): void
        {
            $this->calls++;
        }
    };

    $observer = new DomainActivityObserver($logger);
    $observer->updated(new class extends Model {});

    expect($logger->calls)->toBe(0);
});
