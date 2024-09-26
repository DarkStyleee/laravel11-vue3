import type { Raw } from '@/@interfaces/Raw';
import UserAdapter from './UserAdapter';

export default class PostAdapter {
  public id: number;
  public title: string;
  public content: string;
  public userId: number;
  public createdAt: Date;
  public updatedAt: Date;
  public views: number;
  public commentsCount: number;
  public user: UserAdapter;
  public likes: number;
  public dislikes: number;
  public userVote: number | null;

  constructor(raw: Raw) {
    this.id = Number(raw['id']);
    this.title = String(raw['title']);
    this.content = String(raw['content']);
    this.userId = Number(raw['user_id']);
    this.createdAt = new Date(raw['created_at']);
    this.updatedAt = new Date(raw['updated_at']);
    this.views = Number(raw['views']);
    this.commentsCount = Number(raw['comments_count']);
    this.user = raw['user'] ? UserAdapter.fromRaw(raw['user']) : {} as UserAdapter;
    this.likes = Number(raw['likes']);
    this.dislikes = Number(raw['dislikes']);
    this.userVote = raw['user_vote'] ? Number(raw['user_vote']) : null;
  }

  static toRaw(post: PostAdapter): Raw {
    const raw: Raw = {};

    raw['id'] = post.id;
    raw['title'] = post.title;
    raw['content'] = post.content;
    raw['user_id'] = post.userId;
    raw['created_at'] = post.createdAt.toISOString();
    raw['updated_at'] = post.updatedAt.toISOString();
    raw['views'] = post.views;
    raw['comments_count'] = post.commentsCount;

    return raw;
  }

  static fromRaw(raw: Raw): PostAdapter {
    return new PostAdapter(raw);
  }
}