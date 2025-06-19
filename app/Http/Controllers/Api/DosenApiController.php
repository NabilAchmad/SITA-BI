<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;

class DosenApiController extends Controller
{
    // List all dosen with user relation
    public function index()
    {
        $dosen = Dosen::with('user')->get();
        return response()->json($dosen);
    }

    // Show specific dosen by id
    public function show($id)
    {
        $dosen = Dosen::with('user')->find($id);
        if (!$dosen) {
            return response()->json(['message' => 'Dosen not found'], 404);
        }
        return response()->json($dosen);
    }

    // Store new dosen and user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nidn' => 'required|string|unique:dosen,nidn',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Create dosen
        $dosen = Dosen::create([
            'user_id' => $user->id,
            'nidn' => $validated['nidn'],
        ]);

        return response()->json($dosen, 201);
    }

    // Update dosen and user
    public function update(Request $request, $id)
    {
        $dosen = Dosen::with('user')->find($id);
        if (!$dosen) {
            return response()->json(['message' => 'Dosen not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $dosen->user->id,
            'nidn' => 'sometimes|required|string|unique:dosen,nidn,' . $dosen->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update user
        if ($request->has('name') || $request->has('email') || $request->filled('password')) {
            $dosen->user->update([
                'name' => $request->name ?? $dosen->user->name,
                'email' => $request->email ?? $dosen->user->email,
                'password' => $request->filled('password') ? bcrypt($request->password) : $dosen->user->password,
            ]);
        }

        // Update dosen
        $dosen->update([
            'nidn' => $request->nidn ?? $dosen->nidn,
        ]);

        return response()->json($dosen);
    }

    // Delete dosen and related user
    public function destroy($id)
    {
        $dosen = Dosen::find($id);
        if (!$dosen) {
            return response()->json(['message' => 'Dosen not found'], 404);
        }

        $user = $dosen->user;
        $dosen->delete();
        if ($user) {
            $user->delete();
        }

        return response()->json(['message' => 'Dosen deleted successfully']);
    }
}
