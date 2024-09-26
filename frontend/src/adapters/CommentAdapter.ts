import type { Raw } from '@/@interfaces/Raw';
import type { Id } from '@/@types/IdentifiableItem';
import { Identifier } from '@/utils/adapterUtilities';
import UserAdapter from '@/adapters/UserAdapter'; // Предполагается, что у вас есть адаптер для пользователя

export default class CommentAdapter {
  public id: Id;
  public content: string;
  public userId: Id;
  public postId: Id;
  public createdAt: Date;
  public updatedAt: Date;
  public parentId: Id | null;
  public children: CommentAdapter[];
  public user: UserAdapter | null;

  constructor(raw: Raw) {
    this.id = Identifier(raw['id']);
    this.content = String(raw['content']);
    this.userId = Identifier(raw['user_id']);
    this.postId = Identifier(raw['post_id']);
    this.createdAt = new Date(raw['created_at']);
    this.updatedAt = new Date(raw['updated_at']);
    this.parentId = raw['parent_id'] ? Identifier(raw['parent_id']) : null;
    this.children = Array.isArray(raw['children'])
      ? raw['children'].map((child: Raw) => new CommentAdapter(child))
      : raw['children']
      ? [new CommentAdapter(raw['children'])]
      : [];
    this.user = raw['user'] ? new UserAdapter(raw['user']) : null;
  }

  static toRaw(comment: CommentAdapter): Raw {
    const raw: Raw = {};

    raw['id'] = comment.id;
    raw['content'] = comment.content;
    raw['user_id'] = comment.userId;
    raw['post_id'] = comment.postId;
    raw['created_at'] = comment.createdAt.toISOString();
    raw['updated_at'] = comment.updatedAt.toISOString();
    raw['parent_id'] = comment.parentId;
    raw['children'] = comment.children.map(child => CommentAdapter.toRaw(child));
    raw['user'] = comment.user ? UserAdapter.toRaw(comment.user) : null;

    return raw;
  }

  static fromRaw(raw: Raw): CommentAdapter {
    return new CommentAdapter(raw);
  }
}