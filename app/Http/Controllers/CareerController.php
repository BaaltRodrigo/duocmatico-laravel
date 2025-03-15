<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Http\Resources\CareerIdentifierResource;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index(Request $request)
    {
        $careers = Career::all();
        $resources = CareerIdentifierResource::collection($careers);

        return response()->json($resources);
    }
}
