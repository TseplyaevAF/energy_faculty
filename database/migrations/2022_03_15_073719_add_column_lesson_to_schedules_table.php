<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLessonToSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['discipline_id', 'teacher_id', 'group_id']);
            $table->unsignedBigInteger('lesson_id');

            $table->index('lesson_id', 'schedule_lesson_idx');

            $table->foreign('lesson_id', 'schedule_lesson_fk')->on('lessons')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('lesson_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('discipline_id');
            $table->unsignedBigInteger('group_id');

            // IDX
            $table->index('teacher_id', 'schedule_teacher_idx');
            $table->index('discipline_id', 'schedule_discipline_idx');
            $table->index('group_id', 'schedule_group_idx');

            // FK
            $table->foreign('teacher_id', 'schedule_teacher_fk')->on('teachers')->references('id');
            $table->foreign('discipline_id', 'schedule_discipline_fk')->on('disciplines')->references('id');
            $table->foreign('group_id', 'schedule_group_fk')->on('groups')->references('id');
        });
    }
}
