<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('phone',25);
        });
    }

    public function down(): void
    {
        Schema::table('employee', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
