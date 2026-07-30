<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;

class FloorPlanController extends Controller
{
    public function index()
    {
        return view('restaurant.editor');
    }
}