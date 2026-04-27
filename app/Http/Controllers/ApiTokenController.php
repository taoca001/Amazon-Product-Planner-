<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    /**
     * Show all tokens for the authenticated user.
     */
    public function index()
    {
        $tokens = auth()->user()->apiTokens()->latest()->get();
        return view('profile.tokens', compact('tokens'));
    }

    /**
     * Create a new API token for the authenticated user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $user = auth()->user();

        if ($user->apiTokens()->count() >= 10) {
            return back()->with('error', 'Maximal 10 API-Tokens erlaubt. Bitte zuerst einen alten Token widerrufen.');
        }

        $plainToken = ApiToken::generateToken();

        $user->apiTokens()->create([
            'name'       => $validated['name'],
            'token'      => ApiToken::hashToken($plainToken),
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        // Show plain token once — stored in session flash
        return back()->with('new_token', $plainToken)->with('success', 'API-Token erstellt. Kopiere ihn jetzt — er wird nicht nochmal angezeigt.');
    }

    /**
     * Revoke (delete) one of the authenticated user's tokens.
     */
    public function destroy(ApiToken $token)
    {
        if ($token->user_id !== auth()->id()) {
            abort(403);
        }

        $token->delete();

        return back()->with('success', 'API-Token widerrufen.');
    }
}
