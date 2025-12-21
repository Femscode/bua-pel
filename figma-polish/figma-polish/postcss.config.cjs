module.exports = {
  plugins: [
    require('postcss-preset-env')({
      stage: 1
    }),
    require('postcss-pxtorem')({
      rootValue: 16,
      propList: ['*'],
      // Don't convert border widths less than 1px and hairlines
      minPixelValue: 1.1
    }),
    require('autoprefixer')(),
    require('cssnano')({
      preset: 'default'
    })
    // OPTIONAL: PurgeCSS (enable only after verifying pages render correctly)
    // require('@fullhuman/postcss-purgecss')({
    //   content: ['./dist/**/*.html', './scripts/**/*.js'],
    //   defaultExtractor: (content) => content.match(/[A-Za-z0-9-_:/]+/g) || []
    // })
  ]
};
