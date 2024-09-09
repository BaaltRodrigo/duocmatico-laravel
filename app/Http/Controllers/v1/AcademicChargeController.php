<?php

namespace App\Http\Controllers\v1;

use App\Actions\HandleAcademicChargeFileLoad;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListSectionRequest;
use App\Http\Requests\StoreAcademicChargeRequest as StoreRequest;
use App\Http\Requests\UpdateAcademicChargeRequest as UpdateRequest;
use App\Http\Resources\AcademicChargeResource;
use App\Http\Resources\Collection\SectionCollection;
use App\Http\Resources\Collections\AcademicChargeCollection;
use App\Http\Resources\Collections\CareerCollection;
use App\Http\Resources\Collections\SchoolCollection;
use App\Http\Resources\Identifiers\AcademicChargeIdentifier;
use App\Models\AcademicCharge;
use Illuminate\Http\Request;

class AcademicChargeController extends Controller
{
    /**
     * @urlParam showHidden tells the API to show hidden academic charges.
     */
    public function index(Request $request): AcademicChargeCollection
    {
        // check if request has the showHidden query parameter
        $showHidden = $request->query('showHidden', false);

        $academicCharges = $showHidden
            ? AcademicCharge::all()
            : AcademicCharge::where('is_hidden', false)->get();

        return new AcademicChargeCollection($academicCharges);
    }

    public function show(AcademicCharge $charge): AcademicChargeResource
    {

        return new AcademicChargeResource($charge);
    }

    public function store(StoreRequest $request): AcademicChargeIdentifier
    {
        $validated = $request->validated();

        // TODO: Optimize this to avoid the creation of the same academic charge
        // TODO: Move this to an job or something to avoid frontend waiting for the response

        $path = $request->file('file')->store('charges'); // upload the file into R2

        $charge = AcademicCharge::create([
            'year' => $validated['year'],
            'semester' => $validated['semester'],
            'name' => $validated['name'],
            'path' => $path,
        ]);

        return new AcademicChargeIdentifier($charge);
    }

    public function update(UpdateRequest $request, AcademicCharge $charge): AcademicChargeResource
    {
        $validated = $request->validated();
        $charge->update($validated);

        return new AcademicChargeResource($charge);
    }

    public function destroy(AcademicCharge $charge)
    {
        $this->authorize('delete', $charge);

        $charge->delete();

        return response()->json([
            'message' => 'Academic charge deleted successfully',
        ], 204); // What is no content in Response object?
    }

    public function careers(AcademicCharge $charge): CareerCollection
    {
        $charge->load('sections');

        return new CareerCollection($charge->sections->pluck('career')->unique());
    }

    public function sections(ListSectionRequest $request, AcademicCharge $charge): SectionCollection
    {
        // General filtering using resource id and their type
        $validated = $request->validated();

        $sections = $charge->sections()
            ->where($validated['resource_type'] . '_id', $validated['resource_id'])
            ->get();

        return SectionCollection::make($sections);
    }

    public function schools(AcademicCharge $charge): SchoolCollection
    {
        $charge->load('sections');

        return new SchoolCollection($charge->sections->pluck('school')->unique());
    }
}
