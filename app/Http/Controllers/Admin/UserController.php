<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::withCount(['products', 'apiTokens'])->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_admin'] = $request->boolean('is_admin', false);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Benutzer erfolgreich erstellt!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->loadCount(['products', 'apiTokens']);
        $tokens = $user->apiTokens()->latest()->get();
        return view('admin.users.show', compact('user', 'tokens'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_admin'] = $request->boolean('is_admin', false);

        // Nicht das eigene Konto deaktivieren
        if ($user->id !== auth()->id()) {
            $validated['is_active'] = $request->boolean('is_active', false);
        } else {
            unset($validated['is_active']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)->with('success', 'Benutzer aktualisiert!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Du kannst dich selbst nicht löschen!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Benutzer gelöscht!');
    }

    /**
     * Toggle active/inactive status for a user.
     */
    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Du kannst deinen eigenen Account nicht deaktivieren.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'aktiviert' : 'deaktiviert';
        return back()->with('success', "{$user->name} wurde {$status}.");
    }

    /**
     * Revoke an API token belonging to a specific user.
     */
    public function revokeToken(User $user, ApiToken $token)
    {
        if ($token->user_id !== $user->id) {
            abort(404);
        }

        $token->delete();

        return back()->with('success', "Token \"{$token->name}\" widerrufen.");
    }
}
