const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  entry: {
    style: './src/scss/style.scss',
    main: './src/js/index.js'  // メインのJavaScriptファイル
  },
  output: {
    path: path.resolve(__dirname, 'html/wp-content/themes/lol_dictionary/assets'),
    filename: 'js/[name].js',  // JavaScript出力先
  },
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'sass-loader'
        ],
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      }
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: 'css/[name].css',  // CSS出力先
    }),
  ],
  resolve: {
    alias: {
      '@scss': path.resolve(__dirname, 'src/scss'),
      '@js': path.resolve(__dirname, 'src/js'),
    }
  }
};