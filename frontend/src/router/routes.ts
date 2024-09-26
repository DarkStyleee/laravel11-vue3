export enum RouteName {
  HOME = 'home',
  USER = 'user',
  POST = 'post',
  USERS = 'users',
  POSTS = 'posts',
  REGISTER = 'register',
  LOGIN = 'login',
  PROFILE = 'profile',
  EDIT_PROFILE = 'editProfile',
}

export const RouteNames: Record<RouteName, string> = {
  [RouteName.HOME]: '/',
  [RouteName.USER]: '/user/:id',
  [RouteName.POST]: '/post/:id',
  [RouteName.USERS]: '/users',
  [RouteName.POSTS]: '/posts',
  [RouteName.REGISTER]: '/register',
  [RouteName.LOGIN]: '/login',
  [RouteName.PROFILE]: '/profile',
  [RouteName.EDIT_PROFILE]: '/profile/edit',
};
