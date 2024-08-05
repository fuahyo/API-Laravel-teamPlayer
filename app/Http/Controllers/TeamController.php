<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function process(Request $request)
    {
        try {
            $requirements = $request->all();
            $team = [];

            foreach ($requirements as $requirement) {
                $position = $requirement['position'];
                $mainSkill = $requirement['mainSkill'];
                $numberOfPlayers = $requirement['numberOfPlayers'];

                $players = Player::where('position', $position)
                    ->with(['skills' => function ($query) use ($mainSkill) {
                        $query->orderBy('value', 'desc')
                            ->where('skill', $mainSkill);
                    }])
                    ->get()
                    ->sortByDesc(function ($player) use ($mainSkill) {
                        return $player->skills->first()->value ?? 0;
                    })
                    ->take($numberOfPlayers);

                if ($players->count() < $numberOfPlayers) {
                    return response()->json(['message' => "Insufficient number of players for position: {$position}"], 400);
                }

                foreach ($players as $player) {
                    $team[] = $player;
                }
            }

            return response()->json($team, 200);
        } catch (\Exception $e) {
            return response("Failed", 500);
        }
    }
}
