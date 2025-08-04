<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Indicator extends Model
{
    protected $guarded = [];

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }
}
