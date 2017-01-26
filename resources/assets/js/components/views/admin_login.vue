<template>
    <div class="admin-login__container">
        <k-head></k-head>

        <md-layout md-gutter>
            <md-layout md-flex="20" md-hide-xsmall></md-layout>
            <md-layout md-flex="10"></md-layout>
            <md-layout md-column>
                <router-view></router-view>
            </md-layout>
            <md-layout md-flex="10"></md-layout>
            <md-layout md-flex="20" md-hide-xsmall></md-layout>
        </md-layout>

        <k-footer class="admin-login__footer"></k-footer>
    </div>
</template>

<style lang="sass" scoped>
  .admin-login__container {
	min-height: 100%;
  }
  .admin-login__footer {
	position: absolute;
	bottom: 0;

  }
</style>

<script type="es6">
  import kHead from '../header/head'
  import kFooter from '../footer/foot'
  import kLogin from '../module/login'
  export default{
	components: {
	  kHead,kFooter,kLogin
	}
  }
</script>
