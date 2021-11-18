<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsStatusTeacherId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('status')->nullable();
            $table->unsignedBigInteger('teacher_id');

            // IDX
            $table->index('teacher_id', 'task_teacher_idx');

            // FK
            $table->foreign('teacher_id', 'task_teacher_fk')->on('teachers')->references('id');
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
            $table->dropColumn('status');
            $table->dropColumn('teacher_id');
        });
    }
}
