<template>
  <div>
	<h3>{{msg}}</h3>
	<md-dialog-alert
			:md-content="alert.content"
			:md-ok-text="alert.ok"
			ref="dialog3">
	</md-dialog-alert>

	<md-dialog-alert
			:md-title="alert2.title"
			:md-content-html="alert2.contentHtml"
			ref="dialog4">
	</md-dialog-alert>

	<md-button class="md-primary md-raised" @click="openDialog('dialog3')">Alert</md-button>
	<md-button class="md-primary md-raised" @click="openDialog('dialog4')">Alert with HTML</md-button>
  </div>
</template>

<script>
  export default {
	data () {
	  return {
	  	msg: 'Hello World',
		alert: {
		  content: 'Your post has been deleted!',
		  ok: 'Cool!'
		},
		alert2: {
		  title: 'Post created!',
		  contentHtml: 'Your post <strong>Material Design is awesome</strong> has been created.'
		}
	  }
	},
	methods: {
	  openDialog(ref) {
		this.$refs[ref].open();
	  },
	  closeDialog(ref) {
		this.$refs[ref].close();
	  },
	  onOpen() {
		console.log('Opened');
	  },
	  onClose(type) {
		console.log('Closed', type);
	  },

	  beforeRouteEnter (to, from, next) {
		// 在渲染该组件的对应路由被 confirm 前调用
		// 不！能！获取组件实例 `this`
		// 因为当钩子执行前，组件实例还没被创建
		console.log(to)
	  }
	}
  };
</script>
