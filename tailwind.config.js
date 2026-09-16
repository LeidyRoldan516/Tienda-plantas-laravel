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
            colors: {
                marca: {
                    50: '#F3FBF7',
                    100: '#E4F6EE',
                    200: '#C5EBDA',
                    300: '#82D1B1',
                    400: '#4DB890',
                    500: '#239B6D',
                    600: '#1C7F59',
                    700: '#166449',
                    800: '#0F4A36',
                    900: '#0B2421',
                },
                lienzo: '#F7F9F8',
            },
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                suave: '0 12px 40px -16px rgba(11, 36, 33, 0.18)',
            },
        },
    },

    plugins: [forms],
};
