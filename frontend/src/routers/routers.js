import { createRouter , createWebHistory } from "vue-router";

import axios from "axios";
import domain from "../services/domain";

const session = async ()=>{
    const tokens = localStorage.getItem('tokens');
    const response = await axios.get(`${domain}/api/users/session/${tokens}`);
    if(response.status == 200){
        return { isAuth: response.data.isAuth , isRole: response.data.isRole };
    }
}
const { isAuth , isRole } = session();

const routes = [
    { 
        path: '/dean/dashboard',
        component: ()=>import('../components/dashboard.vue'),
        meta: { requiresAuth: true, role: "dean" },
        beforeEnter: async (to, from, next) => {
            const { isAuth, isRole } = await session();
            if (isAuth) {
              if (isRole === "dean") {
                next(); // Redirect to the dean dashboard
              } else {
                next('/dashboard'); // Redirect to the admin dashboard
              }
            } else {
              next('/login'); // Continue to the login route
            }
        },
        children: [
            { path: '', component: ()=>import('../components/dean/home.vue') },
            { path: 'configure/ranks', component: ()=>import('../components/dean/configureRanks.vue') },
            { path: 'configure/years', component: ()=>import('../components/dean/configureYears.vue') },
            { path: 'configure/departments', component: ()=>import('../components/dean/configureDepartments.vue') },
            { path: 'configure/designation', component: ()=>import('../components/dean/configureDesignation.vue') },
            { path: 'configure/specialization', component: ()=>import('../components/dean/configureSpecialization.vue') },
            { path: 'account/admins', component: ()=>import('../components/dean/accountAdmin.vue') },
            { path: 'school/professors', component: ()=>import('../components/dean/schoolProfessor.vue') },
            { path: 'school/classrooms', component: ()=>import('../components/dean/schoolClassroom.vue') },
            { path: 'school/subjects', component: ()=>import('../components/dean/schoolSubject.vue') },
            { path: 'school/sections', component: ()=>import('../components/dean/schoolSection.vue') },
            { path: 'setting', component: ()=>import('../components/partials/setting-component/setting.vue') }
        ]
    },
    {
        path: '/dashboard',
        component: ()=>import('../components/dashboardAdmin.vue'),
        meta: { requiresAuth: true, role: "admin" },
        beforeEnter: async (to, from, next) => {
            const { isAuth, isRole } = await session();
            if (isAuth) {
              if (isRole === "dean") {
                next('/dean/dashboard'); // Redirect to the dean dashboard
              } else {
                next(); // Redirect to the admin dashboard
              }
            } else {
              next('/login'); // Continue to the login route
            }
        },
        children: [
            { path: '', component: ()=>import('../components/admin/manually.vue') },
            { path: 'manually', component: ()=>import('../components/admin/manually.vue') },
            { path: 'automated', component: ()=>import('../components/admin/automated.vue') },
            { path: 'official-time', component: ()=>import('../components/admin/officialTime.vue') },
            { path: 'minor', component: ()=>import('../components/admin/minor.vue') },
            { path: 'setting', component: ()=>import('../components/partials/setting-component/setting.vue') }
        ]
    },
    { 
        path: '/login',
        component: ()=>import('../components/login.vue'),
        beforeEnter: async (to, from, next) => {
            const { isAuth, isRole } = await session();
            if (isAuth) {
              if (isRole === "dean") {
                next('/dean/dashboard'); // Redirect to the dean dashboard
              } else {
                next('/dashboard'); // Redirect to the admin dashboard
              }
            } else {
              next(); // Continue to the login route
            }
        }
    },
    {   path: '/',
        component: ()=>import('../components/index.vue'),
        beforeEnter: async (to, from, next) => {
            const { isAuth, isRole } = await session();
            if (isAuth) {
                if (isRole === "dean") {
                    next('/dean/dashboard'); // Redirect to the dean dashboard
                } else {
                    next('/dashboard'); // Redirect to the admin dashboard
                }
            } else {
                next(); // Continue to the login route
            }
        } 
    },
    { path: '/schedule', component: ()=>import('../components/partials/public-component/public-schedule-holder.vue') },
    {
      path: '/print/:id/:semester',
      name: 'PrintPage',
      component: ()=>import('../components/partials/print-component/print.vue') ,
      props: true, // To pass route params as props to the component
    },
    { path: '/:pathMatch(.*)*', component: ()=>import('../components/notFound.vue')}, 
]

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach( async (to, from, next) => {
    const {isAuth , isRole} = await session();
    if(to.meta.requiresAuth){
        if(isAuth){
            next()
        }
        else{
            next('/login')
        }
    }
    else{
        next()
    }
});

export default router;