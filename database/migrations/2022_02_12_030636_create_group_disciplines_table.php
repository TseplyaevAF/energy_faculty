<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupDisciplinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_disciplines', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('group_id');
            $table->unsignedInteger('discipline_id');
            $table->string('semester');

            $table->index('group_id', 'group_discipline_group_idx');
            $table->index('discipline_id', 'group_discipline_discipline_idx');

            $table->foreign('group_id', 'group_discipline_group_fk')->on('groups')->references('id');
            $table->foreign('discipline_id', 'group_discipline_discipline_fk')->on('disciplines')->references('id');

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
        Schema::dropIfExists('group_disciplines');
    }
}
