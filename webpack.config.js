var path = require('path')
var webpack = require('webpack')

module.exports = {
  entry: {
    app: './resources/assets/js/main.js',
    vendor: [ 'vue', 'vue-router', 'axios','vuerify','v-vuerify-next','vue-material','lodash','base64-url' ]
  },
  // 输出配置
  output: {
    // 输出的js文件，路径相对于本文件所在的位置
    path: path.resolve(__dirname, "./public/js/"),

    // 将入口文件中涉及到的同步加载的js文件打包成一个js文件，基于文件的md5生成hash名称的script来防止缓存
    filename: "[name].js",

    // 异步加载的业务模块，例如按需加载的.vue单文件组件
    chunkFilename: "[id].[name].[chunkHash].js",

    publicPath: './js/'
  },
  module: {
      loaders: [
          {
              test: /\.js$/,
              loader: 'babel-loader',
              exclude: /node_modules/
          },
          {
            test: /\.json$/,
            loader: 'json'
          },
          {
              test: /\.css$/,
              loader: 'style-loader!css-loader'
          },
          {
              test: /\.(eot|svg|ttf|woff|woff2)(\?\S*)?$/,
              loader: 'file-loader'
          },
          {
              test: /\.(png|jpe?g|gif|svg)(\?\S*)?$/,
              loader: 'file-loader',
              query: {
                  name: '[name].[ext]?[hash]'
              }
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
  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.js'
    },
    extensions: ['', '.js', '.vue'] // 引用js和vue文件可以省略后缀名
  },
  devServer: {
    historyApiFallback: true,
    noInfo: true
  },
  performance: {
    hints: false
  },
  devtool: '#eval-source-map',
  plugins: [
    // http://vuejs.github.io/vue-loader/en/workflow/production.html
    new webpack.DefinePlugin({
      'process.env': "'production'"
    }),
    new webpack.optimize.UglifyJsPlugin({
      compress: {
        warnings: false
      }
    }),
    // split vendor js into its own file
    new webpack.optimize.CommonsChunkPlugin({
      name: 'vendor',
      minChunks: function (module, count) {
        // any required modules inside node_modules are extracted to vendor
        return (
            module.resource &&
            /\.js$/.test(module.resource) &&
            module.resource.indexOf(
                path.join(__dirname, '../node_modules')
            ) === 0
        )
      }
    }),
    // extract webpack runtime and module manifest to its own file in order to
    // prevent vendor hash from being updated whenever app bundle is updated
    new webpack.optimize.CommonsChunkPlugin({
      name: 'manifest',
      chunks: ['vendor']
    })
  ]
};
