import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import User from '@/models/User'
import ProjectSidebar from '@/layouts/ProjectSidebar.vue'
import FrontendEditorSidebar from '@/layouts/FrontendEditorSidebar.vue'
import TablesSidebar from '@/components/TablesSidebar.vue'
import EditorSidebar from '@/components/EditorSidebar.vue'

const routes: Array<RouteRecordRaw> = [
    {
        path: '/',
        name: 'home',
        redirect: { name: 'platform' },
    },
    {
        path: '/about',
        name: 'about',
        component: () =>
            import(/* webpackChunkName: "about" */ '../views/AboutView.vue'),
    },
    {
        path: '/platform',
        name: 'platform',
        redirect: { name: 'recent' },
        component: () =>
            import(
                /* webpackChunkName: "platform" */ '../layouts/AppLayout.vue'
            ),
        beforeEnter: (to, from, next) => {
            if (User.isAuthorized()) {
                next()
            } else {
                next({ name: 'auth-home' })
            }
        },
        children: [
            {
                path: 'profile',
                name: 'profile',
                component: () =>
                    import(
                        /* webpackChunkName: "profile" */ '../views/ProfileView.vue'
                    ),
            },
            {
                path: 'shared',
                name: 'sharedProjects',
                component: () =>
                    import(
                        /* webpackChunkName: "sharedProjects" */ '../views/SharedProjectsView.vue'
                    ),
            },
            {
                path: 'likes',
                name: 'likedAssets',
                component: () =>
                    import(
                        /* webpackChunkName: "sharedProjects" */ '../views/LikedAssetsView.vue'
                        ),
            },
            {
                path: 'onboarding',
                name: 'onboarding',
                component: () =>
                    import(
                        /* webpackChunkName: "onboarding" */ '@/views/OnboardingView.vue'
                    ),
                meta: {
                    sidebar: false,
                },
            },
            {
                path: 'recent',
                name: 'recent',
                component: () =>
                    import(
                        /* webpackChunkName: "recent" */ '../views/RecentView.vue'
                    ),
            },
            {
                path: '404',
                name: '404',
                component: () =>
                    import(
                        /* webpackChunkName: "404" */ '../views/404View.vue'
                    ),
            },
            {
                path: 'test-editor',
                name: 'test-editor',
                component: () =>
                    import(
                        /* webpackChunkName: "test-editor" */ '../views/TestEditorView.vue'
                    ),
                meta: {
                    sidebar: EditorSidebar,
                    header: EditorSidebar,
                },
            },
            {
                path: 'test-frontend',
                name: 'test-frontend',
                component: () =>
                    import(
                        /* webpackChunkName: "test-editor" */ '../views/FrontendEditorView.vue'
                    ),
                meta: {
                    sidebar: FrontendEditorSidebar,
                },
            },
            {
                path: 'marketplace',
                name: 'marketplace',
                redirect: { name: 'marketplaceAssets' },
                children: [
                    {
                        path: 'assets',
                        name: 'marketplaceAssets',
                        component: () =>
                            import(
                                /* webpackChunkName: "marketplaceAssets" */ '../views/marketplace/AssetsListView.vue'
                            ),
                    },
                    {
                        path: 'assets/:id',
                        name: 'asset',
                        component: () =>
                            import(
                                /* webpackChunkName: "asset" */ '../views/marketplace/AssetView.vue'
                            ),
                    },
                ],
            },
            {
                path: 'projects',
                name: 'projects',
                component: () =>
                    import(
                        /* webpackChunkName: "projects" */ '../views/ProjectsView.vue'
                    ),
            },
            {
                path: 'projects/archived',
                name: 'archivedProjects',
                component: () =>
                    import(
                        /* webpackChunkName: "projects" */ '../views/ArchivedProjectsView.vue'
                    ),
            },
            {
                path: 'projects/:project/invitation',
                name: 'projectInvite',
                component: () =>
                    import(
                        /* webpackChunkName: "projectInvite" */ '../views/ProjectInviteSelectView.vue'
                    ),
            },
            {
                path: 'projects/create',
                name: 'createProject',
                component: () =>
                    import(
                        /* webpackChunkName: "createProject" */ '../views/ProjectCreateView.vue'
                    ),
            },
            {
                path: 'projects/:project',
                name: 'project',
                redirect: { name: 'projectBackend' },
                component: () =>
                    import(
                        /* webpackChunkName: "project" */ '../views/ProjectView.vue'
                    ),
                meta: {
                    sidebar: ProjectSidebar,
                },
                children: [
                    {
                        path: 'team/invite',
                        name: 'projectTeamInvite',
                        component: () =>
                            import(
                                /* webpackChunkName: "projectTeam" */ '../views/TeamInviteView.vue'
                            ),
                    },
                    {
                        path: 'team',
                        name: 'projectTeam',
                        component: () =>
                            import(
                                /* webpackChunkName: "projectTeam" */ '../views/ProjectTeamView.vue'
                            ),
                    },
                    {
                        path: 'backend',
                        name: 'projectBackend',
                        component: () =>
                            import(
                                /* webpackChunkName: "projectBackend" */ '../views/ProjectBackendView.vue'
                            ),
                    },
                    {
                        path: 'storage',
                        name: 'projectStorage',
                        component: () =>
                            import(
                                /* webpackChunkName: "projectStorage" */ '../views/ProjectStorageView.vue'
                            ),
                    },
                    {
                        path: 'tables',
                        name: 'tables',
                        component: () =>
                            import(
                                /* webpackChunkName: "tables" */ '../views/project/DatabaseTableListView.vue'
                            ),
                    },
                    {
                        path: 'tables/:table',
                        name: 'table',
                        component: () =>
                            import(
                                /* webpackChunkName: "table" */ '../views/project/DatabaseTableView.vue'
                            ),
                        meta: {
                            sidebar: TablesSidebar,
                        },
                    },
                    {
                        path: 'methods',
                        name: 'methods',
                        component: () =>
                            import(
                                /* webpackChunkName: "methods" */ '../views/MethodsView.vue'
                            ),
                    },
                    {
                        path: 'endpoints',
                        name: 'endpoints',
                        component: () =>
                            import(
                                /* webpackChunkName: "endpoints" */ '../views/EndpointsView.vue'
                            ),
                    },
                    {
                        path: 'methods/create',
                        name: 'methodCreate',
                        component: () =>
                            import(
                                /* webpackChunkName: "methodCreate" */ '../views/MethodCreateView.vue'
                            ),
                        meta: {
                            sidebar: EditorSidebar,
                        },
                    },
                    {
                        path: 'endpoints/create',
                        name: 'endpointCreate',
                        component: () =>
                            import(
                                /* webpackChunkName: "endpointCreate" */ '../views/EndpointCreateView.vue'
                            ),
                        meta: {
                            sidebar: EditorSidebar,
                        },
                    },
                    {
                        path: 'methods/:method',
                        name: 'method',
                        component: () =>
                            import(
                                /* webpackChunkName: "method" */ '../views/MethodView.vue'
                            ),
                        meta: {
                            sidebar: EditorSidebar,
                        },
                    },
                    {
                        path: 'endpoints/:endpoint',
                        name: 'endpoint',
                        component: () =>
                            import(
                                /* webpackChunkName: "endpoint" */ '../views/EndpointView.vue'
                            ),
                        meta: {
                            sidebar: EditorSidebar,
                        },
                    },
                ],
            },
        ],
    },
    {
        path: '/auth',
        name: 'auth',
        component: () =>
            import(/* webpackChunkName: "auth" */ '../views/AuthView.vue'),
        beforeEnter: (to, from, next) => {
            if (User.isAuthorized()) {
                console.log('token exists, redirecting to platform')
                next({ name: 'platform' })
            } else {
                next()
            }
        },
        children: [
            {
                path: '',
                name: 'auth-home',
                redirect: { name: 'login' },
            },
            {
                path: 'login',
                name: 'login',
                component: () =>
                    import(
                        /* webpackChunkName: "login" */ '../views/LoginView.vue'
                    ),
            },
            {
                path: 'register',
                name: 'register',
                component: () =>
                    import(
                        /* webpackChunkName: "register" */ '../views/RegisterView.vue'
                    ),
            },
            {
                path: 'reset',
                name: 'reset',
                component: () =>
                    import(
                        /* webpackChunkName: "reset" */ '../views/ResetView.vue'
                    ),
            },
        ],
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        redirect: { name: '404' },
    },
    {
        path: '/debug',
        name: 'debug',
        redirect: { name: 'frontendbuilder' },
        children: [
            {
                path: 'frontendbuilder',
                name: 'frontendbuilder',
                component: () =>
                    import(
                        /* webpackChunkName: "frontendBuilder" */ '../views/FrontendEditorView.vue'
                    ),
            },
            {
                path: 'renderedfrontend',
                name: 'renderedfrontend',
                component: () =>
                    import(
                        /* webpackChunkName: "renderedfrontend" */ '../views/RenderedFrontendView.vue'
                    ),
            },
        ],
    },
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
})

export default router
