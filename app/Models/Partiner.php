<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string|null $asaas_id
 * @property string $name
 * @property string $cpf
 * @property string $email
 * @property string $phone
 * @property bool $is_to_charge
 * @property int $billing_day
 * @property float $monthly_contribution
 * @property \Illuminate\Support\Carbon $birthday
 * @property ?int $institution_id
 * @property ?int $department_id
 *
 */
class Partiner extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function financialMovements()
    {
        return $this->morphMany(FinancialMovement::class, 'movementable');
    }
}
