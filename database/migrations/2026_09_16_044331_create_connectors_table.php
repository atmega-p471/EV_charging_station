<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('connectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('charger_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->integer('power_kw');
            $table->decimal('price_per_kwh', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('connectors');
    }
};