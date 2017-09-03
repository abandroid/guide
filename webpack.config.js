let Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('src/Bundle/GuideBundle/Resources/public/build/')
    .setPublicPath('/bundles/endroidguide/build')
    .setManifestKeyPrefix('/build')
    .cleanupOutputBeforeBuild()
    .createSharedEntry('base', './src/Bundle/GuideBundle/Resources/public/src/js/base.js')
    .addEntry('dashboard', './src/Bundle/GuideBundle/Resources/public/src/js/dashboard.js')
    .autoProvidejQuery()
    .enableReactPreset()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();