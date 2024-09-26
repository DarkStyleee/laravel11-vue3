import type { Raw } from '@/@interfaces/Raw';
import type { Id } from '@/@types/IdentifiableItem';
import { Identifier } from '@/utils/adapterUtilities';
import UserAdapter from './UserAdapter';

export default class PostAdapter {
  public id: Id;
  public title: string;
  public content: string;
  public userId: Id;
  public createdAt: Date;
  public updatedAt: Date;
  public views: number;
  public commentsCount: number;
  public user: UserAdapter;

  constructor(raw: Raw) {
    this.id = Identifier(raw['id']);
    this.title = String(raw['title']);
    this.content = String(raw['content']);
    this.userId = Identifier(raw['user_id']);
    this.createdAt = new Date(raw['created_at']);
    this.updatedAt = new Date(raw['updated_at']);
    this.views = Number(raw['views']);
    this.commentsCount = Number(raw['comments_count']);
    this.user = raw['user'] ? UserAdapter.fromRaw(raw['user']) : {} as UserAdapter;
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