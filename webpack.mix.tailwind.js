require('dotenv').config()
const { mix } = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const { CleanWebpackPlugin } = require('clean-webpack-plugin')


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
    'vue-cookie',
    'vee-validate',
];

mix.options({
    processCssUrls: true,
    imgLoaderOptions: {
        enabled: false,
    }
}).setPublicPath(path.normalize('public/tailwind'));

mix.webpackConfig({
    output: {
        publicPath: '/tailwind/',
        chunkFilename: 'js/[name].[chunkhash].js',
    },
    plugins: [
        new CleanWebpackPlugin({
            cleanOnceBeforeBuildPatterns: ['!.gitkeep']
        })
    ]
});

mix.disableNotifications();

//Admin Parte
if (mix.inProduction()) {
    mix.js('client/app_tailwind.js', 'js/app_tailwind.js')
        .sass('client/styles/app_tailwind.scss', 'public/tailwind/css')
        .sass('client/components/tailwind/scss/main-page-icons.scss', 'public/tailwind/css')
        .options({
            processCssUrls: false,
            postCss: [tailwindcss('./tailwind.config.js')],
        })
        .version();
    mix.scripts(['client/components/tailwind/helpers/modernizr.js'], 'public/tailwind/js/modernizr.js')
}else{
    mix.js('client/app_tailwind.js', 'js/app_tailwind.js')
        .sass('client/styles/app_tailwind.scss', 'public/tailwind/css')
        .sass('client/components/tailwind/scss/main-page-icons.scss', 'public/tailwind/css')
        .sass('client/components/tailwind/scss/main-page.scss', 'public/tailwind/css')
        .options({
            processCssUrls: false,
            postCss: [tailwindcss('./tailwind.config.js')],
        });
    mix.scripts(['client/components/tailwind/helpers/modernizr.js'], 'public/tailwind/js/modernizr.js')
}

mix.copyDirectory('client/components/tailwind/statics/', 'public/tailwind/statics')

mix.extract(vendors);
