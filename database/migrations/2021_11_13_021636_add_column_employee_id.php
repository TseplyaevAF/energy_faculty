<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnEmployeeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->nullable()->unique();

            // IDX
            $table->index('employee_id', 'role_employee_idx');

            // FK
            $table->foreign('employee_id', 'role_employee_fk')->on('employees')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
    }
}
