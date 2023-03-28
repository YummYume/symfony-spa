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
  safelist: [
    'alert-error',
    'alert-info',
    'alert-success',
    'alert-warning',
    'btn-error',
    'btn-info',
    'btn-success',
    'btn-warning',
    'bg-primary',
    'bg-warning',
    'dark:bg-secondary',
    'rounded-lg',
    'rounded-3xl',
  ],
  plugins: [require('flowbite/plugin')],
};
