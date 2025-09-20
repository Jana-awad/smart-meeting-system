<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionItemsTable extends Migration
{
    public function up()
    {
        Schema::create('action_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('minutes_id');
            $table->text('description');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->timestamp('deadline')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('minutes_id')->references('id')->on('minute_of_meetings')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('action_items');
    }
}
