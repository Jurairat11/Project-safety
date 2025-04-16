import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
    ],

    darkMode: 'class',

    safelist: [
        {
            pattern: /(bg|text|border)-(red|green|blue|yellow|gray)-(100|200|300|500|700|900)/,
            variants: ['dark'],
        },
        // เพิ่มถ้าคุณใช้ class แบบ !bg-red-100
        'bg-blue-100', 'text-blue-900',
        'bg-yellow-100', 'text-yellow-900',
        'bg-green-100', 'text-green-900',
        'bg-red-100', 'text-red-900',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
}
