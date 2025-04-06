import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

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
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
    },
    {
      path: '/logout',
      name: 'logout',
      component: () => import('../views/LogoutView.vue'),
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('../views/DashboardView.vue'),
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
  ],
})

export default router
