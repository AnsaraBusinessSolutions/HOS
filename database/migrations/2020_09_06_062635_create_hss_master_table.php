<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHssMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hss_master', function (Blueprint $table) {
            $table->id();
            $table->string('customer_bp',50)->nullable(false);
            $table->bigInteger('hospital_code')->nullable(false);
            $table->string('name',100)->nullable(false);
            $table->string('address')->nullable(false);
            $table->string('city')->nullable(false);
            $table->string('state')->nullable(false);
            $table->string('pin_code')->nullable(false);
            $table->string('contact_person',100)->nullable(false);
            $table->smallInteger('phone')->nullable(false);
            $table->string('email')->nullable(false);
            $table->integer('client_id')->nullable(false);
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
        Schema::dropIfExists('hss_master');
    }
}
