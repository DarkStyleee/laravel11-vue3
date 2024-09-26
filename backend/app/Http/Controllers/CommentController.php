<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Получить все корневые комментарии с их дочерними комментариями и информацией о пользователе
    public function index()
    {
        return Comment::with(['children.user', 'user'])
            ->whereNull('parent_id')
            ->get();
    }

    // Получить конкретный комментарий с его дочерними комментариями и информацией о пользователе
    public function show($id)
    {
        return Comment::with(['children.user', 'user'])
            ->findOrFail($id);
    }

    // Создать новый комментарий или ответ на комментарий
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create($validated);
        $comment->load(['user']);

        return response()->json($comment, 201);
    }

    // Обновить комментарий
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $validated = $request->validate([
            'content' => 'sometimes|required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment->update($validated);
        $comment->load('user'); // Обновление информации о пользователе

        return response()->json($comment, 200);
    }

    // Удалить комментарий
    public function destroy($id)
    {
        Comment::destroy($id);
        return response()->json(null, 204);
    }
}