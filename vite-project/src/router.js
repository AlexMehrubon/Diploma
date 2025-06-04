import {createMemoryHistory, createRouter, createWebHistory} from 'vue-router'

import HomeView from '@/views/HomeView.vue';
import CoursesView from "@/views/CoursesView.vue";
import {useAuthModalStore} from "@/stores/authModalStore.js";
import {useUserStore} from "@/stores/userStore.js";
import AdminView from "@/views/AdminView.vue";
import CoursesPanel from "@/components/admin-panel/CoursesPanel.vue";
import BlogPanel from "@/components/admin-panel/BlogPanel.vue";
import UserPanel from "@/components/admin-panel/UserPanel.vue";
import ProfileView from "@/views/ProfileView.vue";
import SettingsView from "@/views/SettingsView.vue";

const routes = [
  {
    path: '/',
    component: HomeView,
    name: 'home',
    meta: {
      requiresAuth: false,
      requiresAdmin: false
    },
  },
  {
    path: '/courses',
    component: CoursesView,
    name: 'courses',
    meta: {
      requiresAuth: false,
      requiresAdmin: false
    },
  },
  {
    path: '/courses/:id/lessons',
    name: 'course-lesson',
    component: () => import('@/views/CourseLessonView.vue')
  },
  {
    path: '/courses/:id',
    component: () => import('@/views/CourseDetailView.vue'),
    name: 'course-detail',
    props: true,
    meta: {
      requiresAuth: false,
      requiresAdmin: false
    }
  },
  {
    path: '/profile',
    component: ProfileView,
    name: 'profile'
  },

  {
    path: '/profile/settings',
    component: SettingsView,
    name : 'settings'
  },
  {
    path: '/admin',
    component: AdminView,
    redirect: '/admin/courses',
    children: [
      {
        path: 'courses',
        component: CoursesPanel,
        name: 'admin.courses'
      },
      {
        path: 'users',
        component: UserPanel,
        name: 'admin.users'
      },
      {
        path: 'blogs',
        component: BlogPanel,
        name: 'admin.blogs'
      },
    ],
    meta: {
      requiresAuth: true,
      requiresAdmin: true
    },
    name: 'admin'
  }

]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore();
  const authModalStore = useAuthModalStore()

  if (!userStore.currentUser && userStore.isAuthenticated)
    await userStore.fetchCurrentUser();

  if (to.meta.requiresAuth && !userStore.isAuthenticated) {
    next('/');
    authModalStore.showModal()
  } else if (to.meta.requiresAdmin && (!userStore.isAuthenticated || (!userStore.isAdmin))) {
    next('/');
  } else {
    next();
  }
});

export default router
