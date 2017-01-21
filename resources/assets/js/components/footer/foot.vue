<template>
  <foot-sign></foot-sign>

  <!--<div>-->
	<!--<foot-admin v-if="getFooter.footer_type === 'admin.login'"></foot-admin>-->
	<!--<foot-sign v-else></foot-sign>-->
  <!--</div>-->
</template>

<script>
  import footSign from './foot_sign'
  import footAdmin from './foot_admin'
  export default {
  	components: {
	  footSign,footAdmin
	}
  }
</script>
