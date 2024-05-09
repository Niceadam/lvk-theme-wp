const defaultTheme = require('tailwindcss/defaultTheme')

export default {
  content: [
    './theme/views/**/*.twig',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter var', ...defaultTheme.fontFamily.sans],
      }
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
