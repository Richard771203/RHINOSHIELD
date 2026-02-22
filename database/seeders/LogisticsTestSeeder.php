<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\LogisticsService;

class LogisticsTestSeeder extends Seeder
{
    /**
     * 這裡一定要包在 class 裡面
     */
    public function run(LogisticsService $service): void
    {
        // 1. 犀牛宅急便 (T-RHINO) 第一筆
        $rhinoData = [
            "tracking_number" => "TRHINO20250101001",
            "time_zone" => "+09:00",
            "delivery_records" => [
                ["time" => "2025-01-15 08:00:00", "status_code" => "01", "location" => "台北營業所", "description" => "商品已收件"],
                ["time" => "2025-01-15 10:30:00", "status_code" => "02", "location" => "台北轉運中心", "description" => "理貨中"]
            ]
        ];
        $service->updateTracking('T-RHINO', $rhinoData);

        // 犀牛宅急便 測試第二筆貨態
        $rhinoData1 = [
            "tracking_number" => "TRHINO20250101002",
            "time_zone" => "+09:00",
            "delivery_records" => [
                ["time" => "2025-02-21 08:00:00", "status_code" => "01", "location" => "台北營業所", "description" => "商品已收件"],
                ["time" => "2025-02-22 10:30:00", "status_code" => "06", "location" => "台北轉運中心", "description" => "包裹退回"]
            ]
        ];
        $service->updateTracking('T-RHINO', $rhinoData1);

        // 2. 台中貨運 (TCT)
        $tctData = [
            "tracking_id" => "TCT2025010500123",
            "current_status" => "DELIVERING",
            "message" => "配送中",
            "location_name" => "台中物流中心",
            "last_update" => "2025-01-15T14:20:00Z"
        ];
        $service->updateTracking('TCT', $tctData);

        // 3. 8-TWELVE 超商取貨 (TWELVE)
        $xmlData = '<?xml version="1.0" encoding="UTF-8"?>
        <ShipmentInfo>
          <OrderNo>TWELVE-20250115-A001</OrderNo>
          <StatusCode>4</StatusCode>
          <StatusName>已配達門市</StatusName>
          <UpdateDateTime>2025-01-16 07:00:00</UpdateDateTime>
          <TrackingHistory>
            <Record>
              <DateTime>2025-01-15 19:00:00</DateTime>
              <Status>3</Status>
              <Description>門市配送中</Description>
            </Record>
            <Record>
              <DateTime>2025-01-14 20:00:00</DateTime>
              <Status>1</Status>
              <Description>物流中心已收件</Description>
            </Record>
          </TrackingHistory>
        </ShipmentInfo>';
        $service->updateTracking('TWELVE', $xmlData);
    }
}