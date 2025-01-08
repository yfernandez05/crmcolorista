const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .copyDirectory('resources/eliteadmin/images', 'public/images')
    .copyDirectory([
        'node_modules/@fortawesome/fontawesome-free/webfonts',
        'resources/eliteadmin/icons/material-design-iconic-font/fonts',
        'node_modules/simple-line-icons/fonts',
        'node_modules/themify-icons-forked/themify-icons/fonts',
        'resources/eliteadmin/icons/weather-icons/fonts',
        'resources/eliteadmin/fonts'
    ], 'public/fonts')
    .js([
        'node_modules/jquery/dist/jquery.slim.min.js',
        'node_modules/popper.js/dist/umd/popper.min.js',
        'node_modules/bootstrap/dist/js/bootstrap.min.js',
        'resources/eliteadmin/js/perfect-scrollbar.jquery.min.js',
        'resources/eliteadmin/js/waves.js',
        'resources/eliteadmin/js/sidebarmenu.js',
        'node_modules/sticky-kit/dist/sticky-kit.min.js',
        //'node_modules/jquery-sparkline/jquery.sparkline.min.js',
        'resources/eliteadmin/js/custom.js',
        'node_modules/select2/dist/js/select2.full.min.js',
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js',
        './resources/eliteadmin/js/custom/toastr-message.js',
        'node_modules/moment/moment.js',
        'node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
        'node_modules/bootstrap-daterangepicker/daterangepicker.js',
        'node_modules/sweetalert2/dist/sweetalert2.all.min.js',
        './resources/eliteadmin/js/custom/sweetalert.js',

        //'./resources/eliteadmin/js/pages/morris-data.min.js',
        //'./resources/eliteadmin/js/pages/widget-charts.min.js',

    ], 'public/js/eliteadmin.js')
    .sass('resources/eliteadmin/scss/style.scss',
        'public/css/eliteadmin.css')
    .sass('resources/sass/auth.scss',
        'public/css/eliteadmin-auth.css')
    .sass('resources/sass/app.scss',
        'public/css')
    .options({
        postCss: [
            require('postcss-css-variables')()
        ],
        processCssUrls: false
    });

    
mix.disableSuccessNotifications();
/* mix.browserSync({
    proxy: 'http://localhost:8000/',
    open: false
});
 */