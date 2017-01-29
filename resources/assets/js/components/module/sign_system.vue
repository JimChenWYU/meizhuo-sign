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
			<span class="md-span">
			  <md-button class="md-icon-button" title="出队面试"
						 @click="openDialog('confirmDeQueue', dept)">
			  	<md-icon>queue_play_next</md-icon>
			  </md-button>
			</span>
		  </md-layout>
		</md-layout>
	  </md-card-header>
	  <md-layout :md-gutter="20">
		<md-list class="md-layout">
		  <md-list-item v-for="(android, index) of dept.android">
			<span>{{android.name}}</span>
			<k-span :status="android.status"></k-span>
			<md-button class="md-icon-button" @click="deQueueByIndex(android.department, index)">
			  <md-icon>delete</md-icon>
			</md-button>
		  </md-list-item>
		</md-list>

		<md-list class="md-layout">
		  <md-list-item v-for="(web, index) of dept.web">
			<span>{{web.name}}</span>
			<k-span :status="web.status"></k-span>
			<md-button class="md-icon-button" @click="deQueueByIndex(web.department, index)">
			  <md-icon>delete</md-icon>
			</md-button>
		  </md-list-item>
		</md-list>

		<md-list class="md-layout">
		  <md-list-item v-for="(design, index) of dept.design">
			<span>{{design.name}}</span>
			<k-span :status="design.status"></k-span>
			<md-button class="md-icon-button" @click="deQueueByIndex(design.department, index)">
			  <md-icon>delete</md-icon>
			</md-button>
		  </md-list-item>
		</md-list>

		<md-list class="md-layout">
		  <md-list-item v-for="(marking, index) of dept.marking">
			<span>{{marking.name}}</span>
			<k-span :status="marking.status"></k-span>
			<md-button class="md-icon-button" @click="deQueueByIndex(marking.department, index)">
			  <md-icon>delete</md-icon>
			</md-button>
		  </md-list-item>
		</md-list>
	  </md-layout>
	</md-card>

	<!-- 签到提交 -->
	<md-dialog-confirm
		  md-title="确认框"
		  md-content-html="<h3>您确定提交吗？</h3>"
		  md-ok-text="确定"
		  md-cancel-text="取消"
		  @close="onConfirmClose"
		  ref="confirmSubmit">
	</md-dialog-confirm>

	<!-- 删除提交 -->
	<md-dialog-confirm
		:md-title="confirm.title"
		:md-content-html="confirm.contentHtml"
		md-ok-text="确定"
		md-cancel-text="取消"
		@close="onDeleteQueueClose"
		ref="confirmDelete">
	</md-dialog-confirm>

	<!-- 出对面试 -->
	<md-dialog-confirm
		md-title="确认框"
		md-content-html="<h3>您确认出队吗？</h3>"
		md-ok-text="确定"
		md-cancel-text="取消"
		@close="onDeQueueClose"
		ref="confirmDeQueue">
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
  .md-span {
	padding-left: 10px;
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
		delCondition: {
		  department: '',
		  index: ''
		},
		condition: {},
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
		  this.dept = response.data.data
		})
		.catch(error => {
		  this.openDialog('tip', () => {
			this.alert.content = error.msg
		  })
		})
	  },

	  deQueueByIndex(department, index) {

	    this.delCondition = {
	      department: department,
		  index: index
		}

		this.openDialog('confirmDelete', () => {
		  this.confirm.contentHtml = "<h3>您确定删除吗？</h3>"
		})

	  },

	  deQueue() {
		this.$http.patch(this.$env.adminQueue, {
		  department: this.condition.value
		})
		.then(response => {
		  console.log(response)
		  if (response.status === 202) {
		    this.openDialog('tip', () => {
		      this.alert.content = response.data.msg
			})
		  }
		})
		.catch(error => {
		  console.log(error)
		})
	  },

	  openDialog(ref, callback) {
		if (this.$extension.isFunction(callback)) {
		  callback(this.$refs[ref]);
		} else {
		  this.condition = callback
		}
		this.$refs[ref].open();
	  },

	  onConfirmClose(type) {
		if (type === 'ok') {
		  this.enQueue()
		}
	  },

	  onDeleteQueueClose(type) {
	    // console.log(type)
	    if (type === 'ok') {
	      this.$http.delete(this.$env.adminQueue, {
	        data: this.delCondition
		  })
		  .then(response => {
			// console.log(response)
			this.dept = response.data.data
		  })
		  .catch(error => {
			console.log(error)
			this.openDialog('tip', () => {
			  this.alert.content = error.msg
			})
		  })
		}
	  },

	  onDeQueueClose(type) {
		if (type === 'ok') {
		  console.log(type)
		  this.deQueue()
		}
	  },

	  onEndInterview() {
		this.socket.on('channel-end-interview:App\\Events\\broadcastEndingInterviewEvent', (res) => {
		  console.log(res)
		  this.dept = res.data.redis_array
		  this.openDialog('snackbar', () => {
			this.getStore.setStorage('msg', res.msg)
		  })
		})
	  },

	  onMessage() {
	    this.socket.on('channel-message-sign:App\\Events\\broadcastMessageEvent', res => {
	      console.log(res)
		  this.dept = res.data
		  this.openDialog('snackbar', () => {
			this.getStore.setStorage('msg', res.msg)
		  })
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
	  this.onMessage()
	}
  }
</script>
