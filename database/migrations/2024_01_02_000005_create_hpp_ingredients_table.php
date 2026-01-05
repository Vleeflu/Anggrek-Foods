<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hpp_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hpp_calculation_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('quantity', 10, 2);
            $table->string('unit'); // Kilogram, Liter, Gram, Pcs
            $table->decimal('price_per_unit', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hpp_ingredients');
    }
};
