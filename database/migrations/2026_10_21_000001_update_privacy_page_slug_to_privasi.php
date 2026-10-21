<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('pages')) {
            $privacyExists = DB::table('pages')->where('slug', 'privacy')->exists();
            $privasiExists = DB::table('pages')->where('slug', 'privasi')->exists();

            if ($privacyExists && ! $privasiExists) {
                DB::table('pages')->where('slug', 'privacy')->update(['slug' => 'privasi']);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pages')) {
            $privacyExists = DB::table('pages')->where('slug', 'privacy')->exists();
            $privasiExists = DB::table('pages')->where('slug', 'privasi')->exists();

            if ($privasiExists && ! $privacyExists) {
                DB::table('pages')->where('slug', 'privasi')->update(['slug' => 'privacy']);
            }
        }
    }
};
