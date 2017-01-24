<template>
  <md-toolbar>
  	<h3 class="md-title" style="flex: 1">{{title}}</h3>
  </md-toolbar>
</template>

<style lang="sass" scoped>
  .md-title {
  	text-align: center;
  }
</style>

<script>
  export default {
	props: [ 'title' ]
  }
</script>
