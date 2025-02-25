<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->uuid('email_verified_code')->nullable()->after('email');
            $table->timestamp('email_verified_at')->nullable()->after('email_verified_code');
        });
    }
};
