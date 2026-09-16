<?php

namespace Modules\Core\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Writes the canonical activity-log shape for domain model lifecycle events.
 */
class DomainActivityLogger
{
    public function record(Model $model, string $action, array $properties = []): void
    {
        $actor = auth()->user();

        activity()
            ->causedBy($actor)
            ->performedOn($model)
            ->withProperties(array_merge([
                'module' => $this->module($model),
                'model' => $model::class,
                'model_id' => $model->getKey(),
                'actor_id' => $actor?->getAuthIdentifier(),
                'actor_type' => $actor ? $actor::class : null,
            ], $properties))
            ->log($action);
    }

    public function lifecycle(Model $model, string $verb): void
    {
        $properties = [];

        if ($verb === 'updated') {
            $properties['changes'] = $this->safeAttributes($model->getChanges());
            $properties['old_values'] = $this->safeAttributes(
                Arr::only($model->getOriginal(), array_keys($model->getChanges()))
            );
        } elseif ($verb === 'created') {
            $properties['new_values'] = $this->safeAttributes($model->getAttributes());
        } elseif ($verb === 'deleted') {
            $properties['old_values'] = $this->safeAttributes($model->getAttributes());
        }

        $this->record($model, sprintf('domain.%s.%s', $this->modelName($model), $verb), $properties);
    }

    private function module(Model $model): string
    {
        return explode('\\', $model::class)[1] ?? 'App';
    }

    private function modelName(Model $model): string
    {
        return strtolower(class_basename($model));
    }

    private function safeAttributes(array $attributes): array
    {
        return Arr::except($attributes, [
            'password',
            'password_confirmation',
            'remember_token',
            'token',
            'secret',
        ]);
    }
}
