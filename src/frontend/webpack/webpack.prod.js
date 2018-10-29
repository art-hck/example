const helpers = require('./helpers');
const webpack = require('webpack');
const webpackMerge = require('webpack-merge');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const commonConfig = require('./webpack.common.js');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

module.exports = webpackMerge(commonConfig, {
    devtool: 'source-map',

    output: {
        filename: '[name].[hash].js',
        chunkFilename: '[name].[hash].chunk.js'
    },

    plugins: [
        new MiniCssExtractPlugin({filename: '[name].[hash].css'}),
        new webpack.LoaderOptionsPlugin({
            htmlLoader: {
                minimize: false // workaround for ng2
            }
        })
    ],
    optimization: {
        minimizer: [
            /**
             * Plugin: UglifyJsPlugin
             * Description: Minimize all JavaScript output of chunks.
             * Loaders are switched into minimizing mode.
             *
             * See: https://webpack.js.org/plugins/uglifyjs-webpack-plugin/
             *
             * NOTE: To debug prod builds uncomment //debug lines and comment //prod lines
             */
            new UglifyJsPlugin({
                sourceMap: false,
                parallel: true,
                cache: helpers.root('webpack-cache/uglify-cache'),
                uglifyOptions: {
                    ecma: 6,
                    warnings: false, // TODO verbose based on option?
                    ie8: false,
                    mangle: true,
                    compress: {
                        pure_getters: true /* buildOptimizer */,
                        // PURE comments work best with 3 passes.
                        // See https://github.com/webpack/webpack/issues/2899#issuecomment-317425926.
                        passes: 2 /* buildOptimizer */
                    },
                    output: {
                        ascii_only: true,
                        comments: false
                    }
                }
            })
        ],
        // splitChunks: {
        //     chunks: 'all',
        //     maxSize: 249856
        // }
    }
});