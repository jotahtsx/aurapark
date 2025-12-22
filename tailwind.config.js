import daisyui from "daisyui";

export default {
    darkMode: "class", 
    
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Raleway", "sans-serif"],
            },
        },
    },
    daisyui: {
        themes: [
            {
                light: {
                    "primary": "#3b82f6",
                    "base-100": "#ffffff",
                    "base-200": "#f8fafc",
                    "base-300": "#e2e8f0",
                    "base-content": "#0f172a",
                },
                dark: {
                    "primary": "#60a5fa",
                    "base-100": "#0f172a",
                    "base-200": "#1e293b",
                    "base-300": "#334155",
                    "base-content": "#f8fafc",
                },
            },
        ],
    },
    plugins: [daisyui],
};