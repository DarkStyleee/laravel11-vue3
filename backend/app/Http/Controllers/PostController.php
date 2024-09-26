<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Получить все посты, отсортированные по дате создания
    public function index()
    {
        return Post::withCount('comments')
            ->with('user')
            ->orderBy('views', 'desc')
            ->get()
            ->map(function ($post) {
                return $post;
            });
    }

    // Получить конкретный пост и увеличить счетчик просмотров
    public function show($id)
    {
        $post = Post::withCount('comments')
            ->findOrFail($id);

        // Увеличение счетчика просмотров
        $post->increment('views');

        return $post;
    }

    // Создать новый пост
    public function store(Request $request)
    {
        $post = Post::create($request->all());
        return response()->json($post, 201);
    }

    // Обновить пост
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());
        return response()->json($post, 200);
    }

    // Удалить пост
    public function destroy($id)
    {
        Post::destroy($id);
        return response()->json(null, 204);
    }

    // Получить комментарии поста
    public function comments($id)
    {
        // Убедиться, что пост существует
        $post = Post::findOrFail($id);

        // Получить корневые комментарии для поста с их дочерними комментариями и информацией о пользователе
        $comments = Comment::with(['children.user', 'user'])
            ->where('post_id', $id)
            ->whereNull('parent_id')
            ->get();

        return response()->json($comments, 200);
    }
}