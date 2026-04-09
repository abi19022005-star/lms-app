<?php

namespace App\Observers;

use App\Models\Course;

class CourseObserver
{
    /**
     * Handle the Course "created" event.
     */
    public function created($model)
    {
        logActivity([
            'action' => 'create',
            'action_type' => 'CREATE',
            'module' => 'course',
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'model_name' => $model->judul,
            'new_data' => json_encode($model),
            'description' => 'Menambahkan data course',
        ]);
    }

    public function updated($model)
    {
        logActivity([
            'action' => 'update',
            'action_type' => 'UPDATE',
            'module' => 'course',
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'model_name' => $model->judul,
            'old_data' => json_encode($model->getOriginal()),
            'new_data' => json_encode($model),
            'description' => 'Mengupdate course',
        ]);
    }

    public function deleted($model)
    {
        logActivity([
            'action' => 'delete',
            'action_type' => 'DELETE',
            'module' => 'course',
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'model_name' => $model->judul,
            'description' => 'Menghapus course',
        ]);
    }

    /**
     * Handle the Course "restored" event.
     */
    public function restored(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "force deleted" event.
     */
    public function forceDeleted(Course $course): void
    {
        //
    }
}
