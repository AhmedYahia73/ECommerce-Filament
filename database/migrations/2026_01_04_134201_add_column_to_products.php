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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained("categories")->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('unit_id')->nullable() ->constrained("units")->cascadeOnUpdate()->cascadeOnDelete();

            $table->string("short_description")->nullable();
            $table->string("image")->nullable(); 
            $table->float("offer_price")->nullable();
            $table->date("start_date")->nullable();
            $table->date("end_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
