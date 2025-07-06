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
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('uf', 2);
            $table->integer('ibge_code');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('state');
            $table->string('uf', 2);
            $table->float('lat');
            $table->float('lng');
            $table->integer('uf_code');
            $table->integer('ibge_code');
        });

        Schema::table('travel_orders', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
        Schema::dropIfExists('cities');
        Schema::table('travel_orders', function (Blueprint $table) {
            $table->dropColumn('city_id');
        });
    }
};
