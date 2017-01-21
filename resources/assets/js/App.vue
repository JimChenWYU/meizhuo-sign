<template>
  <transition name="fade">
  	<router-view></router-view>
  </transition>
</template>

<style lang="sass">
  * {
	margin: 0;
	padding: 0;
  }

  html {
	height: 100%;
	body {
	  margin: 0;
	  padding: 0;
	  width: 100%;
	  line-height: 1.5;
	  font-family: Roboto,Noto Sans, Noto,sans-serif;
	}
  }
</style>

<script>
  export default{
	name: 'app',
	created () {
	}
  }
</script>
