import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary-yellow': '#FFCC00',
                'primary-gray': '#2B2B2B',
                'primary-dark-gray': '#1E1E21',
            },
            animation: {
                'fade-in-right': 'fadeInRight 1s ease-out',
            },
        },
    },

    plugins: [forms, typography],
};
