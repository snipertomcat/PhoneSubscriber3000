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
    'buefy',
    'vue',
    'lodash',
    'vue-i18n',
    'vue-chartjs',
    'vue-snotify',
    'vue-core-image-upload',
    'vue-sortable',
    'vee-validate'
];



mix.options({
    processCssUrls: true,
    imgLoaderOptions: {
        enabled: false,
    },
    allChunks: true,
}).setPublicPath(path.normalize('public/admin'));


mix.webpackConfig({
    output: {
        publicPath: '/admin/',
        chunkFilename: 'js/[name].[chunkhash].js',
    },
    plugins: [
        new webpack.NormalModuleReplacementPlugin(/element-ui[\/\\]lib[\/\\]locale[\/\\]lang[\/\\]zh-CN/, 'element-ui/lib/locale/lang/es')
    ]
});


mix.disableNotifications();

//Admin Parte
mix.js('client/app_admin.js', 'js/app.js')
    .sass('client/styles/app_admin.scss', 'public/admin/css')
    .options({
        postCss: [tailwindcss('./tailwind.config.js')],
    })
  .version();

mix.extract(vendors);
