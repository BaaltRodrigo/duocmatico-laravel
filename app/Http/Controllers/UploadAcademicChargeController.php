<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostAcademicChargeRequest;
use App\Actions\ImportAcademicData;

class UploadAcademicChargeController extends Controller
{
    public function __invoke(PostAcademicChargeRequest $request, ImportAcademicData $action)
    {
        $validated = $request->validated();

        $action->handle($validated);

        return response()->json([
            'message' => 'file loaded successfully',
        ]);
    }
}
