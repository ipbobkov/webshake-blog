var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())

    .addEntry('js/app', [
            './assets/app.js',
            './node_modules/bootstrap/dist/js/bootstrap.min.js'
        ])
        .addStyleEntry('css/app', [
            './assets/styles/app.css',
            './node_modules/bootstrap/dist/css/bootstrap.min.css'
        ]);

module.exports = Encore.getWebpackConfig();