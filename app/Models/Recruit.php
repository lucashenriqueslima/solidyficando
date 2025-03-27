<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recruit extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function recruitJobs(): HasMany
    {
        return $this->hasMany(RecruitJob::class);
    }

    public function recruitCourses(): HasMany
    {
        return $this->hasMany(RecruitCourse::class);
    }
}
