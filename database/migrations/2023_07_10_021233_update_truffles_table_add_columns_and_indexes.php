<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('truffles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('price')->nullable();
            $table->string('source_type')->after('user_id')->nullable();
            $table->timestamp('updated_at')->nullable()->after('created_at');
            $table->timestamp('exported_at')->nullable();
            $table->string('sku')->index()->change();
            $table->index('expires_at');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('truffles', function (Blueprint $table) {
            $table->dropForeign('truffles_user_id_foreign');
            $table->dropColumn(['user_id', 'source_type']);
        });
    }
};
