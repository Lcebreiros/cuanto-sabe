<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('content')->nullable(); // markdown o html
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_sections');
    }
};
