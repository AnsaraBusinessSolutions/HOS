<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('customer_bp',50)->nullable(false);
            $table->bigInteger('hospital_code')->nullable(false);
            $table->bigInteger('department_code')->nullable(false);
            $table->string('department_description')->nullable(false);
            $table->string('email')->nullable(false);
            $table->smallInteger('phone')->nullable(false);
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
        Schema::dropIfExists('departments');
    }
}
