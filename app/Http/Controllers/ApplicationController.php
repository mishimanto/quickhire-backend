<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Http\Requests\StoreApplicationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function store(StoreApplicationRequest $request): JsonResponse
    {
        $user = Auth::user();

        $exists = Application::where('job_id', $request->job_id)
                             ->where('email', $request->email)
                             ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'You have already applied for this job.',
            ], 409);
        }

        $application = Application::create([
            ...$request->validated(),
            'user_id' => $user?->id,
            'name'    => $request->name ?? $user?->name,
            'email'   => $request->email ?? $user?->email,
        ]);

        $application->load('job:id,title,company');

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully! Good luck! 🎉',
            'data'    => $application,
        ], 201);
    }

    public function index(): JsonResponse
    {
        $applications = Application::with('job:id,title,company')
                                   ->latest()
                                   ->get();

        return response()->json([
            'success' => true,
            'data'    => $applications,
            'total'   => $applications->count(),
        ]);
    }

    public function myApplications(): JsonResponse
    {
        $applications = Application::with('job:id,title,company,location,type,category')
                                   ->where('user_id', Auth::id())
                                   ->latest()
                                   ->get();

        return response()->json([
            'success' => true,
            'data'    => $applications,
            'total'   => $applications->count(),
        ]);
    }

    public function updateStatus(Request $request, Application $application): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,reviewed,accepted,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $application->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Application status updated.',
            'data'    => $application->load('job:id,title,company'),
        ]);
    }
}