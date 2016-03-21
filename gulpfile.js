var gulp = require('gulp');
var plugins = require('gulp-load-plugins')();

// JS hint task
gulp.task('jshint', function() {
  gulp.src('./js/*.js')
    .pipe(plugins.jshint())
    .pipe(plugins.jshint.reporter('default'));
});

gulp.task('html', function () {
    return gulp.src('golfpervs.php')
        .pipe(plugins.useref())
        .pipe(plugins.if('*.js', plugins.uglify()))
        //.pipe(plugins.minify-html())
        .pipe(gulp.dest('./build'));
});

gulp.task('default', ['html'], function() {
	
});