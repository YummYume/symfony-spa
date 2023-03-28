/* eslint-disable import/no-extraneous-dependencies */

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './assets/**/*.(js|ts)',
    './templates/**/*.html.twig',
    './src/Components/**/*.php',
    './src/Form/**/*Field.php',
    './src/Builder/**/*.php',
    './src/Entity/**/*.php',
    './node_modules/flowbite/**/*.js',
    './vendor/tales-from-a-dev/flowbite-bundle/templates/**/*.html.twig',
  ],
  darkMode: 'class',
  theme: {
    extend: {},
  },
  safelist: [],
  plugins: [require('flowbite/plugin')],
};
