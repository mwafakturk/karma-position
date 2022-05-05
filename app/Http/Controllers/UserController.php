<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getOverall($id, $numUsers = null)
    {
        DB::statement("SET @rank := 0");
        $userReversePosition = DB::select('SELECT *
        FROM (
          SELECT *, @rank := @rank + 1 rank
          FROM (
            SELECT
              id,
              karma_score * 1 karma_score
            FROM
              users
          ) a
          ORDER BY karma_score desc
        ) e
        WHERE
          id = ?', [$id]);

        $userCount     = User::count();
        $maxKarma      = User::select('karma_score')->max('karma_score');
        $minKarma      = User::select('karma_score')->min('karma_score');
        $selectedUser  = User::with('image')->find($id);

        if (!$numUsers || $numUsers > $userCount || $numUsers > 10000) {
            $numUsers = 5;
        }

        if ($selectedUser->karma_score == $maxKarma) {
            $all_users = User::selectRaw('id, username , karma_score , image_id')
                ->orderBy('karma_score', 'DESC')
                ->with('image')
                ->take($numUsers + 1)
                ->get();

            for ($i = 0; $i < count($all_users); $i++) {
                $all_users[$i]->position = $userCount - $userReversePosition[0]->rank + $i + 1;
            }

            return response($all_users, 200);
        } else if ($selectedUser->karma_score == $minKarma) {
            $all_users = User::selectRaw('id, username , karma_score , image_id')
                ->orderBy('karma_score', 'ASC')
                ->with('image')
                ->take($numUsers + 1)
                ->get();
            for ($i = 0; $i < count($all_users); $i++) {
                $all_users[$i]->position = $userCount - $userReversePosition[0]->rank - $i + 1;
            }
            return response($all_users, 200);
        } else {
            $befor = round(($numUsers - 1) / 2);
            $after = ($numUsers - 1) - $befor;

            $allUsers = User::selectRaw('id, username , karma_score , image_id')
                ->orderBy('karma_score', 'DESC')
                ->where('karma_score', '<', $selectedUser->karma_score)
                ->with('image')->take($befor)
                ->get();
            for ($i = 0; $i < count($allUsers); $i++) {
                $allUsers[$i]->position = $userCount - $userReversePosition[0]->rank + $i + 1;
            }

            $selectedUser->position = $userCount - $userReversePosition[0]->rank;
            $allUsers->add($selectedUser);

            $usersAfter = User::selectRaw('id, username , karma_score , image_id')
                ->orderBy('karma_score', 'ASC')
                ->where('karma_score', '>', $selectedUser->karma_score)
                ->with('image')
                ->take($after)
                ->get();
            for ($i = 0; $i < count($usersAfter); $i++) {
                $usersAfter[$i]->position = $userCount - $userReversePosition[0]->rank - $i - 1;
            }
            $allUsers->add($usersAfter);

            return response($allUsers, 200);
        }
    }
}
