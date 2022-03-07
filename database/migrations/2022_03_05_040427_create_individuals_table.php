<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individuals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('eval')->nullable();
            $table->unsignedBigInteger('statement_id');
            $table->text('teacher_signature')->nullable();
            $table->date('exam_finish_date')->nullable();

            $table->timestamps();

            // IDX
            $table->index('student_id', 'individual_student_idx');
            $table->index('statement_id', 'individual_statement_idx');

            // FK
            $table->foreign('student_id', 'individual_student_fk')->on('students')->references('id');
            $table->foreign('statement_id', 'individual_statement_fk')->on('statements')->references('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('individuals');
    }
}
