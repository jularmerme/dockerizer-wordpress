
const webpack = require('webpack')
const fs = require('fs')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const CleanWebpackPlugin = require('clean-webpack-plugin')
const ManifestPlugin = require('webpack-manifest-plugin')

// Programmatically add entrypoints
let entries = {}
const pages = fs.readdirSync('./src/js/pages').filter(file => {
  return file.match(/.*\.js$/)
})
for (let page of pages) {
  const name = page.match(/.*(?=\.)/g)[0]
  entries[name] = `./src/js/pages/${page}`
}

module.exports = {
  entry: {
    vendor: [
      'babel-polyfill'
    ],
    style: './src/scss/style.scss',
    ...entries
  },
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.js$/,
        exclude: /node_modules/,
        loader: 'eslint-loader',
        options: {
          // eslint options (if necessary)
        }
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader'
        }
      }
    ]
  },
  plugins: [
    // Clean the dist folder
    new CleanWebpackPlugin(['../dist'], {
      allowExternal: true
    }),
    // Copy template files and images to dist
    new CopyWebpackPlugin([
      {
        from: 'src/',
        ignore: [ '*.js', '*.scss' ]
      }
    ]),
    new webpack.optimize.CommonsChunkPlugin({
      name: 'vendor',
      // filename: 'vendor.js'
      // (Give the chunk a different name)
      minChunks: 3
      // (with more entries, this ensures that no other module
      //  goes into the vendor chunk)
    }),
    new ManifestPlugin(),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery'
    })
  ]
}
