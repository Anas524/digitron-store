/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        bg: "#070A12",
        panel: "rgba(255,255,255,0.06)",
        panel2: "rgba(255,255,255,0.09)",
        line: "rgba(255,255,255,0.10)",
        primary: "#7C5CFF",   // neon violet
        accent: "#00D4FF",    // cyan
      },
      boxShadow: {
        soft: "0 20px 60px rgba(0,0,0,.45)",
      },
    },
  },
  plugins: [],
};
