<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * 查詢標準化貨態 API
     */
    public function query(Request $request)
    {
        //取得單號
       $numbers = $request->query('numbers');
        
        if (!$numbers) {
            return response()->json(['error' => '請提供物流追蹤編號'], 400);
        }

        $trackingNumbers = explode(',', $numbers);

        // 撈取資料並關聯貨態歷程，按時間由新到舊排序
        $shipments = Shipment::whereIn('tracking_number', $trackingNumbers)
            ->with(['statuses' => function ($query) {
                $query->orderBy('occurred_at', 'desc');
            }])
            ->get();
        
        // 格式化輸出
        $data = $shipments->map(function ($shipment) {
            return [
                'tracking_number' => $shipment->tracking_number,
                'logistics_provider' => $shipment->logistics_type,
                'history' => $shipment->statuses->map(function ($status) {
                    return [
                        'status' => $status->status, 
                        'description' => $status->description,
                        'time' => $status->occurred_at 
                        ? \Carbon\Carbon::parse($status->occurred_at)->toDateTimeString() 
                        : null,
                    ];
                })
            ];
        });

        return response()->json(['data' => $data]);
    }
}