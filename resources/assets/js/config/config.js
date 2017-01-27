/**
 * Created by lenovo on 2017/01/20.
 */

export default {
  debug: process.env.NODE_ENV !== 'production',
  baseUrl: `${window.location.protocol}//${window.location.host}`,
  socket: 'ws://localhost:6001',

  userSign: '/user',
  adminLogin: '/admin',
  adminLogout: '/admin/out',
  adminPermission: '/admin/permission',
  adminSigners: '/admin/signers',
  adminSigner: '/admin/signer',
  adminDepartment: '/admin/department',

  adminQueue: '/admin/queue',
  adminSign: '/admin/sign',
}