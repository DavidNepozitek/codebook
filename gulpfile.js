var gulp = require("gulp");
var browserSync = require("browser-sync").create();
var less = require("gulp-less");
var rename = require("gulp-rename");
var concat = require("gulp-concat");
var cleanCSS = require("gulp-clean-css");
var uglify = require("gulp-uglify");
var plumber = require("gulp-plumber");
var autoprefixer = require("gulp-autoprefixer");

var plumberOpts = {
    errorHandler: function(error) {
        console.log(error.toString());
        this.emit('end');
    }
};


gulp.task("watch", ["css", "css-libs", "js", "js-libs"], function() {

    browserSync.init({
        proxy: {
            target: "localhost/codebook"
        }
    });

    gulp.watch("assets/styles/less/**/*", ["css"]);
    gulp.watch("assets/styles/libs/*.css", ["css-libs"]);
    gulp.watch("assets/scripts/libs/*.js", ["js-watch"]);
    gulp.watch("assets/scripts/js/**/*", ["js-watch"]);
    gulp.watch("assets/scripts/*.js").on("change", browserSync.reload);
    gulp.watch("app/**/*").on("change", browserSync.reload);
});

gulp.task("css", function() {
    return gulp.src("assets/styles/less/core.less")
        .pipe(plumber(plumberOpts))
        .pipe(less())
        .pipe(autoprefixer({
            browsers: ["last 2 versions"],
            cascade: false
        }))
        .pipe(cleanCSS({compatibility: "ie8"}))
        .pipe(rename("main.min.css"))
        .pipe(gulp.dest("assets/styles"))
        .pipe(browserSync.stream());
});

gulp.task("css-libs", function() {
    return gulp.src(["assets/styles/libs/*"])
        .pipe(concat("libs.min.css"))
        .pipe(cleanCSS({compatibility: "ie8"}))
        .pipe(gulp.dest("assets/styles"));
});

gulp.task("js", function() {
    return gulp.src([
            "assets/scripts/js/main.js",
            "assets/scripts/js/components/*"
        ])
        .pipe(plumber(plumberOpts))
        .pipe(concat("scripts.min.js"))
        .pipe(uglify())
        .pipe(gulp.dest("assets/scripts"));
});

gulp.task("js-libs", function() {
    return gulp.src([
            "assets/scripts/libs/jquery.min.js",
            "assets/scripts/libs/bootstrap.min.js"
        ])
        .pipe(concat("libs.min.js"))
        .pipe(uglify())
        .pipe(gulp.dest("assets/scripts"));
});

gulp.task("js-watch", ["js", "js-libs"], function (done) {
    browserSync.reload();
    done();
});

gulp.task("default", ["watch"]);