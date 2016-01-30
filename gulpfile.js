var gulp = require('gulp');

var gutil = require('gulp-util'),
    jshint = require('gulp-jshint'),
    browserify = require('gulp-browserify'),
    /* concat = require('gulp-concat'), */
    minifyCss = require('gulp-minify-css'),
    copy = require('gulp-copy'),
    plumber = require('gulp-plumber'),
    uglify = require('gulp-uglify'),
    sourcemaps = require('gulp-sourcemaps'),
    rename = require('gulp-rename'),
    source = require('vinyl-source-stream'),
    watchify = require('watchify'),
    buffer = require('vinyl-buffer'),
    fastbrowserify = require('browserify'),
    assign = require('lodash.assign'),
    karma = require('gulp-karma');


gulp.task('test', function(){
    // Be sure to return the stream
    // NOTE: Using the fake './foobar' so as to run the files
    // listed in karma.conf.js INSTEAD of what was passed to
    // gulp.src !
   return gulp.src('./foobar')
       .pipe(karma({configFile: 'karma.conf.js',
       action: 'run'}))
       .on('error', function(err){
           console.log(err);
           this.emit('end');
       });
});

gulp.task('autotest', function(){
   return gulp.watch(['public/js/app/**/*.js', 'test/spec/*.js'], ['test'])
});

gulp.task('default', function() {
    // place code for your default task here

});

gulp.task('lint', function(){
   gulp.src(['public/js/app/components/**/*.js', 'public/js/app/shared/**/*.js', 'public/js/app/router.js', 'public/js/app/app.js'])
       .pipe(jshint())
       .pipe(jshint.reporter('default'));
});
/*
 copy:{
 main:{
 files:[
 {expand: true,cwd: 'bower_components/bootstrap/dist/', src:'**', dest:'public/bootstrap/'}]
 }
 }
 */
gulp.task('copy', function(){

});

gulp.task('uglify', function(){
    return gulp.src('public/js/app/app-bundle.js')
        .pipe(uglify())
        .pipe(rename({basename:'app.min'}))
        .pipe(gulp.dest('public/js/app'));
});

gulp.task('minifycss', function(){
    return gulp.src(['public/css/socialSample.css'])
        .pipe(sourcemaps.init())
        .pipe(minifyCss({keepBreaks:true}))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('public/css/'));
});

gulp.task('pro-scripts', function(){
    gulp.src('public/js/app/app.js')
        .pipe(browserify({
            insertGlobals : true,
            debug : false
        }))
        .pipe(uglify())
        .pipe(rename({basename:'app.min'}))
        .pipe(gulp.dest('public/js/app'));
});

gulp.task('dev-scripts', function(){
    gulp.src('public/js/app/app.js')
        .pipe(browserify({
            insertGlobals : true
        }))
        .pipe(rename({suffix:'-bundle'}))
        .pipe(gulp.dest('public/js/app/'));
});


gulp.task('develop', function(){
    gulp.start('dev-scripts', 'minifycss');
});

gulp.task('production', function(){
    gulp.start('pro-scripts', 'minifycss');
});


// add custom browserify options here
var customOpts = {
    entries: ['./public/js/app/app.js'],
    debug: false
};
var opts = assign({}, watchify.args, customOpts);

var b = watchify(fastbrowserify(opts));

// add transformations here
// i.e. b.transform(coffeeify);

gulp.task('js', bundle); // so you can run `gulp js` to build the file
b.on('update', bundle); // on any dep update, runs the bundler
b.on('log', gutil.log); // output build logs to terminal

function bundle() {
    return b.bundle()
        // log errors if they happen
        .on('error', gutil.log.bind(gutil, 'Browserify Error'))
        .pipe(source('app-bundle.js'))
        // optional, remove if you don't need to buffer file contents
        //.pipe(buffer())
        // optional, remove if you dont want sourcemaps
        //.pipe(sourcemaps.init({loadMaps: true})) // loads map from browserify file
        // Add transformation tasks to the pipeline here.
        //.pipe(sourcemaps.write('./')) // writes .map file
        .pipe(gulp.dest('public/js/app/'));
}
