import type { Raw } from '@/@interfaces/Raw';

export default class ProfileAdapter {
  public id?: number;
  public name: string;
  public email: string;
  public avatar: string;
  public bio: string;
  public website: string;
  public password: string;
  public passwordConfirmation: string;

  constructor(raw: Raw) {
    this.id = Number(raw['id']);
    this.name = String(raw['name']);
    this.email = String(raw['email']);
    this.avatar = String(raw['avatar']);
    this.bio = String(raw['bio']);
    this.website = String(raw['website']);
    this.password = String(raw['password']);
    this.passwordConfirmation = String(raw['password_confirmation']);
  }

  static toRaw(user: ProfileAdapter): Raw {
    const raw: Raw = {};

    raw['name'] = user.name || undefined    ;
    raw['email'] = user.email || undefined;
    raw['avatar'] = user.avatar || undefined;
    raw['bio'] = user.bio || undefined;
    raw['website'] = user.website || undefined;
    raw['password'] = user.password || undefined;
    raw['password_confirmation'] = user.passwordConfirmation || undefined;

    return raw;
  }

  static fromRaw(raw: Raw): ProfileAdapter {
    return new ProfileAdapter(raw);
  }
}