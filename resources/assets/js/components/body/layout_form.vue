<template>
  <md-layout md-column md-gutter>
	<md-layout md-column>
		<k-head :title="footer.title"></k-head>
	</md-layout>

	<md-layout md-gutter>
	  <md-layout md-flex="25" md-hide-xsmall></md-layout>
	  <md-layout md-column>
	  	<router-view></router-view>
	  </md-layout>
	  <md-layout md-flex="25" md-hide-xsmall></md-layout>
	</md-layout>

	<k-footer :type="footer.type"></k-footer>


  </md-layout>
</template>

<script>
  import kHead from '../header/head'
  import kFooter from '../footer/foot'
  export default{
	name: 'app',
	components: {
	  kHead,kFooter
	},
	created () {
	},
	data () {
	  return {
	  	footer: {
	  	  title: '',
		  type: ''
	  	}
	  }
	},
	beforeRouteEnter (to, from, next) {
	  let routeName = to.name
	  next(vm => {
		switch(routeName) {
		  case 'sign':
		  	vm.footer = {
			  title: '袂卓工作室线上报名',
		  	  type: routeName
		  	}; break;
		  case 'admin':
		  	vm.footer = {
			  title: '管理后台登录',
		  	  type: routeName
		  	}; break;
		}
	  })
	}
  }
</script>
