import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Montserrat', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                teal: {
                    50:  '#e6f3f3',
                    100: '#b3d9d9',
                    200: '#80bfbf',
                    300: '#4da6a6',
                    400: '#1a8c8c',
                    500: '#008080',
                    600: '#007373',
                    700: '#006666',
                    800: '#005959',
                    900: '#003333',
                },
                gold: {
                    400: '#FFD700',
                    500: '#F5C900',
                },
            },
        },
    },

    plugins: [forms],
};
