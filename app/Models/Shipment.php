<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    // 允許大量賦值的欄位
    protected $fillable = ['tracking_number', 'logistics_type', 'raw_payload'];

    /**
     * 定義一對多關聯：一個單號有多個狀態
     */
    public function statuses()
    {
        return $this->hasMany(ShipmentStatus::class);
    }
}