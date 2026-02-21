<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentStatus extends Model
{
    // 1. 保留你定義的標準貨態常量
    const STATUS_PICKED_UP = 'picked_up';
    const STATUS_PROCESSING = 'processing';
    const STATUS_IN_TRANSIT = 'in_transit';
    const STATUS_DELIVERY = 'out_for_delivery';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_FAILED = 'failed';
    const STATUS_RETURNED = 'returned';

    // 2. 設定白名單，讓 LogisticsService 可以存入資料
    protected $fillable = [
        'shipment_id',
        'status',
        'original_status',
        'description',
        'occurred_at'
    ];

    /**
     * 3. 反向關聯回 Shipment
     */
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }
}