import { defineStore } from 'pinia';
import api from '@/api';
import PostAdapter from '@/adapters/PostAdapter';
import CommentAdapter from '@/adapters/CommentAdapter';
import type { Raw } from '@/@interfaces/Raw';
export const usePostStore = defineStore('post', {
  state: () => ({
    posts: [] as PostAdapter[],
    post: {} as PostAdapter,
    comments: [] as CommentAdapter[],
    loading: false,
    commentsLoading: false,
  }),
  actions: {
    async fetchPosts() {
      this.loading = true;
      try {
        const res = await api.get('/posts');
        this.posts = res.data.map((post: Raw) => PostAdapter.fromRaw(post));
      } catch (error) {
        console.error('Ошибка при загрузке постов:', error);
      } finally {
        this.loading = false;
      }
    },
    async fetchPost(postId: number) {
      this.loading = true;
      try {
        const res = await api.get(`/posts/${postId}`);
        this.post = PostAdapter.fromRaw(res.data);
      } catch (error) {
        console.error('Ошибка при загрузке поста:', error);
      } finally {
        this.loading = false;
      }
    },
    async fetchComments(postId: number) {
      this.commentsLoading = true;
      try {
        const res = await api.get(`/posts/${postId}/comments`);
        this.comments = res.data.map((comment: Raw) => CommentAdapter.fromRaw(comment));
      } catch (error) {
        console.error('Ошибка при загрузке комментариев:', error);
      } finally {
        this.commentsLoading = false;
      }
    },
    async addComment(content: string, userId: number, postId: number) {
      try {
        const res = await api.post('/comments', {
          content,
          user_id: userId,
          post_id: postId,
        });
        this.comments.push(CommentAdapter.fromRaw(res.data));
      } catch (error) {
        console.error('Ошибка при добавлении комментария:', error);
      }
    },
    async addReply(content: string, userId: number, postId: number, parentId: number) {
      try {
        const res = await api.post('/comments', {
          content,
          user_id: userId,
          post_id: postId,
          parent_id: parentId,
        });
        this.addCommentToParent(CommentAdapter.fromRaw(res.data), parentId);
      } catch (error) {
        console.error('Ошибка при добавлении ответа:', error);
      }
    },
    addCommentToParent(newComment: CommentAdapter, parentId: number) {
      const addComment = (commentsList: CommentAdapter[], parentId: number) => {
        for (const comment of commentsList) {
          if (comment.id === parentId) {
            comment.children.push(newComment);
            return true;
          }
          if (comment.children && comment.children.length > 0) {
            if (addComment(comment.children, parentId)) {
              return true;
            }
          }
        }
        return false;
      };

      addComment(this.comments, parentId);
    },
    async votePost(postId: number, value: number) {
      try {
        await api.post(`/posts/${postId}/vote`, { value });
      } catch (error) {
        console.error('Ошибка при голосовании:', error);
      }
    },
    async fetchVotes(postId: number) {
      try {
        const res = await api.get(`/posts/${postId}/votes`);
        if (this.post) {
          this.post.likes = res.data.likes;
          this.post.dislikes = res.data.dislikes;
        }
      } catch (error) {
        console.error('Ошибка при получении голосов:', error);
      }
    },
  },
});