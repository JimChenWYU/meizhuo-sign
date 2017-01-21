/**
 * Created by lenovo on 2017/01/18.
 */
const admin = [
  {
    name: 'admin.login',
    path: '/',
    component: resolve => {
      require(['../components/views/admin_login'], resolve)
    }
  },
  {
    name: 'admin.show',
    path: '/admin/show',
    component: resolve => {
      require(['../components/views/admin_list'], resolve)
    }
  }
];

export default admin;