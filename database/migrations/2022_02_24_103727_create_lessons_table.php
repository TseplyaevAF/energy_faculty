<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('discipline_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('teacher_id');
            $table->integer('semester');

            // IDX
            $table->index('discipline_id', 'lesson_discipline_idx');
            $table->index('group_id', 'lesson_group_idx');
            $table->index('teacher_id', 'lesson_teacher_idx');

            // FK
            $table->foreign('discipline_id', 'lesson_discipline_fk')->on('disciplines')->references('id');
            $table->foreign('group_id', 'lesson_group_fk')->on('groups')->references('id');
            $table->foreign('teacher_id', 'lesson_teacher_fk')->on('teachers')->references('id');

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
        Schema::dropIfExists('lessons');
    }
}
