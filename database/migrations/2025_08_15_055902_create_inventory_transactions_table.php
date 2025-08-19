<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_item_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('transaction_type', ['in', 'out']);
            $table->unsignedInteger('quantity')->default(1);
            $table->date('transaction_date');
            $table->text('notes')->nullable();
            $table->unique('purchase_order_item_id');
            $table->unique('order_item_id');
            $table->timestamps();
        });

        DB::statement("
            ALTER TABLE inventory_transactions
            ADD CONSTRAINT chk_transaction_valid_combination
            CHECK (
                (transaction_type = 'in' AND purchase_order_item_id IS NOT NULL AND order_item_id IS NULL) OR
                (transaction_type = 'out' AND order_item_id IS NOT NULL AND purchase_order_item_id IS NULL)
            )
        ");

        DB::unprepared("
            CREATE TRIGGER after_insert_order_item
            AFTER INSERT ON order_items
            FOR EACH ROW
            BEGIN
                INSERT INTO inventory_transactions (
                    order_item_id,
                    transaction_type,
                    quantity,
                    transaction_date,
                    created_at,
                    updated_at
                )
                VALUES (
                    NEW.id,
                    'out',
                    NEW.quantity,
                    CURDATE(),
                    NOW(),
                    NOW()
                );
            END
        ");

        DB::unprepared("
            CREATE TRIGGER after_insert_purchase_order_item
            AFTER INSERT ON purchase_order_items
            FOR EACH ROW
            BEGIN
                INSERT INTO inventory_transactions (
                    purchase_order_item_id,
                    transaction_type,
                    quantity,
                    transaction_date,
                    created_at,
                    updated_at
                )
                VALUES (
                    NEW.id,
                    'in',
                    NEW.quantity,
                    CURDATE(),
                    NOW(),
                    NOW()
                );
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_order_item');
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_purchase_order_item');
        Schema::dropIfExists('inventory_transactions');
    }
};
