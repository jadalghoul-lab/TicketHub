<?php

$migrations = [
    'create_venues_table.php' => "
        Schema::create('venues', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('organizer_id')->constrained()->cascadeOnDelete();
            \$table->string('name');
            \$table->string('address')->nullable();
            \$table->string('city')->nullable();
            \$table->string('country')->nullable();
            \$table->integer('max_capacity')->nullable();
            \$table->timestamps();
            \$table->softDeletes();
            
            \$table->index('organizer_id');
        });
    ",
    'create_events_table.php' => "
        Schema::create('events', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('organizer_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('venue_id')->nullable()->constrained()->nullOnDelete();
            \$table->string('title');
            \$table->string('slug')->unique();
            \$table->text('description')->nullable();
            \$table->string('image')->nullable();
            \$table->string('category')->nullable();
            \$table->date('start_date');
            \$table->date('end_date')->nullable();
            \$table->time('time')->nullable();
            \$table->string('city')->nullable();
            \$table->string('country')->nullable();
            \$table->integer('capacity')->nullable();
            \$table->string('status')->default('draft');
            \$table->timestamps();
            \$table->softDeletes();

            \$table->index('organizer_id');
        });
    ",
    'create_ticket_types_table.php' => "
        Schema::create('ticket_types', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('event_id')->constrained()->cascadeOnDelete();
            \$table->string('name');
            \$table->decimal('price', 10, 2);
            \$table->integer('quantity');
            \$table->dateTime('sales_start')->nullable();
            \$table->dateTime('sales_end')->nullable();
            \$table->integer('max_per_order')->default(10);
            \$table->text('description')->nullable();
            \$table->timestamps();
            \$table->softDeletes();

            \$table->index('event_id');
        });
    ",
    'create_coupons_table.php' => "
        Schema::create('coupons', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('organizer_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('event_id')->nullable()->constrained()->cascadeOnDelete();
            \$table->string('code')->unique();
            \$table->enum('type', ['percentage', 'fixed']);
            \$table->decimal('value', 10, 2);
            \$table->dateTime('expires_at')->nullable();
            \$table->integer('max_usages')->nullable();
            \$table->integer('usages_count')->default(0);
            \$table->timestamps();
            \$table->softDeletes();

            \$table->index('organizer_id');
        });
    ",
    'create_orders_table.php' => "
        Schema::create('orders', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('organizer_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('event_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            \$table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            \$table->string('order_number')->unique();
            \$table->decimal('total_amount', 10, 2);
            \$table->string('status')->default('pending'); // pending, paid, failed, refunded, cancelled
            \$table->timestamps();

            \$table->index('organizer_id');
            \$table->index('created_at');
        });
    ",
    'create_order_items_table.php' => "
        Schema::create('order_items', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('order_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('ticket_type_id')->constrained()->cascadeOnDelete();
            \$table->integer('quantity');
            \$table->decimal('unit_price', 10, 2);
            \$table->decimal('subtotal', 10, 2);
            \$table->timestamps();
        });
    ",
    'create_tickets_table.php' => "
        Schema::create('tickets', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('order_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('event_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('ticket_type_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // owner
            \$table->uuid('uuid')->unique();
            \$table->string('ticket_number')->unique();
            \$table->string('status')->default('valid'); // valid, used, cancelled, refunded
            \$table->timestamps();

            \$table->index('event_id');
            \$table->index('uuid');
        });
    ",
    'create_payments_table.php' => "
        Schema::create('payments', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('order_id')->constrained()->cascadeOnDelete();
            \$table->string('stripe_payment_id')->nullable();
            \$table->decimal('amount', 10, 2);
            \$table->string('currency')->default('USD');
            \$table->string('status')->default('pending'); // pending, succeeded, failed, refunded
            \$table->timestamps();

            \$table->index('status');
        });
    ",
    'create_coupon_usages_table.php' => "
        Schema::create('coupon_usages', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            \$table->foreignId('order_id')->constrained()->cascadeOnDelete();
            \$table->timestamps();
        });
    ",
    'create_refund_requests_table.php' => "
        Schema::create('refund_requests', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('order_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            \$table->text('reason');
            \$table->string('status')->default('pending'); // pending, approved, rejected
            \$table->timestamps();
        });
    ",
    'create_scans_table.php' => "
        Schema::create('scans', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('scanner_user_id')->nullable()->constrained('users')->nullOnDelete();
            \$table->timestamp('scanned_at');
            \$table->string('status'); // valid, invalid, already_used
            \$table->timestamps();
        });
    ",
    'create_activity_logs_table.php' => "
        Schema::create('activity_logs', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            \$table->foreignId('organizer_id')->nullable()->constrained()->nullOnDelete();
            \$table->string('action');
            \$table->text('description')->nullable();
            \$table->string('ip_address')->nullable();
            \$table->timestamps();
        });
    ",
];

$files = glob(__DIR__ . '/database/migrations/*.php');

foreach ($files as $file) {
    foreach ($migrations as $name => $content) {
        if (str_ends_with($file, $name)) {
            $code = file_get_contents($file);
            // Replace the empty up() method content
            $code = preg_replace(
                "/Schema::create\('.*?', function \(Blueprint \\\$table\) {\n.*?\n.*?\n.*?}\);/s",
                trim($content),
                $code
            );
            file_put_contents($file, $code);
            echo "Updated: " . basename($file) . "\n";
        }
    }
}
