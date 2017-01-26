<template>
  <div class="container">
	<md-sidenav class="main-sidebar md-left md-fixed" ref="leftSidenav">
	  <md-toolbar class="md-large admin-list__logo">
		<div>
		  <img class="" src="../../assets/images/mz.png" alt="meizhuo logo">
		  <span>{{verifyAdmin.welcome}}
			<a href="/admin/login"
			   class="md-raised md-primary">
			  {{verifyAdmin.operate}}
			</a>
			<!--<router-link :to="verifyAdmin.skipTo"-->
						  <!--class="md-raised md-primary" replace>-->
			  <!--{{verifyAdmin.operate}}-->
		  	<!--</router-link>-->
		  </span>
		</div>
	  </md-toolbar>

	  <div class="main-sidebar-links">
		<md-list class="md-double-line md-dense">
		  <md-subheader>Navigation</md-subheader>

		  <md-list-item v-for="p of permission" v-if="p.isPermit">
			<router-link :to="{ name: 'admin.show.department', params: { department: p.name } }">
			  <md-icon>send</md-icon> <span>{{p.value}}</span>
			</router-link>
		  </md-list-item>
		</md-list>
	  </div>
	</md-sidenav>

	<router-view>
	  <md-toolbar>
		<md-button class="md-icon-button"
				   @click="toggleLeftSidenav"
				   v-display-button>
		  <md-icon>menu</md-icon>
		</md-button>

		<h2 class="md-title">{{getHeader.header_title}}</h2>
	  </md-toolbar>
	</router-view>
  </div>
</template>

<style lang="sass">
  @import '../../../sass/app.scss';

  $sizebar-size: 280px;
  .container {
	min-height: 100%;
	flex-flow: column nowrap;
	flex: 1;
	transition: $swift-ease-out;
	@media (min-width: 1281px) {
	  padding-left: $sizebar-size;
	}
  }

  .main-sidebar.md-sidenav {
	.md-toolbar {
	  min-height: 172px;
	  border-bottom: 1px solid rgba(0,0,0,.12);
	}
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
	.admin-list__logo {
	  div {
		width: 100%;
		display: flex;
		flex-flow: column;
		justify-content: center;
		align-items: center;
	  }
	  a {
		color: inherit;
	  	text-decoration: none;
	  }
	  img {
		width: 160px;
		margin-bottom: 16px;
	  }
	}
  }
</style>

<script type="es6">
  import { mapActions, mapGetters } from 'vuex'
  import kTableList from '../module/table_list'
  export default{
  	components: { kTableList },
    data () {
   	  return {
		permission: [],
		verifyAdmin: {
		  welcome: '您未登录，',
		  operate: '请登录',
		  skipTo: {
			name: 'admin.login'
		  }
		}
   	  }
    },
    computed: {
      ...mapGetters([ 'getHeader', 'getAdmin' ])
    },
    methods: {
      ...mapActions(['setHeader', 'setFooter']),

	  getPermission () {
		this.$http.get(this.$env.adminPermission, {
		  headers: {
			authorization: this.getAdmin.getToken()
		  }
		}).then(response => {
		  let res = response.data
		  // console.log(res)
		  if(res.data.admin) {
		  	this.permission = res.data.permission
			this.verifyAdmin = res.data.admin
		  }
		}).catch(error => {
		  this.$router.replace(error.data.redirect)
		})
	  },

      toggleLeftSidenav() {
		this.$refs.leftSidenav.toggle();
	  },
	  closeLeftSidenav() {
		this.$refs.leftSidenav.close();
	  },
      open(ref) {
		//console.log('Opened: ' + ref);
	  },
	  close(ref) {
		//console.log('Closed: ' + ref);
	  }
    },
	mounted() {
  	  this.$nextTick(() => {
		this.setHeader({
		  title: '袂卓工作室招新后台管理',
		  type: 'admin.show'
		})

		this.getPermission()
	  })
	},
    directives: {
      displayButton: {
		update (el) {

		  function on() {
			if (window.innerWidth <= 1281) {
			  el.style.visibility = 'visible'
			} else {
			  el.style.visibility = 'hidden'
			}
		  }

		  on()

		  window.addEventListener('resize', on)
		}
      }
    }
  }
</script>
