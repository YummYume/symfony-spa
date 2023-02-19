/* eslint-disable import/no-extraneous-dependencies */

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './assets/**/*.(js|ts)',
    './templates/**/*.html.twig',
  ],
  darkMode: 'class',
  theme: {
    extend: {},
  },
  daisyui: {
    themes: [
      {
        light: {
          ...require('daisyui/src/colors/themes')['[data-theme=light]'],
          primary: '#10B981',
          secondary: '#6366F1',
        },
        dark: {
          ...require('daisyui/src/colors/themes')['[data-theme=dark]'],
          primary: '#10B981',
          secondary: '#6366F1',
        },
      },
    ],
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
  plugins: [require('daisyui')],
};
