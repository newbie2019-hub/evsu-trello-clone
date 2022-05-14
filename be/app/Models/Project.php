<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i A',
        'updated_at' => 'datetime:Y-m-d h:i A',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function owner(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function members(){
        return $this->hasManyThrough(User::class, ProjectMember::class, 'project_id', 'id', 'id', 'user_id');
    }

    public function project_members(){
        return $this->hasMany(ProjectMember::class, 'project_id', 'id');
    }

    public function boards(){
        return $this->hasMany(Board::class, 'project_id', 'id')->orderBy('order');
    }

    public function tasks(){
        return $this->hasMany(Task::class, 'project_id', 'id');
    }

    public function setSlugAttribute($title){
        return $this->attributes['slug'] = $this->uniqueSlug($title);
    }
 
    public function uniqueSlug($title){
         $slug = Str::slug($title, '-');
         $count = Project::where('slug', 'LIKE', "{$slug}%")->count();
         $newCount = $count > 0 ? ++$count : '';
         return $newCount > 0 ? "$slug-$newCount" : $slug;
     }
 
     public static function updateUniqueSlug($title){
         $slug = Str::slug($title, '-');
         $count = Project::where('slug', 'LIKE', "{$slug}%")->count();
         $newCount = $count > 1 ? ++$count : '';
         return $newCount > 1 ? "$slug-$newCount" : $slug;
     }
}
