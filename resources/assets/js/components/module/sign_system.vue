<template>
  <form @submit.prevent="openDialog('confirmSubmit')">
	<md-card>
	  <md-card-content>
		<md-layout md-gutter>
		  <md-layout md-flex md-flex-medium="33" md-hide-small>
		  </md-layout>

		  <md-layout md-flex-xsmall="100" md-flex-small="50" md-flex-medium="33">
			<md-input-container>
			  <label>学号</label>
			  <md-input type="number"
						v-model.trim="student_id"
						placeholder="学号"
						v-focus
						pattern="^\d{10}$"
						required></md-input>
			</md-input-container>
		  </md-layout>

		  <md-layout md-flex-xsmall="100" md-flex-small="50" md-flex-medium="33">
			<md-input-container>
			  <label>姓名</label>
			  <md-input type="text"
						v-model.trim="name"
						placeholder="姓名"
						required></md-input>
			</md-input-container>
		  </md-layout>

		  <md-layout md-flex-xsmall="100" md-flex-small="50" md-flex-medium="33">
			<md-input-container>
			  <label>意向部门</label>
			  <md-select name="department" id="department" v-model="department">
				<md-option v-for="dept of l_dept" :value="dept.value">
				  {{dept.name}}
				</md-option>
			  </md-select>
			</md-input-container>
		  </md-layout>

		  <md-layout md-flex-small="100"
					  md-flex-medium="33"
					  md-hide-xsmall md-column>
			<md-button class="md-raised md-primary" type="submit">确定</md-button>
		  </md-layout>

		  <md-layout md-flex-small="100"
					  md-flex-medium="33"
					  md-hide-xsmall md-column>
			<md-button class="md-raised" @click="$refs.snackbar.open()">
				显示推送信息
			</md-button>
		  </md-layout>
		</md-layout>
	  </md-card-content>
	</md-card>

	<md-card class="queue-header">
	  <md-card-header>
		<md-layout :md-gutter="24">
		  <md-layout v-for="dept of l_dept">
			<span class="md-title">{{dept.name}}</span>
		  </md-layout>
		</md-layout>
	  </md-card-header>
	  <md-layout :md-gutter="20">
		<md-list class="md-layout">
		  <md-list-item v-for="android of dept.android">
			<span>{{android.name}}</span>
			<k-span :status="android.status"></k-span>
		  </md-list-item>
		</md-list>

		<md-list class="md-layout">
		  <md-list-item v-for="web of dept.web">
			<span>{{web.name}}</span>
			<k-span :status="web.status"></k-span>
		  </md-list-item>
		</md-list>

		<md-list class="md-layout">
		  <md-list-item v-for="design of dept.design">
			<span>{{design.name}}</span>
			<k-span :status="design.status"></k-span>
		  </md-list-item>
		</md-list>

		<md-list class="md-layout">
		  <md-list-item v-for="marking of dept.marking">
			<span>{{marking.name}}</span>
			<k-span :status="marking.status"></k-span>
		  </md-list-item>
		</md-list>
	  </md-layout>
	</md-card>

	<md-dialog-confirm
		  :md-title="confirm.title"
		  :md-content-html="confirm.contentHtml"
		  md-ok-text="确定"
		  md-cancel-text="取消"
		  @close="onConfirmClose"
		  ref="confirmSubmit">
	</md-dialog-confirm>

	  <!-- 弹框 -->
	<md-dialog-alert
		  :md-content="alert.content"
		  md-ok-text="确定"
		  ref="tip">
	</md-dialog-alert>

	<md-snackbar :md-position="snackbar.vertical + ' ' + snackbar.horizontal" ref="snackbar" :md-duration="snackbar.duration">
	  <span>{{snackbarMsg()}}</span>
	  <md-button class="md-accent" md-theme="light-blue" @click="$refs.snackbar.close()">OK</md-button>
	</md-snackbar>
  </form>
</template>

<style lang="sass" scoped>
  .header {
	margin-bottom: 20px;
  }
  .queue-header {
	margin-top: 20px;
  }
  .md-theme-light-blue.md-button:not([disabled]).md-accent:not(.md-icon-button) {
	color: #ffeb3b;
  }
</style>

<script type="es6">
  import kSpan from '../module/status'
    import { mapGetters, mapActions } from 'vuex'
  export default{
	components: { kSpan },

	data(){
	  return{
		confirm: {
		  title: '确定框',
		  contentHtml: '<h3>您确定提交吗？</h3>'
		},
		alert: {
		  content: '提示框'
		},
		socket: '',
		snackbar: {
		  vertical: 'top',
		  horizontal: 'center',
		  duration: '8000'
		},
		l_dept: [
		  {
			name: '安卓组',
			value: '移动组'
		  },
		  {
			name: 'Web组',
			value: 'Web组'
		  },
		  {
			name: '美工组',
			value: '美工组'
		  },
		  {
			name: '营销策划',
			value: '营销策划'
		  }
		],
		dept: {
		  android: [],
		  web: [],
		  design: [],
		  marking: []
		},
		student_id: '3114002521',
		name: '陈君武',
		department: '移动组'
	  }
	},

	computed: {
	  ...mapGetters([ 'getStore' ]),
	  signData() {
		return {
		  student_id: this.student_id,
		  name: this.name,
		  department: this.department
		}
	  }
	},

	methods: {
	  ...mapActions([ 'setHeader' ]),

	  getQueue() {
		this.$http.get(this.$env.adminQueue)
			.then(response => {
			  let data = response.data.data
			  this.dept = data
			  // console.log(data)
			})
			.catch(error => {
			  alert(error)
			})
	  },

	  enQueue() {
		this.$http.get(this.$env.adminSign, {
			params: this.signData
		})
		.then(response => {
		  // console.log(response)
		  if (response.status === 202) {
			this.openDialog('tip', () => {
			  this.alert.content = response.data.msg
			  // console.log(com)
			})
			return false
		  }

		  let data = response.data.data
		  this.dept = data
		})
		.catch(error => {
		  this.openDialog('tip', () => {
			this.alert.content = error.msg
		  })
		})
	  },

	  openDialog(ref, callback) {
		if (this.$extension.isFunction(callback)) {
		  callback(this.$refs[ref]);
		}
		this.$refs[ref].open();
	  },

	  onConfirmClose(type) {
		if (type === 'ok') {
		  this.enQueue()
		}
	  },

	  onEndInterview() {
		this.socket.on('channel-end-interview:App\\Events\\broadcastEndingInterviewEvent', (res) => {
		  // console.log(res)
		  this.getStore.setStorage('msg', res.msg)
		  this.dept = res.data
		  this.$refs.snackbar.open()
		})
	  },

	  snackbarMsg() {
		let msg = this.getStore.getStorage('msg')
		if (typeof msg !== 'undefined') {
		  return msg
		}
		return '请等待通知...'
	  }
	},

	created() {
	  this.setHeader({
		title: '面试者签到系统',
		type: 'admin.login'
	  })
	  this.getQueue()
	  this.socket = new this.$extension.io(this.$env.socket)
	},

	mounted() {
	  this.onEndInterview()
	}
  }
</script>
