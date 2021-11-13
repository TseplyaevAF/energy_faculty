<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->text('images')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('chair_id')->nullable();
            $table->timestamps();

            $table->softDeletes();

            // IDX
            $table->index('category_id', 'news_category_idx');
            $table->index('chair_id', 'news_chair_idx');

            // FK
            $table->foreign('category_id', 'news_category_fk')->on('categories')->references('id');
            $table->foreign('chair_id', 'news_chair_fk')->on('chairs')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
