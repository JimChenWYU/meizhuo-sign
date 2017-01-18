<template>
  <nav class="header-tab">
	<head-sign v-if="head.toggle" :title="title"></head-sign>
	<head-nav v-else></head-nav>
  </nav>
</template>

<style scoped>
  .header-tab {
	position: relative;
	height: 100%;
	padding-bottom: 25px;
  }
</style>

<script>
  import headSign from './head_sign'
  import headNav from './head_nav'
  export default{
	props: [ 'title' ],
	data(){
	  return {
		head: {
		  toggle: true,
		}
	  }
	},
	components:{
	  headSign,headNav
	}
  }
</script>
