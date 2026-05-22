import { createRouter, createWebHistory } from 'vue-router';
import DashboardView from './views/DashboardView.vue';
import CreateCaseView from './views/CreateCaseView.vue';
import CaseDetailView from './views/CaseDetailView.vue';

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: DashboardView,
        meta: { title: 'Ärenden' },
    },
    {
        path: '/cases/new',
        name: 'create-case',
        component: CreateCaseView,
        meta: { title: 'Nytt ärende' },
    },
    {
        path: '/cases/:id',
        name: 'case-detail',
        component: CaseDetailView,
        meta: { title: 'Ärendedetalj' },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior() {
        return { top: 0 };
    },
});

router.afterEach((to) => {
    const pageTitle = to.meta.title ?? 'Care Intake Dashboard';
    document.title = `${pageTitle} – Care Intake Dashboard`;
});

export default router;
