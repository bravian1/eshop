# E-commerce Database Schema

This document outlines the database schema for the e-commerce application.

## Tables

### Categories Table
```php
// 2025_09_26_190000_create_categories_table.php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('parent_id')->nullable()->constrained('categories')->cascadeOnDelete();
    $table->unsignedInteger('lft')->default(0);
    $table->unsignedInteger('rgt')->default(0);
    $table->unsignedInteger('depth')->default(0);
    $table->string('name');
    $table->string('slug')->unique();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    $table->index(['lft', 'rgt', 'parent_id']);
});
```

### Products Table
```php
// 2025_09_26_190139_create_products_table.php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description_md')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### Variant Axes Table
```php
// 2025_09_26_190139_create_variant_axes_table.php
Schema::create('variant_axes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->enum('display_type', ['swatch', 'pill', 'dropdown']);
    $table->unsignedSmallInteger('sort_order')->default(0);
    $table->timestamps();
});
```

### Variant Axis Values Table
```php
// 2025_09_26_190140_create_variant_axis_values_table.php
Schema::create('variant_axis_values', function (Blueprint $table) {
    $table->id();
    $table->foreignId('variant_axis_id')->constrained()->cascadeOnDelete();
    $table->string('value');
    $table->json('meta');
    $table->unsignedSmallInteger('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### Variants Table
```php
// 2025_09_26_190140_create_variants_table.php
Schema::create('variants', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->string('sku')->unique();
    $table->unsignedInteger('price_cents');
    $table->unsignedInteger('cost_cents')->nullable();
    $table->unsignedSmallInteger('weight_g')->nullable();
    $table->unsignedSmallInteger('width_mm')->nullable();
    $table->unsignedSmallInteger('height_mm')->nullable();
    $table->unsignedSmallInteger('depth_mm')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### Variant Composition Table (pivot)
```php
// 2025_09_26_190141_create_variant_composition_table.php
Schema::create('variant_composition', function (Blueprint $table) {
    $table->foreignId('variant_id')->constrained()->cascadeOnDelete();
    $table->foreignId('variant_axis_value_id')->constrained()->cascadeOnDelete();
    $table->primary(['variant_id', 'variant_axis_value_id']);
});
```

### Locations Table
```php
// 2025_09_26_190141_create_locations_table.php
Schema::create('locations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('seller_user_id')->constrained('users')->cascadeOnDelete();
    $table->string('name');
    $table->enum('type', ['shop', 'locker', 'warehouse', 'bus-stop', 'pop-up-point']);
    $table->foreignId('address_id')->nullable()->constrained();
    $table->decimal('geo_lat', 9, 6)->nullable();
    $table->decimal('geo_lng', 9, 6)->nullable();
    $table->text('instructions')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### Inventory Table
```php
// 2025_09_26_190142_create_inventory_table.php
Schema::create('inventory', function (Blueprint $table) {
    $table->foreignId('variant_id')->constrained()->cascadeOnDelete();
    $table->foreignId('location_id')->constrained()->cascadeOnDelete();
    $table->integer('qty_on_hand')->default(0);
    $table->integer('qty_reserved')->default(0);
    $table->timestamps();
    $table->primary(['variant_id', 'location_id']);
});
```

### Stock Reservations Table
```php
// 2025_09_26_190142_create_stock_reservations_table.php
Schema::create('stock_reservations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('variant_id')->constrained()->cascadeOnDelete();
    $table->uuid('cart_session_token');
    $table->integer('qty');
    $table->dateTime('expires_at')->index();
    $table->timestamps();
});
```

### Addresses Table
```php
// 2025_09_26_190143_create_addresses_table.php
Schema::create('addresses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->string('line1');
    $table->string('line2')->nullable();
    $table->string('city');
    $table->string('state')->nullable();
    $table->string('postal')->nullable();
    $table->string('country');
    $table->boolean('is_default_shipping')->default(false);
    $table->boolean('is_default_billing')->default(false);
    $table->timestamps();
});
```

### Carts Table
```php
// 2025_09_26_190143_create_carts_table.php
Schema::create('carts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->uuid('session_token')->unique();
    $table->string('currency', 3);
    $table->timestamps();
});
```

### Cart Items Table
```php
// 2025_09_26_190144_create_cart_items_table.php
Schema::create('cart_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
    $table->foreignId('product_id')->constrained();
    $table->foreignId('variant_id')->nullable()->constrained();
    $table->integer('qty')->unsigned();
    $table->unsignedInteger('price_cents_at_add');
    $table->json('chosen_axis_values')->nullable();
    $table->timestamps();
    $table->unique(['cart_id', 'product_id', 'chosen_axis_values']);
});
```

### Wishlists Table (pivot)
```php
// 2025_09_26_190144_create_wishlists_table.php
Schema::create('wishlists', function (Blueprint $table) {
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->timestamp('added_at')->useCurrent();
    $table->primary(['user_id', 'product_id']);
});
```

### Delivery Options Table
```php
// 2025_09_26_190146_create_delivery_options_table.php
Schema::create('delivery_options', function (Blueprint $table) {
    $table->id();
    $table->foreignId('seller_user_id')->constrained('users')->cascadeOnDelete();
    $table->string('name');
    $table->enum('type', ['pickup', 'courier', 'bus', 'drop-point']);
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### Delivery Zones Table
```php
// 2025_09_26_190147_create_delivery_zones_table.php
Schema::create('delivery_zones', function (Blueprint $table) {
    $table->id();
    $table->foreignId('delivery_option_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->enum('type', ['radius', 'polygon', 'postal-list', 'station-list']);
    $table->json('geo_json');
    $table->unsignedInteger('base_price_cents');
    $table->unsignedSmallInteger('price_per_kg_cents')->nullable();
    $table->unsignedSmallInteger('price_per_item_cents')->nullable();
    $table->unsignedInteger('free_above_cents')->nullable();
    $table->unsignedSmallInteger('eta_hours_min');
    $table->unsignedSmallInteger('eta_hours_max');
    $table->timestamps();
});
```

### Orders Table
```php
// 2025_09_26_190147_create_orders_table.php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->string('guest_email')->nullable();
    $table->string('guest_phone')->nullable();
    $table->foreignId('shipping_address_id')->nullable()->constrained('addresses');
    $table->foreignId('billing_address_id')->nullable()->constrained('addresses');
    $table->foreignId('delivery_option_id')->nullable()->constrained();
    $table->foreignId('delivery_zone_id')->nullable()->constrained();
    $table->foreignId('location_id')->nullable()->constrained();
    $table->unsignedInteger('delivery_price_cents');
    $table->unsignedSmallInteger('delivery_et_hours_min')->nullable();
    $table->unsignedSmallInteger('delivery_et_hours_max')->nullable();
    $table->string('status')->default('pending');
    $table->string('payment_intent_id')->nullable();
    $table->string('refund_status')->nullable();
    $table->unsignedInteger('refund_amount_cents')->nullable();
    $table->timestamps();
});
```

### Order Items Table
```php
// 2025_09_26_190148_create_order_items_table.php
Schema::create('order_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->cascadeOnDelete();
    $table->foreignId('product_id')->constrained();
    $table->foreignId('variant_id')->constrained();
    $table->integer('qty')->unsigned();
    $table->unsignedInteger('unit_price_cents');
    $table->unsignedInteger('unit_cost_cents')->nullable();
    $table->timestamps();
});
```

### Return Requests Table
```php
// 2025_09_26_190148_create_return_requests_table.php
Schema::create('return_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->text('reason');
    $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
    $table->text('admin_notes')->nullable();
    $table->dateTime('approved_at')->nullable();
    $table->dateTime('completed_at')->nullable();
    $table->timestamps();
});
```

### Return Request Items Table
```php
// 2025_09_26_190149_create_return_request_items_table.php
Schema::create('return_request_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('return_request_id')->constrained()->cascadeOnDelete();
    $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
    $table->integer('qty')->unsigned();
    $table->text('reason')->nullable();
    $table->enum('condition', ['new', 'used', 'damaged'])->default('used');
    $table->timestamps();
});
```

### Product Images Table
```php
// 2025_09_26_190150_create_product_images_table.php
Schema::create('product_images', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->foreignId('variant_id')->nullable()->constrained()->cascadeOnDelete();
    $table->string('path');
    $table->string('alt_text')->nullable();
    $table->unsignedSmallInteger('sort_order')->default(0);
    $table->boolean('is_primary')->default(false);
    $table->timestamps();
});
```

### Reviews Table
```php
// 2025_09_26_190151_create_reviews_table.php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('order_item_id')->nullable()->constrained()->cascadeOnDelete();
    $table->unsignedTinyInteger('rating');
    $table->text('title')->nullable();
    $table->text('comment')->nullable();
    $table->boolean('is_verified_purchase')->default(false);
    $table->boolean('is_approved')->default(false);
    $table->timestamps();
    
    $table->unique(['product_id', 'user_id', 'order_item_id']);
});
```

### Coupons Table
```php
// 2025_09_26_190152_create_coupons_table.php
Schema::create('coupons', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->enum('type', ['percentage', 'fixed_amount', 'free_shipping']);
    $table->unsignedInteger('value_cents')->nullable();
    $table->unsignedTinyInteger('percentage')->nullable();
    $table->unsignedInteger('minimum_amount_cents')->nullable();
    $table->unsignedInteger('maximum_discount_cents')->nullable();
    $table->unsignedInteger('usage_limit')->nullable();
    $table->unsignedInteger('used_count')->default(0);
    $table->dateTime('starts_at')->nullable();
    $table->dateTime('expires_at')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```