<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class FeatureController extends Controller
{
    public function index()
    {
        $features = Feature::query()
            ->withCount('plans')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Features/Index', [
            'features' => $features,
        ]);
    }

    public function create()
    {
        return Inertia::render('Features/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                'unique:features,code',
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        Feature::create($validated);

        return redirect()
            ->route('features.index')
            ->with('success', 'Feature berhasil ditambahkan.');
    }

    public function show(Feature $feature)
    {
        $feature->load('plans');

        return Inertia::render('Features/Show', [
            'feature' => $feature,
        ]);
    }

    public function edit(Feature $feature)
    {
        return Inertia::render('Features/Edit', [
            'feature' => $feature,
        ]);
    }

    public function update(Request $request, Feature $feature)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('features', 'code')->ignore($feature->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $feature->update($validated);

        return redirect()
            ->route('features.index')
            ->with('success', 'Feature berhasil diperbarui.');
    }

    public function destroy(Feature $feature)
    {
        if ($feature->plans()->exists()) {
            return back()->with('error', 'Feature tidak bisa dihapus karena masih digunakan oleh plan.');
        }

        $feature->delete();

        return redirect()
            ->route('features.index')
            ->with('success', 'Feature berhasil dihapus.');
    }
}