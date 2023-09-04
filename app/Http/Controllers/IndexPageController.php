<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\League;
use App\Models\Odd;
use App\Models\Sport;

class IndexPageController extends Controller
{
    public function index()
    {
        $sports = Sport::all();
        $leagues = League::has('games', '>', 0)->get();
        $games = Game::all();
        $odds = Odd::all();

        return view('index', [
            'sports' => $sports,
            'leagues' => $leagues,
            'games' => $games,
            'odds' => $odds,
        ]);
    }


    public function games(League $league)
    {
        return view(
            'games',
            [
                'games' => $league->games()
                    ->has('odds', '>', 0)
                    ->withCount('odds')
                    ->orderBy('odds_count', 'desc')
                    ->orderBy('updated_at', 'desc')
                    ->get(),
                'league' => $league,
            ]
        );
    }

    public function odds(League $league, Game $game)
    {

        return view(
            'odds',
            [
                'game' => $game,
                'odds' => $game->odds
            ]
        );
    }
}
