const webpackMerge = require('webpack-merge');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const commonConfig = require('./webpack.common.js');
const webpack = require('webpack');
const helpers = require('./helpers');

module.exports = webpackMerge(commonConfig, {
    devtool: 'source-map',
    entry: './app/server.ts',
    output: {
        filename: 'server.js'
    },
    target: 'node', // solves error: fs not found
    plugins: [
        new MiniCssExtractPlugin({filename: '[name].css'}),

        // Temporary Fix for issue: https://github.com/angular/angular/issues/11580
        // for "WARNING Critical dependency: the request of a dependency is an expression"
        new webpack.ContextReplacementPlugin(
            /(.+)?angular(\\|\/)core(.+)?/,
            helpers.root('.'),
            {} // a map of your routes
        ),
        new webpack.ContextReplacementPlugin(
            /(.+)?express(\\|\/)(.+)?/,
            helpers.root('.'),
            {}
        )        
    ],
    optimization: {
        // keep minimization off
        // workaround for https://github.com/angular/angular-cli/issues/10635
        minimize: false
    }
});