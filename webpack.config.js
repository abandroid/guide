let Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('src/Bundle/GuideBundle/Resources/public/build/')
    .setPublicPath('/bundles/endroidguide/build')
    .setManifestKeyPrefix('/build')
    .cleanupOutputBeforeBuild()
    .addEntry('base', './src/Bundle/GuideBundle/Resources/public/src/js/base.js')
    .autoProvidejQuery()
    .enableReactPreset()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();