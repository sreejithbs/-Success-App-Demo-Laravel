<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repository_id')->constrained('repositories')->cascadeOnDelete();
            $table->bigInteger('uid')->unique();
            $table->text('title');
            $table->longText('description');
            $table->enum('status', ['open', 'closed']);
            $table->boolean('is_synced')->default(0);
            $table->longText('reference_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issues');
    }
};
