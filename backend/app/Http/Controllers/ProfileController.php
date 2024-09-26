<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Profile;

class ProfileController extends Controller
{
    /**
     * Показать профиль текущего пользователя.
     */
    public function show()
    {
        $user = Auth::user()->load('profile');

        return response()->json([
            'user' => $user,
            'profile' => $user->profile
        ]);
    }

    /**
     * Обновить профиль и данные пользователя текущего пользователя.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Валидация данных
        $data = $request->validate([
            // Поля профиля
            'avatar' => 'nullable|url',
            'bio' => 'nullable|string',
            'website' => 'nullable|url',

            // Поля пользователя
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Проверка наличия профиля, создание если отсутствует
        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->save();
        }

        // Обновление профиля
        if (isset($data['avatar']) || isset($data['bio']) || isset($data['website'])) {
            $profileData = array_intersect_key($data, [
                'avatar' => 1,
                'bio' => 1,
                'website' => 1,
            ]);
            $profile->update($profileData);
        }

        // Обновление данных пользователя
        if (isset($data['name']) || isset($data['email']) || isset($data['password'])) {
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            $userData = array_intersect_key($data, [
                'name' => 1,
                'email' => 1,
                'password' => 1,
            ]);
            $user->update($userData);
        }

        return response()->json([
            'message' => 'Профиль успешно обновлен.',
            'user' => $user->fresh(),
            'profile' => $profile->fresh(),
        ]);
    }

    /**
     * Удалить текущий профиль пользователя и самого пользователя.
     */
    public function destroy()
    {
        $user = Auth::user();
        $user->profile()->delete();
        $user->delete();

        return response()->json([
            'message' => 'Пользователь и его профиль успешно удалены.'
        ]);
    }
}