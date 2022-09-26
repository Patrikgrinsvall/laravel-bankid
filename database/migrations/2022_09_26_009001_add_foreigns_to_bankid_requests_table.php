<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bankid_requests', function (Blueprint $table) {
            $table
                ->foreign('bankid_integration_id')
                ->references('id')
                ->on('bankid_integrations')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bankid_requests', function (Blueprint $table) {
            $table->dropForeign(['bankid_integration_id']);
        });
    }
};
