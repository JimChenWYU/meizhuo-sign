/**
 * Created by lenovo on 2017/01/17.
 */
import Vue from 'vue'
import VueRouter from 'vue-router'
import sign from './sign'
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
          path: '/sign',
          component: resolve => require(['../components/body/layout_form'], resolve),
          children: sign
        },
        {
          path: '/admin',
          component: resolve => require(['../components/body/layout_form'], resolve),
          children: admin
        },
        {
          path: '*',
          redirect:'/sign'
        }
      ]
    }
  ]
});

export default router;