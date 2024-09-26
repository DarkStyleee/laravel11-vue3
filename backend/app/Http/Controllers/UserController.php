<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\AccessGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    // Получить всех пользователей
    public function index()
    {
        return User::with(['roles', 'accessGroups'])->get();
    }

    // Получить конкретного пользователя
    public function show($id)
    {
        $user = User::with(['roles', 'accessGroups'])->find($id);
        if ($user) {
            return response()->json($user, 200);
        }

        return response()->json(['error' => 'User not found'], 404);
    }

    // Создать нового пользователя
    public function store(Request $request)
    {
        // Валидация запроса
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|string|exists:roles,name',
            'access_group' => 'sometimes|string|exists:access_groups,name',
        ]);

        // Создание пользователя
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Назначение роли
        if (isset($validated['role'])) {
            $role = Role::where('name', $validated['role'])->first();
            if ($role) {
                $user->roles()->attach($role->id);
            }
        }

        // Назначение группы доступов
        if (isset($validated['access_group'])) {
            $accessGroup = AccessGroup::where('name', $validated['access_group'])->first();
            if ($accessGroup) {
                $user->accessGroups()->attach($accessGroup->id);
            }
        }

        return response()->json($user, 201);
    }

    // Обновить пользователя
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8|confirmed',
            'role' => 'sometimes|string|exists:roles,name',
            'access_group' => 'sometimes|string|exists:access_groups,name',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        // Обновление ролей
        if (isset($validated['role'])) {
            $role = Role::where('name', $validated['role'])->first();
            if ($role) {
                $user->roles()->sync([$role->id]);
            }
        }

        // Обновление групп доступов
        if (isset($validated['access_group'])) {
            $accessGroup = AccessGroup::where('name', $validated['access_group'])->first();
            if ($accessGroup) {
                $user->accessGroups()->sync([$accessGroup->id]);
            }
        }

        return response()->json($user, 200);
    }

    // Удалить пользователя
    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(null, 204);
    }

    // Получить посты пользователя
    public function posts($id)
    {
        // Найти пользователя по ID
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Пользователь не найден.'
            ], 404);
        }

        // Получить посты пользователя с количеством комментариев
        $posts = $user->posts()->withCount('comments')->orderBy('views', 'desc')->get();

        return response()->json($posts);
    }

    public function current()
    {
        $user = Auth::user()->load('profile');

        return new UserResource($user);
    }
}