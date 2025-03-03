<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function getToken()
    {
        $token = Token::create([
            'token' => Str::random(254),
            'expires_at' => Carbon::now()->addMinutes(40)
        ]);

        return [
            "success" => true,
            "token" => $token->token
        ];
    }
}
