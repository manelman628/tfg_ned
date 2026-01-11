const { src, dest, watch, series} = require('gulp');
const rename = require("gulp-rename");
var exec = require('child_process').exec;
const sass = require('gulp-sass')(require('sass'));
const cleanCSS = require('gulp-clean-css');

function copy_bootstrap_js(){
  return src('./node_modules/bootstrap/dist/js/bootstrap.min.js')
     .pipe(dest('./public/js'));
}
exports.copyBootstrapJs = copy_bootstrap_js;

function copyPopperJs(){
  return src('./node_modules/@popperjs/core/dist/umd/popper.min.js')
     .pipe(dest('./public/js'));
}
exports.copyPopperJs = copyPopperJs;

function copyFontawesomeJs(){
  return src('./node_modules/@fortawesome/fontawesome-free/js/all.min.js')
      .pipe(rename('fontawesome.min.js'))
      .pipe(dest('./public/js'));
}
exports.copyFontawesomeJs = copyFontawesomeJs;

function copyJQuery() {
  return src('node_modules/jquery/dist/jquery.min.js')
    .pipe(dest('public/js'));
}
exports.copyJQuery = copyJQuery;

function copyBootstrapselectCss() {
  return src('node_modules/bootstrap-select/dist/css/bootstrap-select.min.css')
    .pipe(dest('public/css'));
 }
 exports.copyBootstrapselectCss = copyBootstrapselectCss;
 
 function copyBootstrapselectJs() {
  return src('node_modules/bootstrap-select/dist/js/bootstrap-select.min.js')
    .pipe(dest('public/js'));
 }
 exports.copyBootstrapselectJs = copyBootstrapselectJs;

function buildBootstrapCss() {
  return src('./assets/scss/custom-bootstrap.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(rename('bootstrap.min.css'))
    .pipe(cleanCSS({debug: true}, (details) => {
      console.log(`${details.name}: ${details.stats.originalSize}`);
      console.log(`${details.name}: ${details.stats.minifiedSize}`);
    }))
    .pipe(dest('./public/css'));
};
exports.buildBootstrapCss = buildBootstrapCss;

function buildMainCss(){
  return src('./assets/scss/main.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(rename('main.min.css'))
    .pipe(cleanCSS({debug: true}, (details) => {
      console.log(`${details.name}: ${details.stats.originalSize}`);
      console.log(`${details.name}: ${details.stats.minifiedSize}`);
    }))
    .pipe(dest('./public/css'));
}
exports.buildMainCss = buildMainCss;

function copyJsAssets(){
  return src('./assets/js/*.js')
      .pipe(dest('./public/js'));
}
exports.copyJsAssets = copyJsAssets;

exports.build = function(cb){
  exec('node_modules/gulp-cli/bin/gulp.js copyBootstrapJs buildBootstrapCss buildMainCss copyFontawesomeJs copyJsAssets copyPopperJs copyJQuery copyBootstrapselectJs copyBootstrapselectCss',
  function (err, stdout, stderr) {
    console.log(stdout);
    console.log(stderr);
    cb(err);
  });
};

exports.watch = function(){
  watch('./assets/scss/*.scss', series('build'));
  watch('./assets/js/*.js', series('build'));
}
