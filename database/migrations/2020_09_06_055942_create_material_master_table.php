<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_master', function (Blueprint $table) {
            $table->id();
            $table->string('customer_bp',100)->nullable();
            $table->bigInteger('nupco_material_generic_code')->nullable();
            $table->bigInteger('nupco_trade_code')->nullable();
            $table->string('material_description',4000)->nullable();
            $table->bigInteger('customer_generic_code')->nullable();
            $table->bigInteger('customer_trade_code')->nullable();
            $table->string('customer_trade_description',4000)->nullable();
            $table->string('buom',25)->nullable();
            $table->string('manufacturer',255)->nullable();
            $table->string('country_of_origin',100)->nullable();
            $table->integer('client_id',11)->nullable();
            $table->timestamps()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_master');
    }
}
