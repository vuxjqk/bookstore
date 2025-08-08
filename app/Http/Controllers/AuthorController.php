<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $authors = Author::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        })->latest()->paginate(10)->appends($request->query());

        return view('authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('authors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:authors,name',
            'slug' => 'required|string|max:150|unique:authors,slug',
            'description' => 'nullable|string',
        ]);

        Author::create($validated);

        return redirect()->route('authors.index')->with('success', __('Author created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        return view('authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:authors,name,' . $author->id,
            'slug' => 'required|string|max:150|unique:authors,slug,' . $author->id,
            'description' => 'nullable|string',
        ]);

        $author->update($validated);

        return redirect()->route('authors.index')->with('success', __('Author updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        try {
            $author->delete();
            return redirect()->route('authors.index')->with('success', __('Author deleted successfully.'));
        } catch (QueryException $e) {
            $msg = $e->getCode() === '23000'
                ? __('Cannot delete because it is in use.')
                : __('An error occurred while deleting: ') . $e->getMessage();

            return redirect()->back()->with('error', $msg);
        }
    }
}
