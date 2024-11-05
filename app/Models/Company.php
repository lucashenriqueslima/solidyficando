<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;

class Company extends Authenticatable implements FilamentUser
{
    use HasFactory;

    public function canAccessPanel(Panel $panel): bool
    {
        Log::info($panel->getId());
        return true;
    }

    public function canAccessFilament(Panel $panel): bool
    {
        return true;
    }

    protected $guarded = [];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function people(): HasMany
    {
        return $this->hasMany(Person::class);
    }

    public function president(): HasOne
    {
        return $this->hasOne(President::class);
    }
}
