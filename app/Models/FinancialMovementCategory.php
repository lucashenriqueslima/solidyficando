<?php

namespace App\Models;

use App\Enums\FinancialMovementFlowType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialMovementCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'flow_type' => FinancialMovementFlowType::class,
        ];
    }

    public function financialMovements()
    {
        return $this->hasMany(FinancialMovement::class);
    }
}
