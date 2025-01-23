<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\locale;
use App\Models\translation;


class TranslationController extends Controller
{
    // Create a new translation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|string|exists:locales,code',
            'key_name' => 'required|string',
            'content' => 'required|string',
            'tags' => 'nullable|array'
        ]);

        $locale = Locale::where('code', $validated['locale'])->first();

        $translation = $locale->translations()->create([
            'key_name' => $validated['key_name'],
            'content' => $validated['content'],
            'tags' => json_encode($validated['tags'] ?? [])
        ]);

        return response()->json($translation, 201);
    }

    // Update an existing translation
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'key_name' => 'required|string',
            'content' => 'required|string',
            'tags' => 'nullable|array'
        ]);

        $translation = Translation::findOrFail($id);
        $translation->update([
            'key_name' => $validated['key_name'],
            'content' => $validated['content'],
            'tags' => json_encode($validated['tags'] ?? [])
        ]);

        return response()->json($translation, 200);
    }

    // Get translations with filters
    public function index(Request $request)
    {
        $query = Translation::query();

        if ($request->has('locale')) {
            $query->whereHas('locale', function ($q) use ($request) {
                $q->where('code', $request->locale);
            });
        }

        if ($request->has('tags')) {
            $query->whereJsonContains('tags', $request->tags);
        }

        if ($request->has('key_name')) {
            $query->where('key_name', 'like', "%{$request->key_name}%");
        }

        return response()->json($query->paginate(10));
    }

    // Export translations as JSON
    public function export()
    {
        $translations = Translation::all();
        return response()->json($translations);
    }
}
