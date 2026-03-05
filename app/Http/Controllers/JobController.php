<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Http\Requests\StoreJobRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request): JsonResponse
{
    $query = Job::query()->withCount('applications');

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('title', 'like', "%{$request->search}%")
              ->orWhere('company', 'like', "%{$request->search}%");
        });
    }

    if ($request->location) {
        $query->where('location', 'like', "%{$request->location}%");
    }

    if ($request->category) {
        $query->where('category', $request->category);
    }

    if ($request->type) {
        $query->where('type', $request->type);
    }

    // Featured filter
    if ($request->is_featured) {
        $query->where('is_featured', true);
    }

    // Sort
    if ($request->sort === 'latest') {
        $query->latest();
    } else {
        $query->orderByDesc('is_featured')->latest();
    }

    $perPage = $request->per_page ?? 20;
    $jobs    = $query->paginate($perPage);

    return response()->json([
        'success' => true,
        'data'    => $jobs->items(),
        'meta'    => [
            'total'        => $jobs->total(),
            'current_page' => $jobs->currentPage(),
            'last_page'    => $jobs->lastPage(),
        ],
    ]);
}

    public function show(Job $job): JsonResponse
    {
        $job->loadCount('applications');

        return response()->json([
            'success' => true,
            'data'    => $job,
        ]);
    }

    public function store(StoreJobRequest $request): JsonResponse
    {
        $job = Job::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Job created successfully.',
            'data'    => $job,
        ], 201);
    }

    public function update(StoreJobRequest $request, Job $job): JsonResponse
    {
        $job->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Job updated successfully.',
            'data'    => $job,
        ]);
    }

    public function destroy(Job $job): JsonResponse
    {
        $job->delete();

        return response()->json([
            'success' => true,
            'message' => 'Job deleted successfully.',
        ]);
    }

    public function filters(): JsonResponse
    {
        $categories = Job::distinct()->pluck('category')->sort()->values();
        $locations  = Job::distinct()->pluck('location')->sort()->values();
        $types      = Job::distinct()->pluck('type')->sort()->values();

        return response()->json([
            'success' => true,
            'data'    => compact('categories', 'locations', 'types'),
        ]);
    }
}