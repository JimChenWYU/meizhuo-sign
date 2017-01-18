<template>
  <div id="app">
	<transition name="fade">
	  <router-view></router-view>
	</transition>
  </div>
</template>

<style>
  * {
	margin: 0;
	padding: 0;
  }

  html,body {
	height: 100%;
  }

  body {
	margin: 0;
	padding: 0;
	width: 100%;
	line-height: 1.5;
	font-family: 'webfont';
  }

</style>

<script>
  export default{
	name: 'app'
  }
</script>
