<?php

use App\Models\Shop\Point;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Point::class)->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->date('birthdate');
            $table->string('passport_series');
            $table->string('address');
            $table->date('employment_date');
            $table->date('dismissal_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
