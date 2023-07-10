<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\PostTokenRequest;
use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    use DispatchesJobs;

    /**
     * @param PostTokenRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function token(PostTokenRequest $request)
    {
        $user = User::where('email', $request->email ?? '')->first();

        if (!$user || !Hash::check($request->password ?? '', $user->password ?? '')) {
            abort(401);
        }

        return response()->json($user->createToken($request->email)->plainTextToken);
    }
}
