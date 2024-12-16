<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
{
    Schema::table('posts', function (Blueprint $table) {
        $table->string('image')->nullable(); // Kolom image, dapat berupa path
        $table->string('title');            // Kolom title
        $table->text('content');            // Kolom content
    });
}

public function down()
{
    Schema::table('posts', function (Blueprint $table) {
        $table->dropColumn(['image', 'title', 'content']);
    });
}
};
