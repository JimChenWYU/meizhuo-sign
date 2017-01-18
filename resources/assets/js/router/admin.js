/**
 * Created by lenovo on 2017/01/18.
 */
const admin = [
  {
    name: 'admin',
    path: '/',
    component: resolve => require(['../views/login'], resolve)
  },
  {
    path: '/show',
    component: resolve => require(['../views/form'], resolve)
  }
];

export default admin;