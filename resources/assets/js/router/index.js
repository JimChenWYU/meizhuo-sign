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
      path: '/user',
      component: require('../App'),
      children: user
    },
    {
      path: '/admin',
      component: require('../App'),
      children: admin
    },
    {
      path: '/test',
      component: require('../components/test'),
    },
    {
      path: '*',
      redirect:'/user'
    }
  ]
});

export default router;