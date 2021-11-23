<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id_number')->unique();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id')->nullable()->unique();
            $table->timestamps();

            $table->softDeletes();

            // IDX
            $table->index('group_id', 'student_group_idx');
            $table->index('user_id', 'student_user_idx');

            // FK
            $table->foreign('group_id', 'student_group_fk')->on('groups')->references('id');
            $table->foreign('user_id', 'student_user_fk')->on('users')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
