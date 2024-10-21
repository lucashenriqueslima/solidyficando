<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory;

    //guarded
    protected $guarded = [];

    public function people(): HasMany
    {
        return $this->hasMany(Person::class);
    }

    public function president(): HasOne
    {
        return $this->hasOne(President::class);
    }
}
