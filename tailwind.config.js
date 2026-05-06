/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './app/Livewire/**/*.php',
        './app/Http/Livewire/**/*.php',
    ],
    corePlugins: {
        // Disable preflight so Bootstrap CSS is not overridden
        preflight: false,
    },
    theme: {
        extend: {
            colors: {
                brand: {
                    50:  '#fdf2f2',
                    100: '#fce4e4',
                    200: '#f9bcbc',
                    300: '#f38e8e',
                    400: '#eb5757',
                    500: '#dd2b2b',
                    600: '#c01a1a',
                    700: '#7c2d2d',
                    800: '#5c1f1f',
                    900: '#3d1212',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
