/**
 * Created by lenovo on 2017/01/17.
 */
import Vue from 'vue'
import VueRouter from 'vue-router'
import user from './user'
import admin from './admin'

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  base: __dirname,
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    {
      path: '/',
      component: require('../App'),
      children: [
        {
          path: '/user',
          component: require('../components/layout_user'),
          children: user
        },
        {
          path: '/admin',
          component: require('../components/layout_admin'),
          children: admin
        },
        {
          path: '*',
          redirect:'/user'
        }
      ]
    }
  ]
});

export default router;