/**
 * Credit to https://github.com/ridgehkr/ for the general style and design.
 */

var paths = {
    src: {
        scripts: [
            'bower_components/modernizr/modernizr.js',
            'bower_components/jquery/dist/jquery.js',
            'bower_components/fastclick/lib/fastclick.js',
            'bower_components/jquery.cookie/jquery.cookie.js',
            'bower_components/jquery-placeholder/jquery.placeholder.js',
            'bower_components/foundation/js/foundation.js',
            'src/scripts/**/*.js'
        ],
        styles: [
            'src/styles/**/*.{css,scss}'
        ],
        fonts: [
            'src/fonts/**/*',
            'bower_components/font-awesome/fonts/*.{eot,svg,ttf,woff,woff2,otf}'
        ]
    },
    build: {
        scripts: '../www/scripts',
        styles: '../www/styles',
        fonts: '../www/fonts'
    }
};

// Load plugins
var gulp = require('gulp'),
    gulpSass = require('gulp-sass'),
    gulpAutoprefixer = require('gulp-autoprefixer'),
    gulpUglify = require('gulp-uglify'),
    gulpConcat = require('gulp-concat'),
    gulpPlumber = require('gulp-plumber');

gulp.task('styles', function() {
    return gulp.src( paths.src.styles )
        .pipe(gulpPlumber())
        .pipe(gulpSass({ outputStyle: 'compressed' }))
        .pipe(gulpAutoprefixer('last 2 version', '> 5%', 'safari 5', 'ie 7', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
        .pipe(gulp.dest( paths.build.styles ));
});

gulp.task('fonts', function() {
    return gulp.src( paths.src.fonts )
        .pipe(gulpPlumber())
        .pipe(gulp.dest( paths.build.fonts ));
});

gulp.task('scripts', function() {
    return gulp.src( paths.src.scripts )
        .pipe(gulpPlumber())
        .pipe(gulpUglify())
        .pipe(gulpConcat('app.js'))
        .pipe(gulp.dest( paths.build.scripts ));
});

gulp.task('default', function() {
	gulp.start( 'styles', 'fonts', 'scripts' );
});

gulp.task('watch', function() {
    gulp.start( 'styles', 'fonts', 'scripts' );
    gulp.watch( paths.src.styles , ['styles'] );
    gulp.watch( paths.src.fonts , ['fonts'] );
    gulp.watch( paths.src.scripts , ['scripts'] );

    console.log("Watching for changes to scripts, fonts, and styles...");
});