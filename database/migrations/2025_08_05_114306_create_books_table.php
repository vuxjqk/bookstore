<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->string('title', 150)->unique();
            $table->string('slug', 225)->unique();
            $table->foreignId('author_id')->nullable()->constrained('authors')->nullOnDelete();
            $table->foreignId('publisher_id')->nullable()->constrained('publishers')->nullOnDelete();
            $table->string('isbn', 50)->nullable()->unique();
            $table->string('language', 50)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('pages')->default(0);
            $table->string('dimensions', 50)->nullable();
            $table->unsignedInteger('weight')->nullable();
            $table->year('publication_year')->nullable();
            $table->enum('cover_type', ['hardcover', 'paperback'])->nullable();
            $table->decimal('original_price', 15, 2)->default(0.00);
            $table->decimal('sale_price', 15, 2)->default(0.00);
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->enum('status', ['available', 'out_of_stock', 'pre_order', 'discontinued'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
