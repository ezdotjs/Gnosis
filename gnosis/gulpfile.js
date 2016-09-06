const elixir     = require('laravel-elixir');

const gutil      = require('gulp-util');
const vinyl      = require('vinyl-source-stream');
const browserify = require('browserify');
const babelify   = require('babelify');
const sourcemaps = require('gulp-sourcemaps');
const eslint     = require('gulp-eslint');

const svgmin     = require('gulp-svgmin');
const svgstore   = require('gulp-svgstore');
const cheerio    = require('gulp-cheerio');

const config = {
    source: './resources/assets',
    build:  './public/gnosis'
};

elixir(mix => {
    mix.sass('style.scss', 'public/gnosis/css')
});

gulp.task('gnosis:lint', () => {
    return gulp
        .src([`${config.source}/js/**/*.js`])
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failAfterError());
});

gulp.task('gnosis:js', () => {
    let bundler = browserify({
        entries:    `${config.source}/js/script.js`,
        debug:      false,
        transform:  [
            babelify.configure({
                presets: ['es2015'],
                plugins: ['transform-class-properties']
            }),
            'uglifyify'
        ],
    });
    bundler
        .bundle()
        .on('error', handleError)
        .pipe(vinyl('script.js'))
        .pipe(gulp.dest(`${config.build}/js/`));
});

gulp.task('gnosis:icons', () => {
    return gulp.src(`${config.source}/icons/{,*/}*.svg`)
        .pipe(svgmin())
        .pipe(svgstore())
        .pipe(cheerio($ => $('svg').attr('style',  'display:none')))
        .pipe(gulp.dest(`${config.build}/img/`))
});

gulp.task('gnosis:all', ['gnosis:lint', 'gnosis:js']);

function handleError(e)
{
    gutil.log(e);
}
