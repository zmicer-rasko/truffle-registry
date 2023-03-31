<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('truffles', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->integer('weight')->nullable();
            $table->double('price')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('expires_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('truffles');
    }
};
