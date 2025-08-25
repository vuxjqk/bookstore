<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('include_deleted') && $request->boolean('include_deleted')) {
            $query = $query->withTrashed();
        }

        $users = $query->filter($request->all())
            ->paginate(10)
            ->appends($request->query());

        $totalUsers = User::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalSellers = User::where('role', 'seller')->count();
        $totalImporters = User::where('role', 'importer')->count();

        return view('users.index', compact(
            'users',
            'totalUsers',
            'totalCustomers',
            'totalSellers',
            'totalImporters'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', __('User deleted successfully.'));
        } catch (QueryException $e) {
            $msg = $e->getCode() === '23000'
                ? __('Cannot delete because it is in use.')
                : __('An error occurred while deleting: ') . $e->getMessage();

            return redirect()->back()->with('error', $msg);
        }
    }

    public function restore($id)
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return redirect()->back()->with('error', __('User not found.'));
        }

        if ($user->trashed()) {
            $user->deleted_at = null;
            $user->save();
            return redirect()->route('users.index')->with('success', __('User restored successfully.'));
        } else {
            return redirect()->back()->with('error', __('This user has not been deleted.'));
        }
    }

    public function forceDelete($id)
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return redirect()->back()->with('error', __('User not found.'));
        }

        try {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->forceDelete();

            return redirect()->route('users.index')->with('success', __('User permanently deleted.'));
        } catch (QueryException $e) {
            $msg = $e->getCode() === '23000'
                ? __('Cannot delete because it is in use.')
                : __('An error occurred while deleting: ') . $e->getMessage();

            return redirect()->back()->with('error', $msg);
        }
    }
}
