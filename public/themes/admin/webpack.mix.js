const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
 
 
mix.setPublicPath("public/themes/admin")
    .js(`${__dirname}/js/app.js`, "js")
    .vue()
    .sass(`${__dirname}/sass/app.scss`, "css");

// require(`${__dirname}/themes/admin/webpack.mix.js`);
// require(`${__dirname}/themes/tailwind/webpack.mix.js`);

//to alternative between themes : through -> npm run dev --theme=admin-child
let theme = process.env.npm_config_theme;

if(theme) {
   require(`${__dirname}/themes/${theme}/webpack.mix.js`);
} else {
    // default theme to compile if theme is not specified
  require(`${__dirname}/themes/admin/webpack.mix.js`);
}