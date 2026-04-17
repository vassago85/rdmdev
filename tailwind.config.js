import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/**/*.php',
    ],
    theme: {
        container: {
            center: true,
            padding: {
                DEFAULT: '1rem',
                sm: '1.5rem',
                lg: '2rem',
            },
            screens: {
                sm: '640px',
                md: '768px',
                lg: '1024px',
                xl: '1200px',
                '2xl': '1280px',
            },
        },
        extend: {
            colors: {
                brand: {
                    DEFAULT: '#2F5D62',
                    50:  '#eaf2f3',
                    100: '#cfe0e2',
                    200: '#a3c2c5',
                    300: '#74a1a6',
                    400: '#4e8085',
                    500: '#2F5D62',
                    600: '#264b4f',
                    700: '#1e3c3f',
                    800: '#172e31',
                    900: '#112528',
                },
                ink: {
                    DEFAULT: '#3A3F45',
                    50:  '#f3f4f5',
                    100: '#e3e5e7',
                    200: '#c5c9cd',
                    300: '#9ea4ab',
                    400: '#6d737a',
                    500: '#3A3F45',
                    600: '#2e3237',
                    700: '#24272b',
                    800: '#1a1d20',
                    900: '#121416',
                },
                accent: {
                    DEFAULT: '#C9A961',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Barlow Condensed"', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                card: '0 1px 2px rgba(17, 37, 40, 0.04), 0 8px 24px rgba(17, 37, 40, 0.06)',
            },
        },
    },
    plugins: [forms, typography],
};
