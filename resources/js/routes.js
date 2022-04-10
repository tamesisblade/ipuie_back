import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
    routes: [{
            path: '/home',
            name: 'home',
            component: require('./views/Home').default
        },
        {
            path: '/evaluacion',
            name: 'evaluacion',
            component: require('./views/Evaluacion').default
        },
        {
            path: ':slug',
            name: 'post',
            component: require('./views/Post').default,
            props: true
        },
        {
            path: '*',
            component: require('./views/404').default
        }
    ],
    mode: 'history',
    scrollBehavior() {
        return { x: 0, y: 0 }
    }
})