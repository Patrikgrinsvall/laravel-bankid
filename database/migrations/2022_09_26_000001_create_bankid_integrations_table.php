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
        Schema::create('bankid_integrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->text('description');
            $table->boolean('active');
            $table->longText('pkcs');
            $table->string('password')->nullable();
            $table->enum('type', ['pfx', 'p12', 'pem']);
            $table->string('url_prefix');
            $table->string('success_url');
            $table->string('error_url')->nullable();
            $table->enum('environment', ['test', 'prod'])->default('test');
            $table->json('layout');
            $table->json('languages');
            $table->longText('extra_html');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bankid_integrations');
    }
};
