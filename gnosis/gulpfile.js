const elixir   = require('laravel-elixir');
const svgmin   = require('gulp-svgmin');
const svgstore = require('gulp-svgstore');
const cheerio  = require('gulp-cheerio');

const config = {
    source: './resources/assets',
    build:  './public/gnosis'
};

elixir(mix => {
    mix.sass('style.scss', 'public/gnosis/css');
});

gulp.task('gnosis:icons', () => {
    return gulp.src(`${config.source}/icons/{,*/}*.svg`)
        .pipe(svgmin())
        .pipe(svgstore())
        .pipe(cheerio($ => $('svg').attr('style',  'display:none')))
        .pipe(gulp.dest(`${config.build}/img/`))
});
