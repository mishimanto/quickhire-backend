<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'open_jobs';

    protected $fillable = [
        'title',
        'company',
        'location',
        'category',
        'type',
        'salary_range',
        'description',
        'requirements',
        'logo_url',
        'is_featured',
        'category_id',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}