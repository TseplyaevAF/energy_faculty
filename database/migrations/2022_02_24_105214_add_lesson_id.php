<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLessonId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('discipline_id');
            $table->dropColumn('teacher_id');
            $table->dropColumn('group_id');
            $table->unsignedBigInteger('lesson_id');

            // IDX
            $table->index('lesson_id', 'task_lesson_idx');

            // FK
            $table->foreign('lesson_id', 'task_lesson_fk')->on('lessons')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('discipline_id');
            $table->unsignedBigInteger('group_id');
            $table->dropColumn('lesson_id');

            // IDX
            $table->index('teacher_id', 'task_teacher_idx');
            $table->index('discipline_id', 'task_discipline_idx');
            $table->index('group_id', 'task_group_idx');

            // FK
            $table->foreign('teacher_id', 'task_teacher_fk')->on('teachers')->references('id');
            $table->foreign('discipline_id', 'task_discipline_fk')->on('disciplines')->references('id');
            $table->foreign('group_id', 'task_group_fk')->on('groups')->references('id');
        });
    }
}
