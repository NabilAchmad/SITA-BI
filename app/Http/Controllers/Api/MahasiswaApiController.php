<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;

class MahasiswaApiController extends Controller
{
    // List all mahasiswa with user relation
    public function index()
    {
        $mahasiswa = Mahasiswa::with('user')->get();
        return response()->json($mahasiswa);
    }

    // Show specific mahasiswa by id
    public function show($id)
    {
        $mahasiswa = Mahasiswa::with('user')->find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }
        return response()->json($mahasiswa);
    }

    // Store new mahasiswa and user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nim' => 'required|string|unique:mahasiswa,nim',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'prodi' => 'required|string',
            'angkatan' => 'required|string',
            'status' => 'required|string',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Create mahasiswa
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $validated['nim'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'prodi' => $validated['prodi'],
            'angkatan' => $validated['angkatan'],
            'status' => $validated['status'],
        ]);

        return response()->json($mahasiswa, 201);
    }

    // Update mahasiswa and user
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::with('user')->find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $mahasiswa->user->id,
            'nim' => 'sometimes|required|string|unique:mahasiswa,nim,' . $mahasiswa->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'prodi' => 'nullable|string',
            'angkatan' => 'nullable|string',
            'status' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update user
        if ($request->has('name') || $request->has('email') || $request->filled('password')) {
            $mahasiswa->user->update([
                'name' => $request->name ?? $mahasiswa->user->name,
                'email' => $request->email ?? $mahasiswa->user->email,
                'password' => $request->filled('password') ? bcrypt($request->password) : $mahasiswa->user->password,
            ]);
        }

        // Update mahasiswa
        $mahasiswa->update([
            'nim' => $request->nim ?? $mahasiswa->nim,
            'phone' => $request->phone ?? $mahasiswa->phone,
            'address' => $request->address ?? $mahasiswa->address,
            'prodi' => $request->prodi ?? $mahasiswa->prodi,
            'angkatan' => $request->angkatan ?? $mahasiswa->angkatan,
            'status' => $request->status ?? $mahasiswa->status,
        ]);

        return response()->json($mahasiswa);
    }

    // Delete mahasiswa and related user
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }

        $user = $mahasiswa->user;
        $mahasiswa->delete();
        if ($user) {
            $user->delete();
        }

        return response()->json(['message' => 'Mahasiswa deleted successfully']);
    }
}
