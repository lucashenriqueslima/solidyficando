<?php

namespace App\Models;

use App\Enums\Housing;
use App\Enums\EducationLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'birthday' => 'date:Y-m-d',
            'education' => EducationLevel::class,
            'housing' => Housing::class,
        ];
    }

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
