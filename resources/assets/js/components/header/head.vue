<template>
  <nav class="header-tab">
	<head-sign v-if="getHeader.header_type === 'user.sign'" :title="getHeader.header_title"></head-sign>
	<head-sign v-if="getHeader.header_type === 'admin.login'" :title="getHeader.header_title"></head-sign>
	<head-nav v-if="getHeader.header_type === 'admin.show'" :title="getHeader.header_title"></head-nav>
  </nav>
</template>

<style lang="sass" scoped>
  .header-tab {
	position: relative;
	padding-bottom: 25px;
  }
</style>

<script>
  import { mapGetters } from 'vuex'
  import headSign from './head_sign'
  import headNav from './head_nav'
  export default{
	computed: {
	  ...mapGetters([
	    'getHeader'
	  ])
	},
	components:{
	  headSign,headNav
	}
  }
</script>
