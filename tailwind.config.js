const colors = require('tailwindcss/colors')

module.exports = {
    purge: [],
    darkMode: false, // or 'media' or 'class'
    theme: {
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
