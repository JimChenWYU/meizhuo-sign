/**
 * Created by lenovo on 2017/01/18.
 */
const admin = [
  {
    path: '/',
    component: require('../components/views/admin_login'),
    children: [
      {
        name: 'admin.login',
        path: '/admin/login',
        component: require('../components/module/login')
      },
      {
        name: 'admin.department',
        path:'/admin/department',
        component: require('../components/module/interview_form')
      },
      {
        name: 'admin',
        path: '/',
        redirect: '/admin/login'
      }
    ]
  },
  {
    name: 'admin.show',
    path: '/admin/show/',
    component: require('../components/views/admin_list'),
    children: [
      {
        name: 'admin.show.department',
        path: '/admin/show/:department',
        component: require('../components/module/table_list')
      },
      {
        name: 'admin.show.department.page',
        path: '/admin/show/:department/:page',
        component: require('../components/module/table_list')
      },
      {
        name: 'admin.show.id',
        path:'/admin/show/:id',
        component: require('../components/module/table_person_info')
      }
    ]
  },
  {
    path: '/admin/show',
    redirect: '/admin/show/all'
  }
];

export default admin;