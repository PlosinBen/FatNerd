const colors = require('tailwindcss/colors')

module.exports = {
    purge: {
        mode: 'jit',
        content: [
            './resources/**/*.blade.php',
            './resources/**/*.vue',
        ],
    },
    darkMode: false, // or 'media' or 'class'
    theme: {
        flexGrow: {
            DEFAULT: 1,
            '0': 0,
            '1': 1,
            '2': 2,
            '3': 3,
            '4': 4,
        },
        colors: {
            ...colors
        },
        fontFamily: {
            sans: ['Noto Sans TC', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            serif: ['ui-serif', 'Georgia'],
            mono: ['ui-monospace', 'SFMono-Regular'],
        },
        extend: {},
    },
    variants: {
        extend: {
            fontWeight: ['hover', 'focus']
        },
    },
    plugins: [],
}
