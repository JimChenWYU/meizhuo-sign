<template>
  <md-toolbar>
	<md-button class="md-icon-button" @click="toggleLeftSidenav">
	  <md-icon>menu</md-icon>
	</md-button>

	<h2 class="md-title">{{title}}</h2>
  </md-toolbar>
</template>

<script>
  export default {
  	props: [ 'title' ],
	methods: {
	  toggleLeftSidenav() {
		this.$refs.leftSidenav.toggle();
	  },
	  open(ref) {
		console.log('Opened: ' + ref);
	  },
	  close(ref) {
		console.log('Closed: ' + ref);
	  }
	}
  };
</script>
