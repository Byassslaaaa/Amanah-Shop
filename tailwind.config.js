/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: [
                    "Lato",
                    "ui-sans-serif",
                    "system-ui",
                    "-apple-system",
                    "BlinkMacSystemFont",
                    "sans-serif",
                ],
                heading: [
                    "Quicksand",
                    "ui-sans-serif",
                    "system-ui",
                    "-apple-system",
                    "BlinkMacSystemFont",
                    "sans-serif",
                ],
            },
            colors: {
                // Nest Theme Colors
                primary: {
                    50: "#f0fdf4",
                    100: "#dcfce7",
                    200: "#bbf7d0",
                    300: "#86efac",
                    400: "#4ade80",
                    500: "#3BB77E", // Nest Green
                    600: "#2f9960",
                    700: "#15803d",
                    800: "#166534",
                    900: "#14532d",
                },
                secondary: {
                    50: "#f8fafc",
                    100: "#f1f5f9",
                    200: "#e2e8f0",
                    300: "#cbd5e1",
                    400: "#94a3b8",
                    500: "#64748b",
                    600: "#475569",
                    700: "#334155",
                    800: "#253D4E", // Nest Navy
                    900: "#0f172a",
                },
                accent: {
                    50: "#fffbeb",
                    100: "#fef3c7",
                    200: "#fde68a",
                    300: "#fcd34d",
                    400: "#fbbf24",
                    500: "#FDC040", // Nest Yellow
                    600: "#d97706",
                    700: "#b45309",
                    800: "#92400e",
                    900: "#78350f",
                },
                "nest-green": "#3BB77E",
                "nest-navy": "#253D4E",
                "nest-yellow": "#FDC040",
                "nest-red": "#F74B81",
                "nest-blue": "#3B82F6",
                light: "#fafafa",
                cream: "#f5ebdd",
            },
            borderRadius: {
                nest: "15px",
            },
            boxShadow: {
                nest: "20px 20px 54px rgba(0, 0, 0, 0.03)",
                "nest-hover": "20px 20px 54px rgba(0, 0, 0, 0.08)",
            },
        },
    },
    plugins: [require("@tailwindcss/forms")],
};
