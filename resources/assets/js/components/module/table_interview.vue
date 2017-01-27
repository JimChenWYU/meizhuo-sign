<template>
  <md-table-card class="main-content">
	<md-toolbar>
	  <h1 class="md-title">面试中...</h1>
	  <md-button class="md-raised md-warn" @click="openDialog('confirm')">
		结束面试
	  </md-button>

	  <md-button class="md-raised" @click="out()">退出</md-button>
	</md-toolbar>
	<md-table>
	  <md-table-header>
		<md-table-row>
		  <md-table-head v-for="(header, index) of person.header"
						 v-if="index < person.header.length-1">{{header}}</md-table-head>
		</md-table-row>
	  </md-table-header>

	  <md-table-body>
		<md-table-row>
			<md-table-cell>{{person.info.student_id}}</md-table-cell>
			<md-table-cell>{{person.info.name}}</md-table-cell>
			<md-table-cell>{{person.info.major}}</md-table-cell>
			<md-table-cell>{{person.info.phone_num}}</md-table-cell>
			<md-table-cell>{{person.info.major}}</md-table-cell>
			<md-table-cell>{{person.info.department}}</md-table-cell>
		</md-table-row>
		<md-table-row>
			<md-table-head colspan="6" style="text-align: center">
				{{person.header[person.header.length-1]}}
			</md-table-head>
		</md-table-row>
		<md-table-row>
			<md-table-cell colspan="6">{{person.info.introduce}}</md-table-cell>
		</md-table-row>
	  </md-table-body>
	</md-table>

	<md-dialog-confirm
	  :md-title="confirm.title"
	  :md-content-html="confirm.contentHtml"
	  md-ok-text="确定"
	  md-cancel-text="取消"
	  @close="onConfirmClose"
	  ref="confirm">
	</md-dialog-confirm>

	<md-snackbar :md-position="snackbar.vertical + ' ' + snackbar.horizontal" ref="snackbar" :md-duration="snackbar.duration">
	  <span>{{snackbarMsg}}</span>
	  <md-button class="md-accent" md-theme="light-blue" @click="$refs.snackbar.close()">OK</md-button>
	</md-snackbar>
  </md-table-card>
</template>

<style lang="sass">
  .main-content {
	padding: 10px;
  }
  .md-theme-light-blue.md-button:not([disabled]).md-accent:not(.md-icon-button) {
  	color: #ffeb3b;
  }
</style>

<script type="es6">
  import { mapActions } from 'vuex'
  export default {
	data(){
	  return{
	    socket: '',
		confirm: {
		  title: '确定框',
		  contentHtml: '<h3>您确定面试结束吗？</h3>'
		},
		snackbar: {
		  vertical: 'top',
		  horizontal: 'center',
		  duration: '8000',
		  msg: '请稍等...'
		},
		person: {
		  header: [
			'学号', '姓名', '专业', '电话号码', '年级', '意向部门', '简介'
		  ],
		  info: {
			student_id: '',
			name: '',
			major: '',
			phone_num: '',
			grade: '',
			department: '',
			introduce: ''
		  }
		}
	  }
	},

	computed: {
	  snackbarMsg() {
	    return this.snackbar.msg
	  }
	},

	methods: {

	  ...mapActions([ 'setHeader' ]),

	  onConfirmClose(type) {
	    if (type === 'ok') {
	      this.endingInterview()
		}
	  },

	  endingInterview() {
	    this.$http.put(this.$env.adminDepartment)
			.catch(error => {
			  console.log(error)
			  this.$router.replace(error.data.redirect)
			})
	  },

	  out() {
	    this.$http.delete(this.$env.adminDepartment)
			.then(response => {
			  // console.log(response)
			  let res = response.data
			  this.$router.replace({ path: res.data.redirect })
			})
			.catch(error => {
			  console.log(error)
			  this.$router.replace({ path: '/admin/department' })
			})
	  },

	  openDialog(ref, callback) {
		if (this.$extension.isFunction(callback)) {
		  callback()
		}
		this.$refs[ref].open()
	  },

	  onMessage() {
		this.socket.on('channel-message:App\\Events\\broadcastMessageEvent', res => {
		  // console.log(res)
		  this.openDialog('snackbar', () => {
			this.snackbar.msg = res.msg
		  })

		})
	  },
	},

	created() {
	  this.socket = new this.$extension.io(this.$env.socket)
	  this.setHeader({
		title: '面试官',
		type: 'admin.login'
	  })
	},

	mounted() {
	  this.onMessage()
	},

	beforeRouteLeave (to, from, next) {
	  // 导航离开该组件的对应路由时调用
	  // 可以访问组件实例 `this`
	  this.out()
	  next()
	},
  }
</script>
