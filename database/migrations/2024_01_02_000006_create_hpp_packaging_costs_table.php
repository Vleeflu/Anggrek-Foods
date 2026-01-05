<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hpp_packaging_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hpp_calculation_id')->constrained()->onDelete('cascade');
            $table->string('description'); // Cup Gelas, Plastik, Sendok, dll
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hpp_packaging_costs');
    }
};
