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
        sans: ['Rajdhani', 'sans-serif'],
        display: ['Orbitron', 'sans-serif'],
      },
      colors: {
        bg: "#070A12",
        panel: "rgba(255,255,255,0.06)",
        panel2: "rgba(255,255,255,0.09)",
        line: "rgba(255,255,255,0.10)",

        brand: {
          dark: '#050505',
          panel: '#0a0a0a',
          accent: '#00f0ff',
          secondary: '#7000ff',
          danger: '#ff003c',
        },

        primary: "#7C5CFF",
        accent: "#00D4FF",
      },
      backgroundImage: {
        'grid-pattern':
          "linear-gradient(to right, #1f2937 1px, transparent 1px), linear-gradient(to bottom, #1f2937 1px, transparent 1px)",
      },
      boxShadow: {
        soft: "0 20px 60px rgba(0,0,0,.45)",
      },
    },
  },
  plugins: [],
};
