<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sport;

class SportController extends Controller
{
    public function index()
    {
        return Sport::select('id', 'name_es', 'name_en')->get();
    }
}
