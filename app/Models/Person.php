<?php

namespace App\Models;

use App\Enums\Housing;
use App\Enums\EducationLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function dependents(): HasMany
    {
        return $this->hasMany(PersonDependents::class);
    }

    public function financialMovements(): MorphMany
    {
        return $this->morphMany(FinancialMovement::class, 'movementable');
    }

    public function projects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable');
    }
}
