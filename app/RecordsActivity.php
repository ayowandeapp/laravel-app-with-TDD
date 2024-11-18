<?php

namespace App;

trait RecordsActivity
{
    public $oldAttribute = [];
    //use traits as an observer
    public static function bootRecordsActivity()
    {
        $recordableEvents = ['created', 'updated', 'deleted'];

        foreach ($recordableEvents as $event) {
            static::$event(function ($model) use ($event) {
                $modelName = strtolower(class_basename($model));
                if (class_basename($model) === 'ProjectTask') {
                    if ($event === 'updated') {
                        if (!$model->completed) {
                            return;
                        }
                        $event = 'completed';
                    }
                }
                $event .= "_$modelName";
                $model->saveActivity($event);
            });

            if ($event === 'updated') {
                static::updating(function ($model) {
                    $model->oldAttribute = $model->getOriginal();
                });
            }

        }
    }
    public function saveActivity($description)
    {
        $this->activity()->create([
            'user_id' => ($this->project ?? $this)->owner->id,
            'description' => $description,
            'changes' => $this->loadActivityChanges(),
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id
        ]);
    }

    private function loadActivityChanges()
    {
        if ($this->wasChanged()) {
            return [
                'before' => array_diff($this->oldAttribute, $this->getAttributes()),
                'after' => $this->getChanges()
            ];
        }
    }

}
