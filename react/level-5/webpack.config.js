const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
// LEVEL 5 : + WATCH
// https://webpack.kr/configuration/watch/
module.exports = {
  // watch: true,
  // watchOptions: {
  //   ignored: /node_modules/,
  // },
  mode: "production", // "production" | "development" | "none"
  entry: [
    './src/main.js',
  ],
  devtool: 'source-map',
  // css 별도 저장시
  plugins: [
    new HtmlWebpackPlugin({
      minify: {
      	collapseWhitespace: true
      },
      hash: true,
      template: './src/index.html'
    }),
    new MiniCssExtractPlugin({ 
      filename: 'styles/style.css' 
    })
  ],
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [ 
          MiniCssExtractPlugin.loader, // css 별도 저장
          "css-loader",
          "sass-loader",
        ],
        exclude: /node_modules/
      }
    ]
  },
  output: {
    path: path.resolve(__dirname, "dist"),
    filename: "bundle.js",
  }
}