<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statements', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('form_exam');
            $table->unsignedBigInteger('lesson_id');
            $table->date('exam_date')->nullable();
            $table->date('finish_date')->nullable();

            // IDX
            $table->index('lesson_id', 'statement_lesson_idx');

            // FK
            $table->foreign('lesson_id', 'statement_lesson_fk')->on('lessons')->references('id');

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
        Schema::dropIfExists('statements');
    }
}
