<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessTruffle;
use App\Models\Truffle;
use App\Models\User;
use App\Services\RequestChecker;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;

class ApiController extends BaseController
{
    use DispatchesJobs;

    public function token(Request $request)
    {
        $user = User::where('email', $request->email ?? '')->first();

        if (!$user || !Hash::check($request->password ?? '', $user->password ?? '')) {
            abort(401);
        }

        return $user->createToken($request->email)->plainTextToken;
    }

    public function registerTruffle(Request $request)
    {
        // Check user token
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        if (!PersonalAccessToken::findToken($token)) {
            return abort(403);
        }

        $checker = new RequestChecker($request->toArray());

        // Check request
        if (!$checker->check()) {
            return response()->json(['status' => 'error'], 422);
        }

        $now = new DateTime();

        // Save truffle
        $truffle = new Truffle();
        $truffle->sku = (string)Str::uuid();
        $truffle->weight = $request->weight;
        $truffle->price = $request->price;
        $truffle->created_at = $now;
        $truffle->expires_at = $now->modify('+1 month');
        $truffle->save();

        ProcessTruffle::dispatch($truffle);

        return response()->json(['status' => 'success']);
    }
}
