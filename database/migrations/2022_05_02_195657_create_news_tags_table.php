<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('news_id');
            $table->unsignedBigInteger('tag_id');

            // IDX
            $table->index('news_id', 'news_tag_news_idx');
            $table->index('tag_id', 'news_tag_tag_idx');

            // FK
            $table->foreign('news_id', 'news_tag_news_fk')
                ->on('news')->references('id')->onDelete('cascade');
            $table->foreign('tag_id', 'news_tag_tag_fk')
                ->on('tags')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_tags');
    }
}
