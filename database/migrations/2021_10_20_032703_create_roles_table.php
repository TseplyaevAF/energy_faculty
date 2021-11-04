<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_default')->nullable()->default('user');
            $table->unsignedBigInteger('teacher_id')->nullable()->unique();
            $table->unsignedBigInteger('student_id')->nullable()->unique();
            $table->timestamps();

            $table->softDeletes();

            // IDX
            $table->index('teacher_id', 'role_teacher_idx');
            $table->index('student_id', 'role_student_idx');

            // FK
            $table->foreign('teacher_id', 'role_teacher_fk')->on('teachers')->references('id')->onDelete('cascade');
            $table->foreign('student_id', 'role_student_fk')->on('students')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
