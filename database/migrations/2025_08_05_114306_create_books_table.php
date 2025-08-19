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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->foreignId('author_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('publisher_id')->nullable()->constrained()->nullOnDelete();
            $table->string('isbn', 50)->nullable()->unique();
            $table->string('language', 50)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('pages')->nullable();
            $table->string('dimensions', 50)->nullable();
            $table->unsignedInteger('weight')->nullable();
            $table->year('publication_year')->nullable();
            $table->enum('cover_type', ['hardcover', 'paperback'])->nullable();
            $table->decimal('original_price', 15, 2)->default(0);
            $table->decimal('sale_price', 15, 2)->default(0);
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->enum('status', ['available', 'out_of_stock', 'pre_order', 'discontinued'])->default('available');
            $table->timestamps();
        });

        DB::unprepared("
            CREATE TRIGGER update_book_status
            BEFORE UPDATE ON books
            FOR EACH ROW
            BEGIN
                IF NEW.stock_quantity != OLD.stock_quantity THEN
                    IF NEW.stock_quantity > 0 THEN
                        SET NEW.status = 'available';
                    ELSEIF NEW.stock_quantity = 0 THEN
                        SET NEW.status = 'out_of_stock';
                    END IF;
                END IF;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_book_status');
        Schema::dropIfExists('books');
    }
};
