<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hpp_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // branch_id removed
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->decimal('total_ingredients_cost', 15, 2)->default(0);
            $table->decimal('total_packaging_cost', 15, 2)->default(0);
            $table->decimal('labor_cost', 15, 2)->default(0);
            $table->integer('portions')->default(1);

            $table->decimal('total_cost', 15, 2)->default(0);
            $table->decimal('hpp_per_portion', 15, 2)->default(0);

            $table->decimal('profit_margin_percent', 5, 2)->default(0);
            $table->decimal('profit_amount', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hpp_calculations');
    }
};
