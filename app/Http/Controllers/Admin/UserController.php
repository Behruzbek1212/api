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
        $users = User::query()
            ->orderByDesc('id')
            ->withTrashed()
            ->paginate(request()->get('limit', 15));
        // $list = TraficResource::collection($tarfics);
        return response()->json([
            'status' => true,
            'data' => $users
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'numeric', 'unique:users,phone'],
            'password' => ['required', 'min:8'],
            'role' => ['required', 'in:admin,customer,candidate'],
            'email' => ['email', 'unique:users,email'],
            'fio' => ['string'],
            'subrole' => ['string'],
        ]);
        // dd($request->all());
        User::create([
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
            'fio' => $request->fio,
            'subrole' => $request->subrole,
        ]);

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $user = User::query()
            ->withTrashed()
            ->findOrFail($slug);
        return response()->json([
            'status' => true,
            'data' => $user
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
            'email' => ['email'],
            'fio' => ['string'],
            'subrole' => ['string']
        ]);
        $user->update([
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
            'fio' => $request->fio,
            'subrole' => $request->subrole,
        ]);

        return response()->json([
            'status' => true,
            'message' => []
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $params = $request->validate([
            'slug' => ['string', 'required']
        ]);

        $user = User::query()
            ->withTrashed()
            ->findOrFail($params['slug']);

        if (!$user->trashed())
            $user->delete();

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }
}
