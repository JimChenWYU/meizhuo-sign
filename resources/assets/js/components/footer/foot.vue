<template>
  <div>
	<foot-sign v-if="type === 'sign'"></foot-sign>
	<foot-admin v-if="type === 'admin'"></foot-admin>
  </div>
</template>

<script>
  import footSign from './foot_sign'
  import footAdmin from './foot_admin'
  export default {
  	props: [ 'type' ],
  	components: {
	  footSign,footAdmin
	},
  }
</script>
