require('dotenv').config()
const { mix } = require('laravel-mix');
const tailwindcss = require('tailwindcss');
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
    'buefy',
    'vue-core-image-upload',
    'vue-snotify',
    'vue-slick',
    'vue-cookie',
    'vue-money',
    'vee-validate',
    'vue-sticky-directive',
    'chart.js',
    'element-ui',
];



mix.options({
    processCssUrls: true,
    imgLoaderOptions: {
        enabled: false,
    }
}).setPublicPath(path.normalize('public/platform'));

mix.webpackConfig({
    output: {
        publicPath: '/platform/',
        chunkFilename: 'js/[name].[chunkhash].js',
    },
    plugins: [
        new webpack.NormalModuleReplacementPlugin(/element-ui[\/\\]lib[\/\\]locale[\/\\]lang[\/\\]zh-CN/, 'element-ui/lib/locale/lang/es')
    ]
});

mix.disableNotifications();

//Admin Parte
if (mix.inProduction()) {
    mix.js('client/app_platform.js', 'js/app.js')
        .sass('client/styles/app_platform.scss', 'public/platform/css')
        .options({
            processCssUrls: false,
            postCss: [tailwindcss('./tailwind.config.js')],
        })
        .version();
}else{
    mix.js('client/app_platform.js', 'js/app.js')
        .sass('client/styles/app_platform.scss', 'public/platform/css')
        .options({
            processCssUrls: false,
            postCss: [tailwindcss('./tailwind.config.js')],
        });
}

mix.extract(vendors);
