<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeatureRequest;
use App\Http\Requests\UpdateFeatureRequest;
use App\Http\Resources\FeatureResource;
use App\Models\Feature;

class FeatureController extends Controller
{
    public function index()
    {
        $features = Feature::latest()->paginate(10);
        return FeatureResource::collection($features);
    }

    public function store(StoreFeatureRequest $request)
    {
        $feature = Feature::create($request->validated());
        return new FeatureResource($feature);
    }

    public function show(Feature $feature)
    {
        return new FeatureResource($feature);
    }

    public function update(UpdateFeatureRequest $request, Feature $feature)
    {
        $feature->update($request->validated());
        return new FeatureResource($feature);
    }

    public function destroy(Feature $feature)
    {
        $feature->delete();
        return response()->json(['message' => 'Feature deleted successfully']);
    }

}
