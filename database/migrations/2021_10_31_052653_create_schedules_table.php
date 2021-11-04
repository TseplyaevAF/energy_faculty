<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('day_id');
            $table->unsignedBigInteger('class_time_id');
            $table->unsignedBigInteger('week_type_id')->nullable();
            $table->unsignedBigInteger('discipline_id');
            $table->unsignedBigInteger('class_type_id')->nullable();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('classroom_id');
            
            // IDX
            $table->index('day_id', 'schedule_day_idx');
            $table->index('class_time_id', 'schedule_class_time_idx');
            $table->index('week_type_id', 'schedule_week_type_idx');
            $table->index('discipline_id', 'schedule_discipline_idx');
            $table->index('class_type_id', 'schedule_class_type_idx');
            $table->index('teacher_id', 'schedule_teacher_idx');
            $table->index('group_id', 'schedule_group_idx');
            $table->index('classroom_id', 'schedule_classroom_idx');

            // FK
            $table->foreign('day_id', 'schedule_day_fk')->on('days')->references('id');
            $table->foreign('class_time_id', 'schedule_class_time_fk')->on('class_times')->references('id');
            $table->foreign('week_type_id', 'schedule_week_type_fk')->on('week_types')->references('id');
            $table->foreign('discipline_id', 'schedule_discipline_fk')->on('disciplines')->references('id');
            $table->foreign('class_type_id', 'schedule_class_type_fk')->on('class_types')->references('id');
            $table->foreign('teacher_id', 'schedule_teacher_fk')->on('teachers')->references('id');
            $table->foreign('group_id', 'schedule_group_fk')->on('groups')->references('id');
            $table->foreign('classroom_id', 'schedule_classroom_fk')->on('classrooms')->references('id');

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
        Schema::dropIfExists('schedules');
    }
}
