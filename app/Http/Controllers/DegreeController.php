<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDegreeRequest;
use App\Http\Requests\UpdateDegreeRequest;
use App\Models\Degree;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DegreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        $degrees = Degree::query()
            ->withCount('students')
            ->orderBy('title')
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'degrees' => $degrees
                    ->map(fn (Degree $degree): array => $this->degreeJson($degree))
                    ->values(),
            ]);
        }

        return view('degrees.index', compact('degrees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('degrees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDegreeRequest $request): RedirectResponse|JsonResponse
    {
        $degree = Degree::create($request->validated());

        Log::info('degree created');

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Degree created successfully.',
                'degree' => $this->degreeJson($degree->loadCount('students')),
            ], 201);
        }

        return redirect()->route('degrees.index')
            ->with('success', 'Degree created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Degree $degree): View|JsonResponse
    {
        $degree->loadCount('students')
            ->load('students');

        if ($request->wantsJson()) {
            return response()->json([
                'degree' => $this->degreeJson($degree, includeStudents: true),
            ]);
        }

        return view('degrees.show', compact('degree'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Degree $degree): View|JsonResponse
    {
        if ($request->wantsJson()) {
            return response()->json([
                'degree' => $this->degreeJson($degree->loadCount('students')),
            ]);
        }

        return view('degrees.edit', compact('degree'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDegreeRequest $request, Degree $degree): RedirectResponse|JsonResponse
    {
        $degree->update($request->validated());

        Log::info('degree updated');

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Degree updated successfully.',
                'degree' => $this->degreeJson($degree->fresh()->loadCount('students')),
            ]);
        }

        return redirect()->route('degrees.index')
            ->with('success', 'Degree updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Degree $degree): RedirectResponse|JsonResponse
    {
        $studentCount = $degree->students()->count();

        if ($studentCount > 0) {
            Log::warning('degree delete blocked because students are enrolled');

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Cannot delete a degree that still has enrolled students.',
                ], 422);
            }

            return redirect()->route('degrees.index')
                ->with('error', 'Cannot delete a degree that still has enrolled students.');
        }

        Log::info('degree deleted');

        $degree->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Degree deleted successfully.',
            ]);
        }

        return redirect()->route('degrees.index')
            ->with('success', 'Degree deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function degreeJson(Degree $degree, bool $includeStudents = false): array
    {
        $degree->loadCount('students');

        $payload = [
            'id' => $degree->id,
            'title' => $degree->title,
            'students_count' => $degree->students_count,
            'view_url' => route('degrees.show', $degree),
            'edit_url' => route('degrees.edit', $degree),
            'update_url' => route('degrees.update', $degree),
            'delete_url' => route('degrees.destroy', $degree),
        ];

        if ($includeStudents) {
            $degree->loadMissing('students');
            $payload['students'] = $degree->students
                ->map(fn ($student): array => [
                    'full_name' => $student->full_name,
                    'email' => $student->email,
                    'contact' => $student->contact,
                ])
                ->values();
        }

        return $payload;
    }
}
