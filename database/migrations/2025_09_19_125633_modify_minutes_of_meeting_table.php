<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMinutesOfMeetingTable extends Migration
{

       public function up()
{
    Schema::table('minute_of_meetings', function (Blueprint $table) {
        // Drop foreign key first
        $table->dropForeign(['assigned_to']);

        // Now drop the columns
        $table->dropColumn([
            'assigned_to',
            'description',
            'status',
            'issues',
            'deadline',
        ]);
    });
}


    public function down()
    {
        Schema::table('minute_of_meetings', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->text('issues')->nullable();
            $table->timestamp('deadline')->nullable();
        });
    }
}
