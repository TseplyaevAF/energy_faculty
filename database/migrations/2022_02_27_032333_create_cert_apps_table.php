<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cert_apps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->text('public_key');
            $table->text('data')->nullable();
            $table->timestamps();

            // IDX
            $table->index('teacher_id', 'cert_app_teacher_idx');

            // FK
            $table->foreign('teacher_id', 'cert_app_teacher_fk')->on('teachers')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cert_apps');
    }
}
