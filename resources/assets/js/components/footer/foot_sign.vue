<template>
    <footer class="footer">
        <p class="footer__links">
            <a target="_blank" href="http://meizhuo.org/meizhuo/" class="footer__link">
                @袂卓工作室
            </a>
        </p>
        <p class="footer__text">Copyright &copy; 2015-2016 meizhuo.org</p>
    </footer>
</template>

<style lang="sass" scoped>
  p {
	margin: 0 0 10px;
  }
  .footer {
	/*position: absolute;*/
	margin: auto;
	left: 0;
	right: 0;
	bottom: 0;
	padding-top: 30px;
	padding-bottom: 10px;
	text-align: center;
	font-size: 14px;
	z-index: 100;
	.footer__text {
	  padding: 0 .34em;
	  font-size: 12px;
  }
	.footer__links {
	   font-size: 0;
	  .footer__link {
		display: inline-block;
		vertical-align: top;
		margin: 0 .62em;
		position: relative;
		font-size: 14px;
	  }
  	}
  }
</style>

<script>
  export default {}
</script>
