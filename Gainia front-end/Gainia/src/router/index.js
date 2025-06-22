import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/auth/LoginView.vue'),
    },
    {
      path: '/logout',
      name: 'logout',
      component: () => import('../views/auth/LogOutView.vue'),
    },
    {
      path: '/create-account',
      name: 'create-account',
      component: () => import('../views/auth/CreateAccountView.vue'),
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('../views/DashboardView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/admin',
      name: 'admin',
      component: () => import('../views/Admin/AdminDashboardView.vue'),
      meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
      path: '/exercises',
      name: 'exercises',
      component: () => import('../views/Exercises/ExercisesView.vue'),
    },
    {
      path: '/goals',
      name: 'goals',
      component: () => import('../views/Goals/GoalsView.vue'),
    },
    {
      path: '/progress',
      name: 'progress',
      component: () => import('../views/Progress/ProgressView.vue'),
    },
    {
      path: '/personalrecords',
      name: 'personalrecords',
      component: () => import('../views/Pr/PersonalRecordsView.vue'),
    },
    {
      path: '/contact',
      name: 'contact',
      component: () => import('../views/ContactView.vue'),
    },
    {
      path: '/exercises/edit/:exerciseId',
      name: 'ExercisesEdit',
      component: () => import('../views/Exercises/ExercisesEdit.vue'),
      props: true,
    },
    {
      path: '/exercises/add',
      name: 'ExercisesAdd',
      component: () => import('../views/Exercises/ExercisesAdd.vue'),
    },
    {
      path: '/goals/add',
      name: 'GoalsAdd',
      component: () => import('../views/Goals/AddGoal.vue'),
    },
    {
      path: '/workouts/:id',
      name: 'workout-details',
      component: () => import('../views/Workouts/WorkoutDetailView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/workouts/edit/:id',
      name: 'edit-workout',
      component: () => import('../views/Workouts/EditWorkoutView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/workouts/add',
      name: 'add-workout',
      component: () => import('../views/Workouts/AddWorkoutView.vue'),
      meta: { requiresAuth: true }
    },
  ],
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  const isAuthenticated = authStore.isAuthenticated;
  const userRole = authStore.user?.role;

  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const requiresAdmin = to.matched.some(record => record.meta.requiresAdmin);

  if (requiresAuth && !isAuthenticated) {
    next({ name: 'login' });
  } else if (requiresAdmin && userRole !== 'admin') {
    next({ name: 'dashboard' });
  } else if ((to.name === 'login' || to.name === 'create-account') && isAuthenticated) {
    next({ name: 'dashboard' });
  } else {
    next();
  }
});

export default router
