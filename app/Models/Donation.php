<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Donation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function donationCategory(): BelongsTo
    {
        return $this->belongsTo(DonationCategory::class);
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'donations_people');
    }
}
