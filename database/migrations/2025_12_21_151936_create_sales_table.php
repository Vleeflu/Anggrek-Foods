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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hpp_calculation_id')->constrained('hpp_calculations')->onDelete('cascade');
            // branch_id removed
            $table->date('sale_date');
            $table->integer('quantity_sold');
            $table->decimal('selling_price_used', 10, 2); // Price actually used for this sale
            $table->decimal('cost_per_unit', 10, 2); // HPP per portion from calculation
            $table->decimal('total_revenue', 10, 2); // quantity * selling_price
            $table->decimal('total_cost', 10, 2); // quantity * cost_per_unit
            $table->decimal('profit', 10, 2); // total_revenue - total_cost
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('sale_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
