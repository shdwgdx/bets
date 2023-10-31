<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Game;

class RemoveOldRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'records:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fourHoursAgo = Carbon::now()->subHours(2);
        Game::where('updated_at', '<', $fourHoursAgo)->delete();

        $this->info('Old records have been removed.');
    }
}
