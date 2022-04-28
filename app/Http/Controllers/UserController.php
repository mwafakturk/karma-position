<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function getOverall($id, $numUsers = null)
    {
        $users            = User::with('image')->orderBy('karma_score', 'DESC')->get();
        $user             = $users->where('id', $id)->first();

        if (!$user) {
            return response(['Message' => 'Invalid ID.'], 400);
        }

        $max_karama_score = $users->max('karma_score');
        $min_karama_score = $users->min('karma_score');

        $data             = $users->where('id', $id);
        $user->position   = $data->keys()->first() + 1;
        $rank             = $user->position;

        if (!$numUsers || $numUsers > count($users)) {
            $numUsers = 5;
        }

        if ($numUsers == 1) {
            return response($user, 200);
        } else if ($user->karma_score == $max_karama_score) {

            $previous = $users->where('karma_score', '<', $user->karma_score)->sortByDesc('karma_score')->take($numUsers - 1);

            $subUsers = collect();
            $subUsers->add($user);
            for ($i = 2; $i <= count($previous) + 1; $i++) {
                $subUsers->add($previous[$i - 1]);
                $subUsers->last()->position = $i;
            }
            return response($subUsers, 200);
        } else if ($user->karma_score == $min_karama_score) {
            $next = $users->reverse()->where('karma_score', '>', $user->karma_score)->take($numUsers - 1);
            $next->add($user);

            $subUsers = collect();
            for ($i = 0; $i < count($next); $i++) {
                $subUsers->add($next[count($users) - $numUsers + $i]);
                $subUsers->last()->position = count($users) - $numUsers + $i + 1;
            }

            return response($subUsers, 200);
        } else {
            $befor = round(($numUsers - 1) / 2);
            $after = ($numUsers - 1) - $befor;

            if ($rank + $after > count($users)) {
                $_counter_condition = count($users) - $rank;
                $counter_condition = $_counter_condition + $rank;
            } else {

                $counter_condition = $rank + $after;
            }

            $subUsers = collect();
            for ($i = $rank - $befor; $i <= $counter_condition; $i++) {
                $subUsers->add($users[$i - 1]);
                $subUsers->last()->position = $i;
            }
            return response($subUsers, 200);
        }
    }

    public function get()
    {
        return User::orderBy('karma_score', 'DESC')->get();
    }
}
