<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('discipline_id');
            $table->unsignedBigInteger('group_id');
            $table->timestamps();

            $table->softDeletes();

            // IDX
            $table->index('discipline_id', 'task_discipline_idx');
            $table->index('group_id', 'task_group_idx');

            // FK
            $table->foreign('discipline_id', 'task_discipline_fk')->on('disciplines')->references('id');
            $table->foreign('group_id', 'task_group_fk')->on('groups')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
