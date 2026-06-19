<?php

$migrations = [
    'create_venues_table.php' => '2026_05_02_103601_create_venues_table.php',
    'create_events_table.php' => '2026_05_02_103602_create_events_table.php',
    'create_ticket_types_table.php' => '2026_05_02_103603_create_ticket_types_table.php',
    'create_coupons_table.php' => '2026_05_02_103604_create_coupons_table.php',
    'create_orders_table.php' => '2026_05_02_103605_create_orders_table.php',
    'create_z_order_items_table.php' => '2026_05_02_103606_create_order_items_table.php',
    'create_z_tickets_table.php' => '2026_05_02_103607_create_tickets_table.php',
    'create_z_payments_table.php' => '2026_05_02_103608_create_payments_table.php',
    'create_z_coupon_usages_table.php' => '2026_05_02_103609_create_coupon_usages_table.php',
    'create_z_refund_requests_table.php' => '2026_05_02_103610_create_refund_requests_table.php',
    'create_scans_table.php' => '2026_05_02_103611_create_scans_table.php',
    'create_activity_logs_table.php' => '2026_05_02_103612_create_activity_logs_table.php',
];

$dir = __DIR__.'/database/migrations/';
$files = glob($dir.'*.php');

foreach ($files as $file) {
    foreach ($migrations as $search => $newName) {
        if (str_ends_with($file, $search)) {
            rename($file, $dir.$newName);
            echo 'Renamed to: '.$newName."\n";
        }
    }
}
