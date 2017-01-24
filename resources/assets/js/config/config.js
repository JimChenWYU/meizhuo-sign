/**
 * Created by lenovo on 2017/01/20.
 */

export default {
  debug: process.env.NODE_ENV !== 'production',
  baseUrl: `${window.location.protocol}//${window.location.host}`,

  userSign: '/user',
  adminLogin: '/admin',
  adminLogout: '/admin/out',
  adminPermission: '/admin/permission',
  adminSigners: '/admin/signers',
  adminSigner: '/admin/signer'
}