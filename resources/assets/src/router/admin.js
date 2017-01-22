/**
 * Created by lenovo on 2017/01/18.
 */
const admin = [
  {
    name: 'admin.login',
    path: '/',
    component: require('../components/views/admin_login')
  },
  {
    name: 'admin.show',
    path: '/admin/show',
    component: require('../components/views/admin_list')
  }
];

export default admin;