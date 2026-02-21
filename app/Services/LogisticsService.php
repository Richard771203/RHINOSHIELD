<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\ShipmentStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LogisticsService
{
    /**
     * 更新物流貨態
     */
    public function updateTracking($type, $rawData)
    {
        return DB::transaction(function () use ($type, $rawData) {
            if ($type === 'T-RHINO') {
                return $this->handleRhino($rawData);
            } elseif ($type === 'TCT') {
                return $this->handleTct($rawData);
            } elseif ($type === 'TWELVE') {
                return $this->handleTwelve($rawData);
            }
        });
    }

    // 1. 處理犀牛 (JSON)
    private function handleRhino($data) {
        $shipment = Shipment::updateOrCreate(
            ['tracking_number' => $data['tracking_number']],
            ['logistics_type' => 'T-RHINO', 'raw_payload' => json_encode($data)]
        );
        $map = ['01'=>'picked_up', '02'=>'processing', '03'=>'out_for_delivery', '04'=>'delivered', '05'=>'failed', '06'=>'returned'];
        foreach ($data['delivery_records'] as $record) {
            $shipment->statuses()->updateOrCreate([
                'occurred_at' => Carbon::parse($record['time'], $data['time_zone'])->tz('UTC'),
            ], [
                'status' => $map[$record['status_code']] ?? 'unknown',
                'original_status' => $record['status_code'],
                'description' => $record['description']
            ]);
        }
    }

    // 2. 處理台中貨運 (JSON)
    private function handleTct($data) {
        $shipment = Shipment::updateOrCreate(
            ['tracking_number' => $data['tracking_id']],
            ['logistics_type' => 'TCT', 'raw_payload' => json_encode($data)]
        );
        $map = ['RECEIVED'=>'picked_up', 'PROCESSING'=>'processing', 'DISPATCHED'=>'in_transit', 'DELIVERING'=>'out_for_delivery', 'COMPLETED'=>'delivered', 'EXCEPTION'=>'failed', 'RETURN'=>'returned'];
        $shipment->statuses()->updateOrCreate([
            'occurred_at' => Carbon::parse($data['last_update'])->tz('UTC'),
        ], [
            'status' => $map[$data['current_status']] ?? 'unknown',
            'original_status' => $data['current_status'],
            'description' => $data['message']
        ]);
    }

    // 3. 處理 8-TWELVE (XML)
    private function handleTwelve($xmlString) {
        $xml = simplexml_load_string($xmlString);
        $shipment = Shipment::updateOrCreate(
            ['tracking_number' => (string)$xml->OrderNo],
            ['logistics_type' => 'TWELVE', 'raw_payload' => json_encode($xml)]
        );
        $map = ['1'=>'picked_up', '2'=>'processing', '3'=>'in_transit', '4'=>'delivered', '5'=>'returned', '97'=>'failed', '98'=>'failed', '99'=>'failed'];
        foreach ($xml->TrackingHistory->Record as $record) {
            $shipment->statuses()->updateOrCreate([
                'occurred_at' => Carbon::createFromFormat('Y-m-d H:i:s', (string)$record->DateTime, 'Asia/Taipei')->tz('UTC'),
            ], [
                'status' => $map[(string)$record->Status] ?? 'unknown',
                'original_status' => (string)$record->Status,
                'description' => (string)$record->Description
            ]);
        }
    }
}