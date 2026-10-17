<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->date('viewed_date')->index();
            $table->unsignedInteger('views')->default(1);
            $table->timestamps();

            $table->unique(['post_id', 'viewed_date']);
        });

        // Inisialisasi awal log tayangan dari artikel yang sudah ada
        $posts = DB::table('posts')->where('views_count', '>', 0)->get();
        foreach ($posts as $post) {
            $date = $post->published_at
                ? Carbon::parse($post->published_at)->toDateString()
                : Carbon::parse($post->created_at)->toDateString();

            DB::table('post_views')->insertOrIgnore([
                'post_id' => $post->id,
                'viewed_date' => $date,
                'views' => $post->views_count,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_views');
    }
};
