<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW. 
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\PlayerSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
{
    public function index()
    {
        try {
            $players = Player::with('skills')->get();
            return response()->json($players, 200);
        } catch (\Exception $e) {
            return response("Failed", 500);
        }
    }

    public function show($id)
    {
        try {
            $player = Player::with('skills')->findOrFail($id);
            return response()->json($player, 200);
        } catch (\Exception $e) {
            return response("Failed", 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'position' => 'required|in:defender,midfielder,forward',
                'playerSkills' => 'required|array|min:1',
                'playerSkills.*.skill' => 'required|string|in:defense,attack,speed,strength,stamina',
                'playerSkills.*.value' => 'required|integer|min:0|max:100',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }
    
            $validated = $validator->validated();
    
            $player = Player::create([
                'name' => $validated['name'],
                'position' => $validated['position'],
            ]);
    
            foreach ($validated['playerSkills'] as $playerSkill) {
                $player->skills()->create([
                    'skill' => $playerSkill['skill'],
                    'value' => $playerSkill['value']
                ]);
            }
    
            return response()->json($player->load('skills'), 201);
        } catch (\Exception $e) {
            return response("Failed", 500);
        }
    }

    public function update(Request $request, $playerId)
    {
        try {
            $player = Player::findOrFail($playerId);
    
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'position' => 'required|in:defender,midfielder,forward',
                'playerSkills' => 'required|array|min:1',
                'playerSkills.*.skill' => 'required|string|in:defense,attack,speed,strength,stamina',
                'playerSkills.*.value' => 'required|integer|min:0|max:100',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }
    
            $validated = $validator->validated();
    
            $player->update([
                'name' => $validated['name'],
                'position' => $validated['position'],
            ]);
    
            $player->skills()->delete();
            foreach ($validated['playerSkills'] as $playerSkill) {
                $player->skills()->create([
                    'skill' => $playerSkill['skill'],
                    'value' => $playerSkill['value']
                ]);
            }
    
            return response()->json($player->load('skills'), 200);
        } catch (\Exception $e) {
            return response("Failed", 500);
        }
    }

    public function destroy($id)
    {
        try {
            $player = Player::findOrFail($id);
            $player->delete();
        
            return response()->json(['message' => 'Player deleted successfully'], 200);
        } catch (\Exception $e) {
            return response("Failed", 500);
        }
    }
}
