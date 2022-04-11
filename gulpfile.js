let preprocessor = 'sass'; // Имя препроцессора для CSS (sass, less, styl);
let fileswatch   = 'php,yaml,html,htm,txt,json,md,woff2' // Список расширений,
                                                // для которых требуется перезагрузка браузера и надзор.

// Прокси адрес для BrowserSync
const proxyPath = 'http://jetbox.lc';

// Подключение пакетов
import pkg from 'gulp'
const { gulp, src, dest, parallel, series, watch } = pkg

import browserSync   from 'browser-sync'
import bssi          from 'browsersync-ssi'
import ssi           from 'ssi'
import webpackStream from 'webpack-stream'
import webpack       from 'webpack'
import TerserPlugin  from 'terser-webpack-plugin'
import gulpSass      from 'gulp-sass'
import dartSass      from 'sass'
import sassglob      from 'gulp-sass-glob'
const  sass          = gulpSass(dartSass)
import less          from 'gulp-less'
import lessglob      from 'gulp-less-glob'
import styl          from 'gulp-stylus'
import stylglob      from 'gulp-noop'
import postCss       from 'gulp-postcss'
import cssnano       from 'cssnano'
import autoprefixer  from 'autoprefixer'
import imagemin      from 'gulp-imagemin'
import changed       from 'gulp-changed'
import concat        from 'gulp-concat'
import rsync         from 'gulp-rsync'
import del           from 'del'
import uglifyI 			 from 'gulp-uglify-es'

const uglify = uglifyI.default;


// Прокси-сервер
function browsersync() {
	browserSync.init({
    proxy : proxyPath,
		notify: false,
		online: true,
		// tunnel: 'yousutename', // Attempt to use the URL https://yousutename.loca.lt
	})
}

// Сборка JS стэка скриптов
function stack() {
	return src([
    'assets/js/libs/**/*',
    ])
		.pipe(concat('stack.min.js'))
		.pipe(uglify())
		.pipe(dest('dist'))
		.pipe(browserSync.stream())
}


// Обработка скриптов
function scripts() {
	return src([
    'assets/js/modules/**/*.js',
    'assets/js/app.js',
    ])
		.pipe(concat('bundle.min.js'))
		.pipe(uglify())
		.pipe(dest('dist'))
		.pipe(browserSync.stream())
}


// стили
function styles() {
  return src([
		`assets/${preprocessor}/main.scss`
	])
  .pipe(eval(`${preprocessor}glob`)())
  .pipe(eval(preprocessor)({ 'include css': true }))
  .pipe(postCss([
    autoprefixer({ grid: 'autoplace' }),
    cssnano({ preset: ['default', { discardComments: { removeAll: true } }] })
  ]))
  .pipe(concat('bundle.min.css'))
  .pipe(dest('dist'))
  .pipe(browserSync.stream())
}

// Изображения
function images() {
  return src('assets/img/**/*')
  .pipe(imagemin())
  .pipe(dest('dist/i'))
  .pipe(browserSync.stream());
}

// шрифты
function fonts() {
  return src([
    'assets/fonts/**/*'
  ])
  .pipe(dest('dist/f'))
  .pipe(browserSync.stream());
}


// очистка директории сборок
async function cleandist() {
	del('dist/**/*', { force: true })
}


// Запуск наключения за файлами
function startwatch() {
	watch('assets/fonts/**/*', { usePolling: true }, fonts);

	watch(`assets/${preprocessor}/**/*`, { usePolling: true }, styles);

	watch(['assets/js/**/*.js', '!assets/js/libs/**/*'], { usePolling: true }, scripts);
	watch(['assets/js/libs/**/*'], { usePolling: true }, stack);

	watch('assets/img/**/*', { usePolling: true }, images);

	watch([`view/**/*.{${fileswatch}}`, 'index.php', '!node_modules/**/*', '!dist/**/*'], { usePolling: true }).on('change', browserSync.reload);
}

export { scripts, styles, images, fonts }
export let assets = series(stack, scripts, styles, images, fonts)
export let build = series(cleandist, images, scripts, styles, fonts, stack)
export default series(cleandist, stack, scripts, styles, images, fonts, parallel(browsersync, startwatch))
