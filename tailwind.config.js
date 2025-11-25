import daisyui from "daisyui";

export default {
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
                    "primary": "#6366f1",
                    "base-100": "#f9fafb",
                    "base-200": "#e5e7eb",
                    "base-content": "#111827",
                    "accent": "#fbbf24",
                    "neutral": "#d1d5db",
                },
                dark: {
                    "primary": "#818cf8",
                    "base-100": "#1f2937",
                    "base-200": "#374151",
                    "base-content": "#f3f4f6",
                    "accent": "#fbbf24",
                    "neutral": "#4b5563",
                },
            },
        ],
    },
    plugins: [daisyui],
};
