/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    plugins: [
        require('daisyui'),
    ],
    daisyui: {
        themes: ["light", "dark"],
    },
};
