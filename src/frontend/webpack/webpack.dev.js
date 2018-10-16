const webpackMerge = require('webpack-merge');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const commonConfig = require('./webpack.common.js');

module.exports = webpackMerge(commonConfig, {
    devtool: 'cheap-module-eval-source-map',

    output: {
        filename: '[name].js',
        chunkFilename: '[id].chunk.js'
    },

    plugins: [
        new MiniCssExtractPlugin({filename: '[name].css'}),
    ]
});