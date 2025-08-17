<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $publishers = Publisher::filter($request->all())
            ->paginate(10)
            ->appends($request->query());

        return view('publishers.index', compact('publishers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('publishers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name',
            'slug' => 'required|string|max:255|unique:publishers,slug',
            'email' => 'nullable|string|max:255|unique:publishers,email',
            'address' => 'nullable|string|max:255',
        ]);

        Publisher::create($validated);

        return redirect()->route('publishers.index')->with('success', __('Publisher created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Publisher $publisher)
    {
        return view('publishers.show', compact('publisher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publisher $publisher)
    {
        return view('publishers.edit', compact('publisher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name,' . $publisher->id,
            'slug' => 'required|string|max:255|unique:publishers,slug,' . $publisher->id,
            'email' => 'nullable|string|max:255|unique:publishers,email,' . $publisher->id,
            'address' => 'nullable|string|max:255',
        ]);

        $publisher->update($validated);

        return redirect()->route('publishers.index')->with('success', __('Publisher updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher)
    {
        try {
            $publisher->delete();
            return redirect()->route('publishers.index')->with('success', __('Publisher deleted successfully.'));
        } catch (QueryException $e) {
            $msg = $e->getCode() === '23000'
                ? __('Cannot delete because it is in use.')
                : __('An error occurred while deleting: ') . $e->getMessage();

            return redirect()->back()->with('error', $msg);
        }
    }
}
