<?php

namespace App\Models;

use App\Enums\FinancialMovementFlowType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property FinancialMovementFlowType $flow_type
 */
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
