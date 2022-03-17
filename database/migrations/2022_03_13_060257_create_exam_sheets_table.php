<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_sheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('individual_id');
            $table->date('before')->nullable();
            $table->text('dekan_signature')->nullable();

            // IDX
            $table->index('student_id', 'exam_sheet_student_idx');
            $table->index('individual_id', 'exam_sheet_individual_idx');

            // FK
            $table->foreign('student_id', 'exam_sheet_student_fk')->on('students')->references('id');
            $table->foreign('individual_id', 'exam_sheet_individual_fk')->on('individuals')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_sheets');
    }
}
