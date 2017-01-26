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

<script type="es6">
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
	  }
	},
	created() {
	  let socket = new this.$extension.io('ws://localhost:6001')
	  console.log(socket)
	  socket.on('channel-end-interview', (data) => {
	    console.log(data)
	  })
	}
  };
</script>
