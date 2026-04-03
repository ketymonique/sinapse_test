<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
        ]);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('filter') && isset($request->filter['name'])) {
            $query->where('name', 'like', '%' . $request->filter['name'] . '%');
        }

        $users = $query->paginate(10);

        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => $request->password]);
        }

        return new UserResource($user);
    }
}
