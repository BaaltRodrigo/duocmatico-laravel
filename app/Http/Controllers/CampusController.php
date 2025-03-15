<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Http\Resources\CampusIdentifierResource;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    public function index(Request $request)
    {
        $campuses = Campus::all();
        $resources = CampusIdentifierResource::collection($campuses);

        return response()->json($resources);
    }
}
