<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i A',
        'updated_at' => 'datetime:Y-m-d h:i A',
        'actual_finished_date' => 'datetime:Y-m-d h:i A',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function assignee(){
        return $this->hasMany(TaskAssignee::class, 'task_id', 'id');
    }

    public function attachments(){
        return $this->hasMany(TaskAttachment::class, 'task_id', 'id');
    }

    public function comments(){
        return $this->hasMany(TaskComment::class, 'task_id', 'id')->orderBy('created_at', 'desc');
    }

}
