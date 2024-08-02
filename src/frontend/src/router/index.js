import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import DashBoardView from '@/views/DashboardView.vue'
import SignUpView from '@/views/SignUpView.vue'
import UserProfileView from '@/views/UserProfileView.vue'
import UserUpdateView from '@/views/UserUpdateView.vue'
import AllUsersView from '@/views/AllUsersView.vue'
import EditUserView from '@/views/EditUserView.vue'
import LoginView from '@/views/LoginView.vue'
import AllCoursesView from '@/views/CoursesView.vue'
import CreateCourseView from '@/views/CreateCourseView.vue'
import UpdateCourseView from '@/views/UpdateCourseView.vue';
import AdminCoursesView from '@/views/AdminCoursesView.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'index',
      alias: '/index',
      component: HomeView,
      meta: {
        hideNavbar: true,
       }
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashBoardView
    },
    {
      path: '/signup',
      name: 'signup',
      component: SignUpView,
      meta: { requiresAuth: false, requiresAdmin: false, hideNavbar: true}
    },
    {
      path: '/profile',
      name: 'profile',
      component: UserProfileView
    },
    {
      path: '/update',
      name: 'update',
      component: UserUpdateView
    },
    {
      path: '/users',
      name: 'users',
      component: AllUsersView,
      meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
      path: '/edit-user/:id',
      name: 'edit-user',
      component: EditUserView,
      meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: {
        hideNavbar: true,
       }
    },
    {
      path: '/courses',
      name: 'listCourses',
      component: AllCoursesView
    },
    {
      path: '/courses/create',
      name: 'createCourse',
      component: CreateCourseView
    },
    {
      path: '/courses/:id/edit',
      name: 'updateCourse',
      component: UpdateCourseView,
      props: true
    },
    {
      path: '/admin/courses',
      name: 'adminCourses',
      component: AdminCoursesView
    }
  ]
})

router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('authToken');
  const userRole = localStorage.getItem('userRole'); 
  
  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'login' });
  } else if (to.meta.requiresAdmin && userRole !== 'admin') {
    next({ name: 'dashboard' }); 
  } else {
    next();
  }
})

export default router
