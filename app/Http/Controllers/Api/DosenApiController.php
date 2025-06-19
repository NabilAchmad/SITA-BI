<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB facade
use Illuminate\Support\Facades\Hash; // Gunakan Hash::make() alih-alih bcrypt()

class DosenApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $dosen = Dosen::with('user')->get();
        return response()->json([
            'message' => 'List of all dosen retrieved successfully',
            'data' => $dosen
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Menggunakan findOrFail untuk penanganan 404 otomatis
        $dosen = Dosen::with('user')->find($id);

        if (!$dosen) {
            return response()->json(['message' => 'Dosen not found'], 404);
        }

        return response()->json([
            'message' => 'Dosen retrieved successfully',
            'data' => $dosen
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'nidn' => 'required|string|unique:dosen,nidn',
            ]);

            $dosen = null; // Inisialisasi variabel untuk scope di luar transaction
            DB::transaction(function () use ($validated, &$dosen) {
                // Create user
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']), // Gunakan Hash::make
                ]);

                // Create dosen
                $dosen = Dosen::create([
                    'user_id' => $user->id,
                    'nidn' => $validated['nidn'],
                ]);

                // Memuat ulang relasi user untuk respon lengkap
                $dosen->load('user');
            });

            return response()->json([
                'message' => 'Dosen created successfully',
                'data' => $dosen
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Error storing dosen: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create dosen',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $dosen = Dosen::with('user')->find($id);

            if (!$dosen) {
                return response()->json(['message' => 'Dosen not found'], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $dosen->user->id,
                'nidn' => 'sometimes|required|string|unique:dosen,nidn,' . $dosen->id,
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            DB::transaction(function () use ($validated, $dosen, $request) {
                // Update user
                if ($request->has('name') || $request->has('email') || $request->filled('password')) {
                    $dosen->user->update([
                        'name' => $validated['name'] ?? $dosen->user->name,
                        'email' => $validated['email'] ?? $dosen->user->email,
                        'password' => $request->filled('password') ? Hash::make($validated['password']) : $dosen->user->password, // Gunakan Hash::make
                    ]);
                }

                // Update dosen
                $dosen->update([
                    'nidn' => $validated['nidn'] ?? $dosen->nidn,
                ]);

                // Memuat ulang relasi user setelah update untuk respon yang akurat
                $dosen->load('user');
            });

            return response()->json([
                'message' => 'Dosen updated successfully',
                'data' => $dosen
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating dosen: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update dosen',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $dosen = Dosen::find($id);

            if (!$dosen) {
                return response()->json(['message' => 'Dosen not found'], 404);
            }

            DB::transaction(function () use ($dosen) {
                $user = $dosen->user; // Dapatkan user terkait sebelum dosen dihapus
                $dosen->delete(); // Hapus record dosen
                if ($user) {
                    $user->delete(); // Hapus record user terkait jika ada
                }
            });

            return response()->json(['message' => 'Dosen deleted successfully'], 200);

        } catch (\Exception $e) {
            Log::error('Error deleting dosen: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to delete dosen',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}