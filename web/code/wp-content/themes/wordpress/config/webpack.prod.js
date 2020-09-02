
const webpack = require('webpack')
const autoprefixer = require('autoprefixer')
const path = require('path')
const merge = require('webpack-merge')
const common = require('./webpack.base.js')
const UglifyJSPlugin = require('uglifyjs-webpack-plugin')
const ExtractTextPlugin = require('extract-text-webpack-plugin')

module.exports = merge(common, {
  output: {
    path: path.resolve(__dirname, '../dist'),
    filename: 'js/[name].bundle.[chunkhash:8].js'
  },
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: ExtractTextPlugin.extract({
          use: [{
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
              minimize: true,
              sourceMap: false
            }
          }]
        })
      }
    ]
  },
  plugins: [
    // Compile SCSS files to plain css
    new ExtractTextPlugin({
      filename: 'css/[name].[contenthash].css'
    }),
    new UglifyJSPlugin(),
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': JSON.stringify('production')
    })
  ]
})
