var gulp         = require('gulp'); 
var rename       = require('gulp-rename');
var coffee       = require('gulp-coffee');
var requirejs    = require('gulp-requirejs');
var less         = require('gulp-less');
var autoprefixer = require('gulp-autoprefixer');
var cssmin       = require('gulp-cssmin');
var uglify       = require('gulp-uglify');
var concat       = require('gulp-concat');
var plumber      = require('gulp-plumber');
var sourcemaps   = require('gulp-sourcemaps');

gulp.task('style', function() {
    gulp.src(['web/client/src/less/app.less'])
        .pipe(plumber())
        .pipe(less())
        .pipe(autoprefixer({
            browsers: ['last 500 versions', 'ie 7'],
            cascade: true
        }))
        .pipe(gulp.dest('web/client/dist/css'))
        .on('end', function(e) {
            gulp.src(['web/client/dist/css/app.css'])
                .pipe(plumber())
                .pipe(cssmin())
                .pipe(rename({suffix: '.min'}))
                .pipe(gulp.dest('web/client/dist/css'));
        });
});

gulp.task('script', function() {
    gulp.src(['web/client/src/coffee/**/*.coffee'])
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(coffee({bare: true}))
        .pipe(sourcemaps.write('./sourcemaps'))
        .pipe(gulp.dest('web/client/dist/js'))
        .on('end', function() {
            gulp.src(['web/client/dist/js/app.js'])
                .pipe(plumber())
                .pipe(requirejs({
                    baseUrl: "web/client",
                    paths: {
                        jquery:      'src/vendor/bower/jquery/dist/jquery',
                        inputmask:   'src/vendor/bower/jquery.inputmask/dist/jquery.inputmask.bundle',
                        underscore:  'src/vendor/bower/underscore/underscore',
                        moment:      'src/vendor/bower/momentjs/moment',
                        moment_ru:   'src/vendor/bower/momentjs/locale/ru',
                        common:      'dist/js/common',
                        controllers: 'dist/js/controllers',
                        components:  'dist/js/components',
                        models:      'dist/js/models',
                        views:       'dist/js/views'
                    },
                    shim: {
                        inputmask: {
                            dest: ['jquery']
                        }
                    },
                    removeCombined: true,
                    findNestedDependencies: true,
                    fileExclusionRegExp: /^\./,
                    out: "app.min.js",
                    name: 'dist/js/app'
                }))
                .pipe(gulp.dest('web/client/dist/js'))
                .on('end', function() {
                    gulp.src(['web/client/src/vendor/bower/requirejs/require.js', 'web/client/dist/js/app.min.js'])
                        .pipe(plumber())
                        .pipe(concat('app.min.js'))
                        .pipe(uglify())
                        .pipe(gulp.dest('web/client/dist/js'));
                });
        });
});

gulp.task('watch', function() {
    gulp.watch('web/client/src/less/**/*.less', function() {
        gulp.run('style');
    });
    
    gulp.watch('web/client/src/coffee/**/*.coffee', function() {
        gulp.run('script');
    });
});

gulp.task('default', function() {
    gulp.run('style', 'script');
});