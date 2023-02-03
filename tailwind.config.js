/* eslint-disable import/no-extraneous-dependencies */

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './assets/**/*.(js|ts)',
    './templates/**/*.html.twig',
  ],
  theme: {
    extend: {},
  },
  daisyui: {
    themes: [
      {
        mytheme: {
          primary: '#10B981',
          secondary: '#6366F1',
          accent: '#14b8a6',
          neutral: '#191D24',
          'base-100': '#2A303C',
          info: '#22d3ee',
          success: '#34d399',
          warning: '#fbbf24',
          error: '#f87171',
        },
      },
    ],
  },
  safelist: [
    'alert-error',
    'alert-info',
    'alert-success',
    'alert-warning',
  ],
  plugins: [require('daisyui')],
};
