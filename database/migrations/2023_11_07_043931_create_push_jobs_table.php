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
        Schema::create('push_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('noti_title');
            $table->string('noti_body')->nullable();
            $table->string('device_token');
            $table->string('link')->nullable();
            $table->boolean('send_flag')->default(0);
            $table->string('error')->nullable()->default(null);
            $table->dateTime('created_at')->default(now());
            $table->dateTime('send_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('push_jobs');
    }
};
