<template>
  <div class="container">
	<md-toolbar>
	  <md-button class="md-icon-button" @click="toggleLeftSidenav">
		<md-icon>menu</md-icon>
	  </md-button>

	  <h2 class="md-title">{{getHeader.header_title}}</h2>
	</md-toolbar>

	<md-sidenav class="main-sidebar md-left md-fixed" ref="leftSidenav">
	  <md-toolbar class="md-large">
		<div class="md-toolbar-container">
		  <h3 class="md-title">Sidenav content</h3>
		</div>
	  </md-toolbar>

	  <div class="main-sidebar-links">
		<md-list class="md-double-line md-dense">
		  <md-subheader>Navigation</md-subheader>

		  <md-list-item>
			<md-icon>move_to_inbox</md-icon> <span>Inbox</span>
		  </md-list-item>

		  <md-list-item>
			<md-icon>send</md-icon> <span>Outbox</span>
		  </md-list-item>

		  <md-list-item>
			<md-icon>delete</md-icon> <span>Trash</span>
		  </md-list-item>

		  <md-list-item>
			<md-icon>error</md-icon> <span>Spam</span>

			<md-divider class="md-inset"></md-divider>
		  </md-list-item>
		</md-list>
	  </div>
	</md-sidenav>
  </div>
</template>
<style lang="sass">
  @import '../../../sass/app';

  $sizebar-size: 280px;

  .container {
	min-height: 100%;
	display: flex;
	flex-flow: column nowrap;
	flex: 1;
	transition: $swift-ease-out;
	@media (min-width: 1281px) {
	  padding-left: $sizebar-size;
	}
  }

  .main-sidebar.md-sidenav {
	.md-sidenav-content {
	  width: $sizebar-size;
	  display: flex;
	  flex-flow: column;
	  overflow: hidden;
	  @media (min-width: 1281px) {
		top: 0;
		pointer-events: auto;
		transform: translate3d(0, 0, 0);
		box-shadow: $material-shadow-2dp;
	  }
	}
	.md-backdrop {
	  @media (min-width: 1281px) {
		opacity: 0;
		pointer-events: none;
	  }
	}
	.main-sidebar-links {
	  overflow: auto;
	  flex: 1;
  	}
  }
</style>
<script>
  import { mapActions, mapGetters } from 'vuex'
  import kHead from '../header/head'
  export default{
  	component: { kHead },
    created () {
      this.setHeader({
      	title: '袂卓工作室招新后台管理',
      	type: 'admin.show'
      })
	  //console.log(this.getAuthorization)
      // this.toggleLeftSidenav()
    },
    data () {
   	  return {
   	  	msg: 'Hello World!',
   	  	isShowSideNav: false,
   	  	menus: [
   	  	  {position: 'bottom top'},
   	  	  {position: 'bottom left'},
   	  	  {position: 'top left'},
   	  	  {position: 'top right'},
   	  	]
   	  }
    },
    computed: {
      ...mapGetters([ 'getHeader', 'getAuthorization' ])
    },
    methods: {
      ...mapActions(['setHeader', 'setFooter']),
      setIsShowSideNav () {
      	this.isShowSideNav = !this.isShowSideNav
      },
      toggleLeftSidenav() {
		console.log(this.$refs)
		this.$refs.leftSidenav.toggle();
	  },
	  closeLeftSidenav() {
		this.$refs.leftSidenav.close();
	  },
      open(ref) {
		console.log('Opened: ' + ref);
	  },
	  close(ref) {
		console.log('Closed: ' + ref);
	  }
    }
  }
</script>
