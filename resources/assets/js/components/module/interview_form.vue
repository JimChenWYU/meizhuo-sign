<template>

  <md-layout md-gutter>
	<md-layout md-flex="20" md-hide-xsmall></md-layout>
	  <md-layout md-flex="10"></md-layout>
		<md-layout md-column>
		  <form novalidate @submit.stop.prevent="submit()" class="content">
			<div class="field-group">
			  <md-input-container :class="{ 'md-input-invalid' : errors.department }">
				<label>部门</label>
				<md-select
						name="department"
						id="department"
						v-model="department"
						required>
				  <md-option v-for="dept of l_department" :value="dept.value">{{dept.name}}</md-option>

				</md-select>
				<span class="md-error"
					  v-for="(error, index) of errors.department"
					  v-if="index === 0">{{error}}</span>
			  </md-input-container>
			  &nbsp;&nbsp;
			  <md-input-container :class="{ 'md-input-invalid' : errors.tab }">
				<label>组数</label>
				<md-select
						name="tab"
						id="tab"
						v-model="tab"
						required>
				  <md-option v-for="tab of l_tab" :value="tab.value">{{tab.name}}</md-option>
				</md-select>
				<span class="md-error"
					  v-for="(error, index) of errors.tab"
					  v-if="index === 0">{{error}}</span>
			  </md-input-container>
			</div>

			<md-layout md-column>
			  <md-button class="md-raised md-primary" type="submit" md-fab-bottom-center>确定</md-button>
			  <md-button class="md-raised md-primary" @click="log" md-fab-bottom-center>show</md-button>
			</md-layout>

			<md-spinner
					:md-size="100"
					:md-stroke="1.5"
					md-indeterminate
					v-show="isLoading"
					class="spinner">
			</md-spinner>

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
  }
  .field-group {
	display: flex;
  }
  .spinner {
	position: absolute;
	margin: auto;
	left: 0;
	right: 0;
  }
</style>

<script type="es6">
  import { mapActions } from 'vuex'
  export default {

	data(){
	  return{
		alert: {
		  content: 'tip',
		  ok: '确定'
		},
		l_department: [
		  { name: '安卓组', value: '移动组' },
		  { name: 'Web组', value: 'Web组' },
		  { name: '美工组', value: '美工组' },
		  { name: '营销策划', value: '营销策划' },
		],
		l_tab: [
		  { name: '第一组', value: 1 },
		  { name: '第二组', value: 2 },
		  { name: '第三组', value: 3 },
		],
		department: '',
		tab: '',
		isLoading: false
	  }
	},

	vuerify: {
	  department: [ 'required' ],
	  tab: [ 'required' ]
	},

	computed: {
	  adminData () {
		return {
		  department: this.department,
		  tab: this.tab
		}
	  },

	  errors () {
		return this.$vuerify.$errors
	  },

	  validate() {
		let isPass = this.$vuerify.check()
		return isPass
	  }
	},

	methods: {

	  ...mapActions(['setHeader']),

	  setLoading () {
		this.isLoading = !this.isLoading
	  },

	  submit() {
		if (this.validate) {
		  this.login()
		}
	  },

	  login () {
		this.setLoading()
		this.$http.post(this.$env.adminDepartment, this.adminData)
		  .then(response => {
			let res = response.data
			// console.log(res)
			this.setLoading()
			this.$router.push({ path: res.data.redirect })
		  })
		  .catch(error => {
			// console.log(error.data)
			this.setLoading()
			this.openDialog('login-tip', alert => {
			  alert.content = error.msg
			})
			this.$router.replace({ path: error.data.redirect })
		  })
	  },

	  openDialog(ref, callback) {
		if (this.$extension.isFunction(callback)) {
		  callback(this.alert)
		}
		this.$refs[ref].open()
	  },

	  closeDialog(ref) {
		this.$refs[ref].close()
	  },

	  log() {
	    console.log(this.l_department)
	    console.log(this.l_tab)
		this.$router.replace({ path: '/admin/department', query: { id: Math.random() } })
	  }
	},

	created () {
	  this.setHeader({
		title: '管理后台登录',
		type: 'admin.login'
	  })
	},

	// mounted() {
	//   console.log('mounted')
	//   this.$nextTick(() => {
	// 	this.department = ''
	// 	this.tab = ''
	// 	this.isLoading = false
    //
	// 	this.alert =  {
	// 	  content: 'tip',
	// 	  ok: '确定'
	// 	}
    //
	// 	this.l_department = [
	// 	  { name: '安卓组', value: '移动组' },
	// 	  { name: 'Web组', value: 'Web组' },
	// 	  { name: '美工组', value: '美工组' },
	// 	  { name: '营销策划', value: '营销策划' },
	// 	]
    //
	// 	this.l_tab = [
	// 	  { name: '第一组', value: 1 },
	// 	  { name: '第二组', value: 2 },
	// 	  { name: '第三组', value: 3 },
	// 	]
	//   })
	// }
  }
</script>
