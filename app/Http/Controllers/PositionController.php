<?php

namespace App\Http\Controllers;

use App\Models\Position;

class PositionController extends Controller
{
    public function list()
    {
        $positions = Position::all();

        return [
            "success" => true,
            "positions" => $positions
        ] ;

    }
}
