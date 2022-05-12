<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedBigInteger('student_id');
            $table->smallInteger('mark')->nullable();
            $table->smallInteger('number_of_passes')->default(0);
            $table->smallInteger('month');

            // IDX
            $table->index('lesson_id', 'student_progress_lesson_idx');
            $table->index('student_id', 'student_progress_student_idx');

            // FK
            $table->foreign('lesson_id', 'student_progress_lesson_fk')->on('lessons')->references('id');
            $table->foreign('student_id', 'student_progress_student_fk')->on('students')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_progress');
    }
}
