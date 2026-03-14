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
        Schema::table('users', function (Blueprint $table) {

            $table ->string('role')->default('usuario');
            $table ->decimal('saldo')->default('usuario');
            $table ->string('otp_code', 6) ->nullable();
            $table ->timestamp('otp_expires_at')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            
        $table->dropColumn([
            'role',
            'saldo',
            'otp_code',
            'otp_expires_at'
           ]);
           
        });
    }
};
