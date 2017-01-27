<template>
  <span class="font" :class="{'yellow': status===1, 'blue': status===2, 'green': status===3}">{{map[status]}}</span>
</template>
<style lang="sass">
  .yellow {
  	color: #CC9900;
  }
  .blue {
	color: #3F51B5;
  }
  .green {
	color: #009A61;
  }
  .font {
	font-weight: bold;
  }
</style>
<script type="es6">
  export default{
	props: {
	  status: {
	    type: Number,
		default: 1
	  }
	},
	data(){
	  return{
		map: [
		  null, '等待中', '就绪中', '面试中'
		]
	  }
	}
  }
</script>
