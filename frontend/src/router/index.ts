import { createRouter, createWebHistory } from 'vue-router';
import { RouteName, RouteNames } from '@/router/routes';
import HomePage from '@/views/HomePage';
import UserPage from '@/views/UserPage';
import PostPage from '@/views/PostPage';
import PostsList from '@/views/PostsList';
import UsersList from '@/views/UsersList';
import RegisterPage from '@/views/RegisterPage';
import LoginPage from '@/views/LoginPage';
import ProfileView from '@/views/ProfileView';
import ProfileEdit from '@/views/ProfileEdit';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: RouteNames[RouteName.HOME],
      name: RouteName.HOME,
      component: HomePage,
    },
    {
      path: RouteNames[RouteName.USER],
      name: RouteName.USER,
      component: UserPage,
    },
    {
      path: RouteNames[RouteName.POST],
      name: RouteName.POST,
      component: PostPage,
    },
    {
      path: RouteNames[RouteName.POSTS],
      name: RouteName.POSTS,
      component: PostsList,
    },
    {
      path: RouteNames[RouteName.USERS],
      name: RouteName.USERS,
      component: UsersList,
    },
    {
      path: RouteNames[RouteName.REGISTER],
      name: RouteName.REGISTER,
      component: RegisterPage,
    },
    {
      path: RouteNames[RouteName.LOGIN],
      name: RouteName.LOGIN,
      component: LoginPage,
    },
    {
      path: RouteNames[RouteName.PROFILE],
      name: RouteName.PROFILE,
      component: ProfileView,
      meta: { requiresAuth: true },
    },
    {
      path: RouteNames[RouteName.EDIT_PROFILE],
      name: RouteName.EDIT_PROFILE,
      component: ProfileEdit,
      meta: { requiresAuth: true },
    },
  ],
});

router.beforeEach((to, from, next) => {
  const requiresAuth = to.meta.requiresAuth;
  const token = localStorage.getItem('authToken');

  if (requiresAuth && !token) {
    next({ name: RouteName.LOGIN });
  } else {
    next();
  }
});

export default router;
