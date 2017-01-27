<template>
  <md-layout md-gutter>
	<md-layout md-flex="20" md-hide-xsmall></md-layout>
	<md-layout md-flex="10"></md-layout>
	<md-layout md-column>
	  <form novalidate @submit.stop.prevent="submit()" class="content">
		<md-input-container :class="{ 'md-input-invalid' : errors.account }">
		  <md-icon md-theme="brown" class="md-primary">people</md-icon>
		  <label>用户名</label>
		  <md-input id="account"
					name="account"
					type="text"
					v-focus
					v-model.trim="account"></md-input>
		  <span class="md-error"
				v-for="(error, index) of errors.account"
				v-if="index === 0">{{error}}</span>
		</md-input-container>

		<md-input-container :class="{ 'md-input-invalid' : errors.password }">
		  <md-icon>speaker_notes</md-icon>
		  <label>密码</label>
		  <md-input id="password"
					name="password"
					type="password"
					v-model.trim="password"></md-input>
		  <span class="md-error"
				v-for="(error, index) of errors.password"
				v-if="index === 0">{{error}}</span>
		</md-input-container>

		<md-layout md-column>
		  <md-button class="md-raised md-primary" type="submit" md-fab-bottom-center>确定</md-button>
		</md-layout>

		<md-spinner :md-size="100"
					:md-stroke="1.5"
					md-indeterminate
					v-show="isLoading"
					class="spinner"></md-spinner>

		<md-dialog-alert
				:md-content="alert.content"
				:md-ok-text="alert.ok"
				ref="login-tip">
		</md-dialog-alert>
	  </form>
	</md-layout>
	<md-layout md-flex="10"></md-layout>
	<md-layout md-flex="20" md-hide-xsmall></md-layout>
  </md-layout>
</template>

<style lang="sass" scoped>
  .content {
	padding-top: 50px;
	text-align: center;
	display: inline-block;
  }
  .spinner {
  	position: absolute;
  	margin: auto;
  	left: 0;
  	right: 0;
  }
</style>

<script type="es6">
  import { mapActions, mapGetters } from 'vuex'
  export default{
    created () {
      this.setHeader({
      	title: '管理后台登录',
      	type: 'admin.login'
      })

	  this.getAdmin.setToken(null)
    },

	data(){
	  return{
	  	alert: {
	  	  content: 'tip',
	  	  ok: '确定'
	  	},
		account: 'hr',
		password: '123',
		isLoading: false
	  }
	},

	vuerify: {
	  account: [ 'required' ],
	  password: [ 'required' ]
	},

	computed: {
	  ...mapGetters([ 'getAdmin' ]),

	  adminData () {
		return {
		  account: this.account,
		  password: this.password
		}
	  },

	  errors () {
		return this.$vuerify.$errors
	  },

	  validate() {
	  	let isPass = this.$vuerify.check()
	  	if (! isPass) {
	  	  // console.log(this.$vuerify)
	  	}
	  	return isPass
	  }
	},

	methods: {

	  ...mapActions(['setHeader', 'setBody', 'setAdmin']),

	  setLoading () {
	  	this.isLoading = !this.isLoading
	  },

	  submit() {
	  	if (this.validate) {
		  this.setLoading()
		  this.login()
	  	}
	  },

	  login () {
	  	this.$http.post(this.$env.adminLogin, this.adminData)
	  		.then(response => {
			  let res = response.data
			  //console.log(res)
			  this.setLoading()
			  this.setAdmin({
			  	token: res.data.token
			  })
			  this.$router.replace(res.data.redirect)
	  		})
	  		.catch(error => {
			  this.setLoading()
			  this.openDialog('login-tip', error.msg)
	  		})
	  },

	  openDialog(ref, msg) {
	  	this.alert.content = msg
      	this.$refs[ref].open()
	  },
	  closeDialog(ref) {
		this.$refs[ref].close()
	  }
	}
  }
</script>
