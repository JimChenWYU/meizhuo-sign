/**
 * Created by lenovo on 2017/01/18.
 */
const user = [
  {
    name: 'user.sign',
    path: '/',
    component: resolve => {
      require(['../components/views/user_form'], resolve)
    }
  }
];

export default user;