<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::all();
        return response()->json($applications);
    }

    public function store(Request $request)
    {
        $application = new Application();
        $application->title = $request->input('title');
        $application->description = $request->input('description');
        $application->user_id = auth()->id();
        $application->save();
        return response()->json($application, 201);
    }

    public function show($id)
    {
        $application = Application::find($id);
        if (!$application) {
            return response()->json(['error' => 'Application not found'], 404);
        }
        if ($application->user_id!== auth()->id() && auth()->user()->role!== 'admin') {
            return response()->json(['error' => 'You are not authorized to view this application'], 403);
        }
        return response()->json($application);
    }

    public function update(Request $request, $id)
    {
        $application = Application::find($id);
        if (!$application) {
            return response()->json(['error' => 'Application not found'], 404);
        }
        if ($application->user_id!== auth()->id() && auth()->user()->role!== 'admin') {
            return response()->json(['error' => 'You are not authorized to update this application'], 403);
        }
        $application->title = $request->input('title');
        $application->description = $request->input('description');
        $application->save();
        return response()->json($application);
    }

    public function destroy($id)
    {
        $application = Application::find($id);
        if (!$application) {
            return response()->json(['error' => 'Application not found'], 404);
        }
        if ($application->user_id!== auth()->id() && auth()->user()->role!== 'admin') {
            return response()->json(['error' => 'You are not authorized to delete this application'], 403);
        }
        $application->delete();
        return response()->json(['message' => 'Application deleted successfully']);
    }
}