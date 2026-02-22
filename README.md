<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
</p>

# 複製專案
git clone https://github.com/Richard771203/RHINOSHIELD.git

# 建立資料表
php artisan migrate
/example-app/database/migrations/2026_02_15_124140_create_shipments_table.php
/example-app/database/migrations/2026_02_15_124240_create_shipment_statuses_table.php

# 執行 Migration(標準貨態的測試資料)
php artisan migrate:fresh --seed --seeder=LogisticsTestSeeder
