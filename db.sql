var gulp = require('gulp');
var plugins = require('gulp-load-plugins')();

// JS hint task
gulp.task('jshint', function() {
  gulp.src('./js/*.js')
    .pipe(plugins.jshint())
    .pipe(plugins.jshint.reporter('default'));
});

gulp.task('htmldev', function () {
    return gulp.src('golfpervs.php')
        .pipe(plugins.useref())
        //.pipe(plugins.minify-html())
        .pipe(gulp.dest('./_dev'));
});

gulp.task('htmllive', function () {
    return gulp.src('golfpervs.php')
        .pipe(plugins.useref())
        .pipe(plugins.if('*.js', plugins.uglify()))
        .pipe(plugins.if('*.css', plugins.cleanCss()))
        //.pipe(plugins.minify-html())
        .pipe(gulp.dest('./_build'));
});

gulp.task('dev', ['movedev','htmldev'], function() {
	
});

gulp.task('live', ['movelive','htmllive'], function() {
	
});


var filesToMove = [
        './ajax/**/*.*',
        './coreClasses/**/*.*',
        './globals/**/*.*',
        './images/**/*.*',
        'index.php',
        'register.php',
        'login.php'
    ];

gulp.task('movelive', function(){
  // the base option sets the relative root for the set of files,
  // preserving the folder structure
  gulp.src(filesToMove, { base: './' })
  .pipe(gulp.dest('_build'));
});

gulp.task('movedev', function(){
  // the base option sets the relative root for the set of files,
  // preserving the folder structure
  gulp.src(filesToMove, { base: './' })
  .pipe(gulp.dest('_dev'));
});