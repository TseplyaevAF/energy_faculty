<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeadmenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headmen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->unique();
            $table->unsignedBigInteger('student_id')->unique();
            $table->timestamps();
            
            // IDX
            $table->index('group_id', 'headman_group_idx');
            $table->index('student_id', 'headman_student_idx');

            // FK
            $table->foreign('group_id', 'headman_group_fk')->on('groups')->references('id');
            $table->foreign('student_id', 'headman_student_fk')->on('students')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('headmen');
    }
}
