<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chair_id');
            $table->unsignedBigInteger('user_id')->nullable()->unique();
            $table->timestamps();

            $table->softDeletes();   

            // IDX
            $table->index('chair_id', 'employee_chair_idx');
            $table->index('user_id', 'employee_user_idx');

            // FK
            $table->foreign('chair_id', 'employee_chair_fk')->on('chairs')->references('id');
            $table->foreign('user_id', 'employee_user_fk')->on('users')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
