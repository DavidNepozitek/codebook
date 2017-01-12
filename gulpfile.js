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


gulp.task("watch", ["css-front", "css-back", "css-libs", "js-front", "js-back", "js-libs"], function() {

    browserSync.init({
        proxy: {
            target: "http://localhost:8080/codebook"
        }
    });

    gulp.watch("assets/styles/front/**/*", ["css-front"]);
    gulp.watch("assets/styles/back/**/*", ["css-back"]);
    gulp.watch("assets/styles/main.less", ["css-back", "css-front"]);
    gulp.watch("assets/styles/libs/*.css", ["css-libs"]);
    gulp.watch("assets/scripts/libs/*.js", ["js-libs"]);
    gulp.watch("assets/scripts/front/**/*", ["js-front"]);
    gulp.watch("assets/scripts/back/**/*", ["js-back"]);
    gulp.watch("assets/scripts/main.js", ["js-back", "js-front"]);
    gulp.watch("assets/scripts/*.js").on("change", browserSync.reload);
    gulp.watch("app/**/*").on("change", browserSync.reload);
});

gulp.task("css-back", function() {
    return gulp.src("assets/styles/back/core.less")
        .pipe(plumber(plumberOpts))
        .pipe(less())
        .pipe(autoprefixer({
            browsers: ["last 2 versions"],
            cascade: false
        }))
        .pipe(cleanCSS({compatibility: "ie8"}))
        .pipe(rename("back.min.css"))
        .pipe(gulp.dest("assets/styles"))
        .pipe(browserSync.stream());
});

gulp.task("css-front", function() {
    return gulp.src("assets/styles/front/core.less")
        .pipe(plumber(plumberOpts))
        .pipe(less())
        .pipe(autoprefixer({
            browsers: ["last 2 versions"],
            cascade: false
        }))
        .pipe(cleanCSS({compatibility: "ie8"}))
        .pipe(rename("front.min.css"))
        .pipe(gulp.dest("assets/styles"))
        .pipe(browserSync.stream());
});

gulp.task("css-libs", function() {
    return gulp.src(["assets/styles/libs/*"])
        .pipe(plumber(plumberOpts))
        .pipe(concat("libs.min.css"))
        .pipe(cleanCSS({compatibility: "ie8"}))
        .pipe(gulp.dest("assets/styles"));
});

gulp.task("js-front", function() {
    return gulp.src([
            "assets/scripts/main.js",
            "assets/scripts/front/*",
            "assets/scripts/front/components/*"
        ])
        .pipe(plumber(plumberOpts))
        .pipe(concat("front.min.js"))
        .pipe(uglify())
        .pipe(gulp.dest("assets/scripts"));
});


gulp.task("js-back", function() {
    return gulp.src([
            "assets/scripts/main.js",
            "assets/scripts/back/*",
            "assets/scripts/back/components/grido.js",
            "assets/scripts/back/components/grido.nette.ajax.js",
            "assets/scripts/back/components/grido.bootstrap.paginator.js",
            "assets/scripts/back/components/login.js",
            "assets/scripts/back/components/tags.js",
            "assets/scripts/back/components/navigation.js",
            "assets/scripts/back/components/tutorial.js",
            "assets/scripts/back/components/ga.js",
            "assets/scripts/back/components/onload.js"
        ])
        .pipe(plumber(plumberOpts))
        .pipe(concat("back.min.js"))
        .pipe(uglify())
        .pipe(gulp.dest("assets/scripts"));
});

gulp.task("js-libs", function() {
    return gulp.src([
            "assets/scripts/libs/jquery-1.11.2.min.js",
            "assets/scripts/libs/bootstrap.min.js",
            "assets/scripts/libs/highlight.pack.js",
            "assets/scripts/libs/nette.ajax.js",
            "assets/scripts/libs/history.ajax.js"
        ])
        .pipe(concat("libs.min.js"))
        .pipe(uglify())
        .pipe(gulp.dest("assets/scripts"));
});

gulp.task("default", ["watch"]);