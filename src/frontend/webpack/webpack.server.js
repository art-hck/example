const webpackMerge = require('webpack-merge');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const commonConfig = require('./webpack.common.js');

module.exports = webpackMerge(commonConfig, {
    devtool: 'source-map',
    entry: './app/server.ts',
    output: {
        filename: 'server.js'
    },
    target: 'node', // solves error: fs not found
    plugins: [
        new MiniCssExtractPlugin({filename: '[name].css'})
    ],
    optimization: {
        // keep minimization off
        // workaround for https://github.com/angular/angular-cli/issues/10635
        minimize: false
    }
});