const gulp = require('gulp');
const babel = require('gulp-babel');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');
const wrap = require('gulp-wrap');

gulp.task('js', () => {
    return gulp.src('assets/js/websocket.js')
        .pipe(babel({
            presets: ['@babel/preset-env'],
            plugins: [
                '@babel/plugin-proposal-class-properties',
                '@babel/plugin-proposal-private-methods',
                '@babel/plugin-syntax-class-properties',
            ],
        }))
        .pipe(wrap({src: 'assets/js/websocket.template.js'}))
        .pipe(gulp.dest('public/js'))
        .pipe(rename({extname: '.min.js'}))
        .pipe(uglify())
        .pipe(gulp.dest('public/js'));
});

gulp.task('default', gulp.series(['js']));
