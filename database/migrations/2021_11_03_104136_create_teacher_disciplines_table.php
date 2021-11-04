<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherDisciplinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_disciplines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('discipline_id');
            $table->timestamps();

            // IDX
            $table->index('teacher_id', 'teacher_discipline_teacher_idx');
            $table->index('discipline_id', 'teacher_discipline_discipline_idx');

            // FK
            $table->foreign('teacher_id', 'teacher_discipline_teacher_fk')->on('teachers')->references('id');
            $table->foreign('discipline_id', 'teacher_discipline_discipline_fk')->on('disciplines')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_disciplines');
    }
}
