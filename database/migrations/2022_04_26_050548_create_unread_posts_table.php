<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnreadPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unread_posts', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('group_news_id');

            // IDX
            $table->index('student_id', 'unread_post_student_idx');
            $table->index('group_news_id', 'unread_post_group_news_idx');

            // FK
            $table->foreign('student_id', 'unread_post_student_fk')
                ->on('students')->references('id')->onDelete('cascade');
            $table->foreign('group_news_id', 'unread_post_group_news_fk')
                ->on('group_news')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unread_posts');
    }
}
