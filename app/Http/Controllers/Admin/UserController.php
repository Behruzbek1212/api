<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $tarfics = User::query()
            ->orderByDesc('id')
            ->paginate(request()->get('limit', 15));
        // $list = TraficResource::collection($tarfics);
        return response()->json([
            'status' => true,
            'data' => $tarfics
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'numeric', 'unique:users,phone'],
            'password' => ['required', 'min:8'],
            'role' => ['required', 'in:admin,customer,candidate'],
            'email' => ['email', 'unique:users,email']
        ]);
        User::create([
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
        ]);

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $trafic = User::query()
            ->findOrFail($slug);
        return response()->json([
            'status' => true,
            'data' => $trafic
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        $user = User::query()
            ->findOrFail($request->get('slug'));

        $request->validate([
            'phone' => ['required', 'numeric'],
            'password' => ['required', 'min:8'],
            'role' => ['required'],
            'email' => ['email']
        ]);
        $user->update([
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
        ]);

        return response()->json([
            'status' => true,
            'message' => []
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        User::query()
            ->findOrFail($request->slug)->delete();

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }
}
