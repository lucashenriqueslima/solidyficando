<?php

namespace App\Models;

use App\Enums\FinancialMovementFlowType;
use App\Enums\FinancialMovementStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialMovement extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected static function booted()
    {
        static::creating(function ($financialMovement) {
            match ($financialMovement->flow_type) {
                FinancialMovementFlowType::IN => $financialMovement->value = abs($financialMovement->value),
                FinancialMovementFlowType::OUT => $financialMovement->value = -abs($financialMovement->value),
            };
        });
    }

    protected function casts(): array
    {
        return [
            'status' => FinancialMovementStatus::class,
            'flow_type' => FinancialMovementFlowType::class,
            'due_date' => 'date:Y-m-d',
            'payment_date' => 'date:Y-m-d',
        ];
    }
}
