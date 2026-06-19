<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $count = User::where('status', 'active')->where('role', 'user')->count();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'count' => $count,
            'filters' => $request->only(['search', 'role'])
        ]);
    }

    public function toggleStatus(User $user)
    {
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';

        $user->status = $newStatus;

        if ($newStatus === 'inactive') {
            $user->letter_box = null;
        }

        $user->save();

        return back()->with('success', 'Status user berhasil diperbarui.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usedBoxes = User::whereNotNull('letter_box')->pluck('letter_box')->toArray();
        $allBoxes = ['A', 'B'];
        $availableBoxes = array_values(array_diff($allBoxes, $usedBoxes));

        return Inertia::render('Admin/Users/create', [
            'availableBoxes' => $availableBoxes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'status' => 'required|in:active,inactive',
            'letter_box' => 'nullable|in:A,B',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'user'; // Otomatis user

        if ($validated['status'] === 'inactive') {
            $validated['letter_box'] = null;
        }

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return Inertia::render('Admin/Users/show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        // Cari kotak yang sedang digunakan, KECUALI oleh user ini sendiri
        $usedBoxes = User::whereNotNull('letter_box')
            ->where('id', '!=', $user->id)
            ->pluck('letter_box')
            ->toArray();

        $allBoxes = ['A', 'B'];
        $availableBoxes = array_values(array_diff($allBoxes, $usedBoxes));

        return Inertia::render('Admin/Users/edit', [
            'user' => $user,
            'availableBoxes' => $availableBoxes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|in:active,inactive',
            'letter_box' => 'nullable|in:A,B',
            'phone_number' => 'nullable|string|max:20',
        ]);

        if ($validated['status'] === 'inactive') {
            $validated['letter_box'] = null;
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Profil pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus akun admin.');
        }

        // Hapus user
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus dari sistem.');
    }
}
