<?php

namespace App\Models;

use App\Enums\FinancialMovementFlowType;
use App\Enums\FinancialMovementStatus;
use App\Observers\FinancialMovementObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string|null $asaas_id
 * @property string $description
 * @property float $value
 * @property FinancialMovementFlowType $flow_type
 * @property FinancialMovementStatus $status
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property \Illuminate\Support\Carbon|null $payment_date
 * @property int|null $financial_movement_category_id
 * @property string|null $invoice_url
 * @property string|null $bank_slip_url
 */
class FinancialMovement extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => FinancialMovementStatus::class,
            'flow_type' => FinancialMovementFlowType::class,
            'due_date' => 'date:Y-m-d',
            'payment_date' => 'date:Y-m-d',
        ];
    }


    protected static function booted()
    {
        static::creating(function ($financialMovement) {
            match ($financialMovement->flow_type) {
                FinancialMovementFlowType::IN => $financialMovement->value = abs($financialMovement->value),
                FinancialMovementFlowType::OUT => $financialMovement->value = -abs($financialMovement->value),
            };
        });

        static::updating(function ($financialMovement) {
            match ($financialMovement->flow_type) {
                FinancialMovementFlowType::IN => $financialMovement->value = abs($financialMovement->value),
                FinancialMovementFlowType::OUT => $financialMovement->value = -abs($financialMovement->value),
            };
        });
    }



    public function financialMovementCategory(): BelongsTo
    {
        return $this->belongsTo(FinancialMovementCategory::class);
    }

    public function movementable(): MorphTo
    {
        return $this->morphTo();
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'financial_movement_person')
            ->withPivot('value')
            ->withTimestamps();
    }
}
