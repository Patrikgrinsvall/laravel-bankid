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
        Schema::create('bankid_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('response');
            $table->string('orderRef');
            $table->unsignedBigInteger('bankid_integration_id');
            $table->string('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bankid_requests');
    }
};
