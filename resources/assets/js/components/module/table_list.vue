<template>
  <div class="container-list">
	<slot></slot>
	<md-table-card class="main-content">
	  <md-toolbar>
		<h1 class="md-title">{{table.group}}</h1>
		<form @submit.stop.prevent="getSignerList({}, searchCondition)" class="form">
		  <md-input-container class="input">
			<md-input type="text"
					  placeholder="请输入学号或姓名搜索"
					  v-model.number.trim="table.search"
					  v-focus></md-input>
			<md-button class="md-icon-button" type="submit">
			  <md-icon>search</md-icon>
			</md-button>
		  </md-input-container>
		</form>
	  </md-toolbar>
	  <md-table>
		<md-table-header>
		  <md-table-row>
			<md-table-head v-for="head of table.header">{{head}}</md-table-head>
		  </md-table-row>
		</md-table-header>

		<md-table-body>
		  <md-table-row v-for="(row, index) of table.body" :key="index">
			<md-table-cell>
			  {{(table.page-1)*table.per_page+index+1}}
			</md-table-cell>
			<md-table-cell>{{row.student_id}}</md-table-cell>
			<md-table-cell>{{row.name}}</md-table-cell>
			<md-table-cell>{{row.major}}</md-table-cell>
			<md-table-cell>{{row.phone_num}}</md-table-cell>
			<md-table-cell>{{row.grade}}</md-table-cell>
			<md-table-cell>{{row.department}}</md-table-cell>
			<md-table-cell>
			  <router-link tag="md-button" :to="{ name: 'admin.show.id', params: { id: row.id } }" class="md-icon-button">
				<md-icon>more</md-icon>
			  </router-link>
			</md-table-cell>
		  </md-table-row>
		</md-table-body>
	  </md-table>

	  <k-pagination
		md-label="Rows"
		:md-size="table.per_page"
		:md-count="table.count"
		:md-total="table.total"
		:md-page="table.page"
		:md-page-options="table.mdPageOptions"
		@pagination="getSignerList">
	  </k-pagination>

	</md-table-card>
  </div>
</template>
<style lang="sass" scoped>
  .container-list {
	min-height: 100%;
	max-height: 100%;
	flex: 1;
	display: flex;
	flex-flow: column;
  }
  .main-content {
	position: relative;
	z-index: 1;
	display: flex;
	padding: 16px;
	flex: 1;
	overflow: auto;
	background-color: #fff;
	transform: translate3D(0,0,0);
	transition: all .4s cubic-bezier(.25,.8,.25,1);
	transition-delay: .2s;
  }

  .form {
	width: 35%;
	@media (max-width: 600px) {
	  width: 100%;
	}
  }
</style>
<script type="es6">
  import kPagination from './my_pagination'
  import { mapGetters, mapActions } from 'vuex'
  export default{
	components: { kPagination },

	data(){
	  return {
		table: {
		  group: '全部',
		  page: 1,
		  mdPageOptions: [7],
		  per_page: 7,
		  count: 7,
		  total: 0,
		  header: [
			'序号', '学号', '姓名', '专业', '电话号码', '年级', '意向部门', '简介'
		  ],
		  body: [],
		  search: ''
		},
	  }
	},
	computed: {
	  ...mapGetters([ 'getAdmin' ]),

	  onMdPageOptions() {
		// let per = 7, multiple = 2,
		// 		count = Math.ceil(Math.ceil(this.table.total/per)/multiple)
		// let mdPageOptions = [];
		// for (let group = 1; group <= count; group++) {
		//   mdPageOptions.push((multiple*group-1)*per)
		// }
		return [this.table.per_page]
	  },

	  // 路由调转时状态
	  routeCondition() {
		let _listCondition = {
		  page: this.$route.params['page'] ? this.$route.params['page'] : 1,
		  student_id: null,
		  name: null,
		  department: this.getAdmin.map[this.$route.params['department']]
		}

		this.table.search = ''
		this.setListCondition(_listCondition)

		return this.getAdmin.listCondition
	  },

	  // 搜索状态
	  searchCondition() {
		let searchCondition = this.table.search,
			_listCondition = {
			  page: 1,
			  student_id: null,
			  name: null,
			  department: null
			}

		if (this.$extension.isNumber(searchCondition)) {
		  _listCondition['student_id'] = searchCondition
		} else if (this.$extension.isEmpty(searchCondition) && this.$extension.isString(searchCondition)) {
		  _listCondition['name'] = searchCondition
		}

		this.setListCondition(_listCondition)

		return this.getAdmin.listCondition
	  }
	},

	methods: {
	  ...mapActions([ 'setListCondition' ]),

	  getSignerList (pagination = {page: 1, size: 7},
					 listCondition = this.getAdmin.listCondition) {
	    let params = this.$extension.extend({
		  page: listCondition.page,
		  name: listCondition.name,
		  student_id: listCondition.student_id,
		  department: listCondition.department
		}, pagination)

		this.$http.get(this.$env.adminSigners, {
		  params: params
		})
		  .then(response => {
			let res = response.data
			this.setViews(res)
			// console.log(this.setViews(res))
		  })
		  .catch(error => {
		    console.log(error)
		    this.$router.replace(error.data.redirect)
		  })
	  },

	  onPagination(pagination) {
	    let department = this.$extension.isEmpty(this.$route.params.department) ?
				'all' : this.$route.params.department
	    let location = {
	      name: 'admin.show.department.page',
		  params: {
	        page: pagination.page,
	        department: department
		  }
		}

	    this.$router.push(location)
	  },

	  setViews(res) {
	    let group = this.getAdmin.map[this.$route.params.department]
	    this.table.group = group ? group : '全部'
		this.table.body = res.data
		this.table.per_page = res.per_page
		this.table.count = res.count
		this.table.total = res.total
		this.table.page = res.current_page
		this.table.mdPageOptions = this.onMdPageOptions
		return this.table.page
	  }
	},

	watch: {
	  $route() {
		this.getSignerList({}, this.routeCondition)
	  },
	},

	created() {
	  // console.log(this.$extension)
	  this.getSignerList({}, this.routeCondition)
	}
  }
</script>
