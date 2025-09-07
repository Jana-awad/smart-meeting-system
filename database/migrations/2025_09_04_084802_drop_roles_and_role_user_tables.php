<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('role_user')) {
            Schema::drop('role_user');
        }
        if (Schema::hasTable('roles')) {
            Schema::drop('roles');
        }
    }

    public function down(): void
    {
        // Leave empty (we won't restore the old schema)
    }
};
