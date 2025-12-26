<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id'); // same as int(10) unsigned auto increment primary key
            $table->string('key', 255);
            $table->string('display_name', 255);
            $table->text('value')->nullable();
            $table->text('details')->nullable();
            $table->string('type', 255);
            $table->integer('order')->default(1);
            $table->string('group', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
