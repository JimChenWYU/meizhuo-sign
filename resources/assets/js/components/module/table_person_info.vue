<template>
  <div class="container-person">
	<slot></slot>
	<md-table-card class="main-content">
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
	</md-table-card>

	<md-dialog-alert
	  :md-title="alert.title"
	  :md-content-html="alert.content"
	  @close="onClose"
	  ref="warning">
	</md-dialog-alert>
  </div>
</template>
<style lang="sass" scoped>
  .container-person {
	height: 100%;
	flex: 1;
	display: flex;
	flex-flow: column;
  }
  .main-content {
	padding: 6em 16px 0 16px;
  }
</style>
<script type="es6">
  export default{
	data(){
	  return{
	    alert: {
	      title: '警告！',
		  content: '<strong>warning!</strong>'
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

	methods: {
	  getPerson() {
		let id = this.$route.params.id
	  	this.$http.get(this.$env.adminSigner, {
	  	  params: { id: id }
		})
	  		.then(response => {
			  if (response.status === 204) {
				this.warn('<strong>访问出错！</strong>', 'warning')
				return false;
			  }
			  this.person.info = response.data.data
	  		})
			.catch(error => {
			  this.$router.replace(error.data.redirect)
			})
	  },

	  warn(msg, ref) {
	    this.alert.content = msg
	    this.$refs[ref].open()
	  },

	  onClose() {
		this.$router.replace('/admin')
	  }
	},

	created() {
	  this.getPerson()
	}
  }
</script>
