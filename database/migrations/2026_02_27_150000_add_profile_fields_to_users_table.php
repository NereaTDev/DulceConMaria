<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('city', 100)->nullable()->after('phone');
            $table->string('country', 100)->nullable()->after('city');
            $table->string('instagram', 100)->nullable()->after('country');
            $table->text('notes')->nullable()->after('role'); // notas internas para admin
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone','city','country','instagram','notes']);
        });
    }
};
