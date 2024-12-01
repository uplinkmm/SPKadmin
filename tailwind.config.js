/** @type {import('tailwindcss').Config} */
const colors = require("tailwindcss/colors");
export default {
  content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./resources/**/**/*.vue",
        "./node_modules/tw-elements/js/**/*.js",
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require("tw-elements/plugin.cjs"),
  ],
}

