import type { Raw } from '@/@interfaces/Raw';
import type { Id } from '@/@types/IdentifiableItem';
import { Identifier } from '@/utils/adapterUtilities';// Предполагается, что у вас есть адаптер для пользователя

export default class UserAdapter {
  public id: Id;
  public name: string;
  public email: string;

  constructor(raw: Raw) {
    this.id = Identifier(raw['id']);
    this.name = String(raw['name']);
    this.email = String(raw['email']);
  }

  static toRaw(user: UserAdapter): Raw {
    const raw: Raw = {};

    raw['id'] = user.id;
    raw['name'] = user.name;
    raw['email'] = user.email;


    return raw;
  }

  static fromRaw(raw: Raw): UserAdapter {
    return new UserAdapter(raw);
  }
}