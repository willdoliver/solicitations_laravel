let mix = require("laravel-mix");

mix.js("resources/js/app.js", "public/js").postCss(
    "resources/css/app.css",
    "public/css",
    [require("autoprefixer")]
);

// Add versioning for cache busting in production
if (mix.inProduction()) {
    mix.version();
}
