<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignToiletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_toilets', function (Blueprint $table) {
            $table->increments('id');
            $table->date('assign_date');
            $table->integer('vehicle_id');
            $table->integer('cleaning_type_id');
            $table->string('zone');
            $table->string('ward');
            $table->integer('toilet_id');
            $table->string('image_path')->nullable();
            $table->integer('completed_by')->nullable();
            $table->boolean('is_reported_not_clean')->default(false);
            $table->timestamps();
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('user_checkers');
    }
}
