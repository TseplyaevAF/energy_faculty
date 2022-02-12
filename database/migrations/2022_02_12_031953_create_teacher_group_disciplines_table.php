<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherGroupDisciplinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_group_disciplines', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('group_discipline_id');


            $table->index('teacher_id', 'teacher_group_discipline_teacher_idx');
            $table->index('group_discipline_id', 'teacher_group_discipline_group_discipline_idx');

            $table->foreign('teacher_id', 'teacher_group_discipline_teacher_fk')->on('teachers')->references('id');
            $table->foreign('group_discipline_id', 'teacher_group_discipline_group_discipline_fk')->on('group_disciplines')->references('id');


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
        Schema::dropIfExists('teacher_group_disciplines');
    }
}
