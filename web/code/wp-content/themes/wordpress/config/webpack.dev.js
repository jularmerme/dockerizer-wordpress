
const webpack = require('webpack')
const autoprefixer = require('autoprefixer')
const path = require('path')
const merge = require('webpack-merge')
const common = require('./webpack.base.js')
const StyleLintPlugin = require('stylelint-webpack-plugin')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

module.exports = merge(common, {
  output: {
    path: path.resolve(__dirname, '../dist'),
    filename: 'js/[name].bundle.js'
  },
  devtool: 'inline-source-map',
  watch: true,
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: ExtractTextPlugin.extract({
          use: [
            {
              loader: 'css-loader',
              options: {
                url: false,
                sourceMap: true
              }
            },
            {
              loader: require.resolve('postcss-loader'),
              options: {
                // Necessary for external CSS imports to work
                // https://github.com/facebookincubator/create-react-app/issues/2677
                ident: 'postcss',
                plugins: () => [
                  require('postcss-flexbugs-fixes'),
                  autoprefixer({
                    browsers: [
                      '>1%',
                      'last 4 versions',
                      'Firefox ESR',
                      'not ie < 9' // React doesn't support IE8 anyway
                    ],
                    flexbox: 'no-2009',
                    grid: true
                  })
                ]
              }
            },
            {
              loader: 'sass-loader',
              options: {
                url: false,
                sourceMap: true
              }
            }
          ]
        })
      }
    ]
  },
  plugins: [
    // Lint our CSS
    new StyleLintPlugin({
      syntax: 'scss'
    }),
    // Compile SCSS files to plain css
    new ExtractTextPlugin('css/[name].css'),
    // Env vars
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': JSON.stringify('development')
    }),
    // Browser Sync
    new BrowserSyncPlugin({
      notify: false,
      host: 'localhost',
      port: 3000,
      logLevel: 'silent',
      baseDir: './dist',
      files: [
        path.join(__dirname, '../src/pages/**/*.php'),
        path.join(__dirname, '../dist/css/*.css'),
        path.join(__dirname, '../dist/js/*.js')
      ],
      proxy: 'http://localhost:8000/'
    }, {
      reload: false,
      injectCss: true
    })
  ]
})
