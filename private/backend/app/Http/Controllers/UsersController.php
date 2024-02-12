<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends Controller
{
    public function index()
    {
        return response()->json([
            'users' => User::all(),
        ]);
    }

    public function register(Request $request)
    {
        $user = User::create($request->all());

        $credentials = request(['email', 'password']);

        Auth::attempt($credentials);
        $user = Auth::user();
        $token = JWTAuth::fromUser($user);

        return response()->json([
            "message" => "User created successfully",
            "token" => $token,
            "user" => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        if (Cookie::get('remember_token') != null) {
            $user = User::where('remember_token', Cookie::get('remember_token'))->first();

            if ($user != null) {
                $token = JWTAuth::fromUser($user);

                return response()->json([
                    "message" => "Login succesfuly",
                    "token" => $token,
                    "user" => $user,
                ]);
            }
        }

        if (RateLimiter::tooManyAttempts(request()->ip(), 3)) {
            return response()->json([
                "message" => "Too many attempts, please try again later",
            ], 429);
        }

        $credentials = request(['email', 'password']);

        if (!auth()->attempt($credentials)) {
            RateLimiter::hit(request()->ip());
            return response()->json(['errors' => 'Invalid username and Password'], 401);
        }

        Auth::attempt($credentials);
        $user = Auth::user();
        $token = JWTAuth::fromUser($user);

        if ($request->remember_me == true) {
            if ($user->remember_token == null) {
                $user->setRememberToken(Str::random(60));
                $user->remember_token_expires_at = Carbon::now()->addDays(7);
                $user->save();
            }
        }

        return response()
            ->json([
                "message" => "Login succesfuly",
                "token" => $token,
                "user" => $user,
            ])
            ->cookie('remember_token', $user->remember_token, 60 * 24 * 7);
    }

    public function refresh()
    {
        $token = JWTAuth::getToken();
        $newToken = JWTAuth::refresh($token);

        return response()->json([
            "token" => $newToken,
        ]);
    }

    public function logout()
    {
        $token = JWTAuth::getToken();
        $token = JWTAuth::invalidate($token);
        $cookie = Cookie::forget('remember_token');

        return response()->json([
            "message" => "Logout succesfuly",
        ])->withCookie($cookie);
    }

    public function logoutFromAll()
    {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $token = JWTAuth::invalidate($token);
        $cookie = Cookie::forget('remember_token');
        $user->setRememberToken(null);
        $user->save();

        return response()->json([
            "message" => "Logout succesfuly",
        ])->withCookie($cookie);
    }

    public function updateAvatar(Request $request, $id)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find($id);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarPath = $avatar->store('avatars', 'public');
            $user->avatar = $avatarPath;
            $user->save();
        }

        return back()->with('success', 'Avatar uploaded successfully.');
    }

    public function deleteAvatar($id)
    {
        $user = User::find($id);
        $user->avatar = null;
        $user->save();

        return back()->with('success', 'Avatar deleted successfully.');
    }
}
