<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('post');
            $table->text('activity')->nullable();
            $table->unsignedSmallInteger('work_experience')->nullable();
            $table->text('address')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->unique();
            $table->timestamps();

            $table->softDeletes();

            // IDX
            $table->index('user_id', 'teacher_user_idx');

            // FK
            $table->foreign('user_id', 'teacher_user_fk')->on('users')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
