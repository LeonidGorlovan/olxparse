<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscriber_link', function (Blueprint $table) {
            $table->foreignId('subscribers_id')
                ->references('id')
                ->on('subscribers')
                ->cascadeOnDelete()
                ->restrictOnUpdate();

            $table->foreignId('link_id')
                ->references('id')
                ->on('links')
                ->cascadeOnDelete()
                ->restrictOnUpdate();

            $table->unique(['subscribers_id', 'link_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriber_link');
    }
};
