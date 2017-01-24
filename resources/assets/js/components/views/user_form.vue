<template>
  <md-layout md-column md-gutter class="container-form">
	<k-head></k-head>

	<md-layout md-gutter>
	  <md-layout md-flex="20" md-hide-xsmall></md-layout>
	  <md-layout md-flex="10"></md-layout>
	  <md-layout md-column>
		<k-sign></k-sign>
	  </md-layout>
	  <md-layout md-flex="10"></md-layout>
	  <md-layout md-flex="20" md-hide-xsmall></md-layout>
	</md-layout>

	<k-footer></k-footer>
  </md-layout>
</template>
<style lang='sass' scoped>
  .container-form {
	min-height: 100%;
  }
</style>
<script>
  import kHead from '../header/head'
  import kFooter from '../footer/foot'
  import kSign from '../module/form'
  export default {
	components: {
	  kHead,kFooter,kSign
	}
  }
</script>
