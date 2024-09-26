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
        return Post::withCount('comments') // Удалено: 'likes as likes_count'
            ->orderBy('views', 'desc')
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'content' => $post->content,
                    'author' => $post->author,
                    'authorId' => $post->author_id,
                    'createdAt' => $post->created_at,
                    'updatedAt' => $post->updated_at,
                    'commentsCount' => $post->comments_count,
                    'views' => $post->views,
                    // Удалено: 'likesCount' и 'dislikesCount'
                ];
            });
    }

    // Получить конкретный пост и увеличить счетчик просмотров
    public function show($id)
    {
        $post = Post::withCount('comments') // Удалено: 'likes as likes_count'
            ->findOrFail($id);

        // Увеличение счетчика просмотров
        $post->increment('views');

        return [
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'author' => $post->author,
            'authorId' => $post->author_id,
            'createdAt' => $post->created_at,
            'updatedAt' => $post->updated_at,
            'commentsCount' => $post->comments_count,
            'views' => $post->views,
            // Удалено: 'likesCount' и 'dislikesCount'
        ];
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