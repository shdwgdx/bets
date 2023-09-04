<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('odds', function (Blueprint $table) {
            DB::unprepared(
                '
            CREATE TRIGGER update_games_updated_at AFTER UPDATE ON odds
            FOR EACH ROW
            BEGIN
                UPDATE games SET updated_at = NOW() WHERE id = NEW.game_id;
            END'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('odds', function (Blueprint $table) {
            DB::unprepared('DROP TRIGGER IF EXISTS update_games_updated_at');
        });
    }
};
