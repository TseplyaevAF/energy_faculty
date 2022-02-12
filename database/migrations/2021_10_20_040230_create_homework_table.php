<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeworkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homework', function (Blueprint $table) {
            $table->id();
            $table->string('homework');
            $table->text('grade')->nullable();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('student_id');
            $table->timestamps();

            $table->softDeletes();

            // IDX
            $table->index('task_id', 'homework_task_idx');
            $table->index('student_id', 'homework_student_idx');

            // FK
            $table->foreign('task_id', 'homework_task_fk')->on('tasks')->references('id');
            $table->foreign('student_id', 'homework_student_fk')->on('students')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homework');
    }
}
