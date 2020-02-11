const path = require('path');
const webpack = require('webpack');

const ROOT_DIR = path.resolve(__dirname, '../');

module.exports = {
    // entry points of webpack, in vendor.js is for common styles and js
    // in custom.js is for unique styles and js of project
    entry: {
        vendor: path.resolve(ROOT_DIR, 'local', 'src', 'webpack_vendor.js'),
        custom:  path.resolve(ROOT_DIR, 'local', 'src', 'webpack_custom.js'),
    },
    module: {
        rules: [
            // js
            {
                test: /\.js$/,
                use: 'babel-loader',
                exclude: [
                    path.resolve(ROOT_DIR, 'node_modules'),
                ],
            },
            // images
            {
                test: /\.(png|jpg|gif)$/i,
                use: [
                    {
                        loader: 'url-loader',
                        options: {
                            limit: 10 * 1024, // картинки до 10 КБ
                            noquotes: true,
                        }
                    }
                ]
            },
            {
                test: /\.svg$/,
                loader: 'svg-url-loader',
                options: {
                  limit: 10 * 1024, // картинки до 10 КБ
                  noquotes: true,
                }
            },
            // fonts
            { test: /\.(woff|woff2|eot|ttf|otf)$/, use: ['url-loader'] },
        ]
    }
};
