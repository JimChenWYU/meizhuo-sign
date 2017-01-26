var path = require('path')
var webpack = require('webpack')
var merge = require('webpack-merge')
var ExtractTextPlugin = require('extract-text-webpack-plugin')
var BaseDir = '/'
var env = 'dev' // production or dev

var base = {
  entry: {
    app: './resources/assets/js/main.js',
    vendor: [ 'vue', 'vue-router', 'axios',
      'vuex', 'vuerify', 'vue-material', 'lodash' ]
  },
  // 输出配置
  output: {
    // 输出的js文件，路径相对于本文件所在的位置
    path: path.resolve(__dirname, "./public/js/"),

    // 将入口文件中涉及到的同步加载的js文件打包成一个js文件，基于文件的md5生成hash名称的script来防止缓存
    filename: "[name].js",

    // 异步加载的业务模块，例如按需加载的.vue单文件组件
    chunkFilename: "[id].[chunkHash].js",

    publicPath: "/"
  },
  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.min.js',
      'logger': 'vuex/dist/logger.js',
      'socket.io': 'socket.io-client/dist/socket.io.js',
    },
    extensions: ['', '.js', '.vue'] // 引用js和vue文件可以省略后缀名
  },
  module: {
    loaders: [
      {
        test: /\.js$/,
        loader: 'babel-loader',
        exclude: /node_modules/
      },
      {
        test: /\.css$/,
        loader: 'style-loader!css-loader'
      }
    ],
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          loaders: {
            // Since sass-loader (weirdly) has SCSS as its default parse mode, we map
            // the "scss" and "sass" values for the lang attribute to the right configs here.
            // other preprocessors should work out of the box, no loader config like this nessessary.
            'scss': 'vue-style-loader!css-loader!sass-loader',
            'sass': 'vue-style-loader!css-loader!sass-loader?indentedSyntax'
          }
          // other vue-loader options go here
        }
      }
    ]
  },
  devtool: '#cheap-module-source-map',
  plugins: [
    new webpack.optimize.OccurrenceOrderPlugin(),
    new webpack.NoErrorsPlugin()
  ]
}


var production = merge(base, {
  stats: {
    children: false
  },
  module: {
    rules: [{
      enforce: 'pre',
      test: /\.js$/,
      loader: "eslint-loader",
      exclude: /node_modules/
    }, {
      test: /\.(png|jpg|jpeg|gif|svg|eot|woff|ttf)$/,
      loader: 'url?limit=10000&name=' + BaseDir + 'images/[name].[ext]',
    }]
  },
  plugins: [
    new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: '"production"'
      }
    }),
    new webpack.optimize.UglifyJsPlugin({
      compress: {
        warnings: false
      }
    }),
    new webpack.LoaderOptionsPlugin({
      vue: {
        postcss: [
          require('autoprefixer')({
            browsers: ['> 0%']
          }),
          require('precss')()
        ],
        css: ExtractTextPlugin.extract({
          loader: "css-loader",
          fallbackLoader: "vue-style-loader"
        })
      },
      eslint: {
        configFile: '../.eslintrc'
      }
    }),
    new webpack.optimize.CommonsChunkPlugin({
      name: 'vendor',
      filename: 'vendor.bundle.js'
    })
  ]
});
var dev = merge(base, {
  module: {
    rules: [{
      test: /\.(png|jpg|jpeg|gif|svg|eot|woff|ttf)$/,
      loader: 'url?limit=10000&name=images/[name].[ext]'
    }]
  },
  devtool: '#cheap-eval-source-map',
  plugins: [
    new webpack.LoaderOptionsPlugin({
      vue: {
        postcss: [
          require('autoprefixer')({
            browsers: ['> 0%']
          }),
          require('precss')()
        ]
      }
    }),
    new webpack.optimize.CommonsChunkPlugin({
      name: 'vendor',
      filename: 'vendor.bundle.js'
    })
  ]
});

module.exports = env === 'production' ? production : dev