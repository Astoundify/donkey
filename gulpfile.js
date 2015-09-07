var gulp = require('gulp');
var wpPot = require('gulp-wp-pot');
var sort  = require('gulp-sort');

gulp.task( 'pot', function () {
  return gulp.src([ './src/*.php', './src/**/*.php' ])
    .pipe(sort())
    .pipe(wpPot({
       domain: 'donkey',
       destFile: 'donkey.pot',
       lastTranslator: 'Spencer Finnell<spencer@astoundify.com>',
       team: 'Astoundify <contact@astoundify.com>'
     }))
     .pipe(gulp.dest('src/languages'));
});
