/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/**/*.php", 
    "./core/templates/**/*.php",   
    "./modules/**/*.php",
    "./modules/**/widgets/**/*.php",
    "./modules/**/widgets/**/*.js",
    "./public/assets/js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        background: 'var(--background-color)',
        OnBackground: 'var(--on-background)',
        surface: 'var(--surface-color)',
        OnSurface: 'var(--on-surface)',
        primary: 'var(--primary-color)',
        OnPrimary: 'var(--on-primary)',
        secondary: 'var(--secondary-color)',
        OnSecondary: 'var(--on-secondary)',
        tertiary: 'var(--tertiary-color)',
        OnTertiary: 'var(--on-tertiary)',
        error: 'var(--error-color)',
        OnError: 'var(--on-error)',
      },
    },
  },
  variants: {
    extend: {
      backgroundColor: ['dark'],
      textColor: ['dark'],
    },
  },
  plugins: [],
  darkMode: 'class',
};
