require('dotenv').config()
const { mix } = require('laravel-mix');
let webpack = require('webpack');


/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

let vendors = [
    'axios',
    'vue',
    'lodash',
    'vue-i18n',
    'vee-validate',
];



mix.options({
    processCssUrls: true,
    imgLoaderOptions: {
        enabled: false,
    }
}).setPublicPath(path.normalize('public/website'));

mix.webpackConfig({
    output: {
        publicPath: '/website/',
        chunkFilename: 'js/[name].[chunkhash].js',
    }
});

mix.disableNotifications();

//Admin Parte
if (mix.inProduction()) {
    mix.js('client/website.js', 'js/website.js').version();
}else{
    mix.js('client/website.js', 'js/website.js');
}

mix.extract(vendors);
