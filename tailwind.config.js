const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

// Modify your tailwind.config.js
const disabledCss = {
  pre: false,
  code: false,
  'code::before': false,
  'code::after': false,
  'blockquote p:first-of-type::before': false,
  'blockquote p:last-of-type::after': false,
}

module.exports = {
  darkMode: 'class',
  content: [
    './resources/**/*.antlers.html',
    './resources/**/*.blade.php',
    './resources/**/*.vue',
    './content/**/*.md'
  ],
  theme: {
    extend: {
      fontFamily: {
        'serif': ['eb-garamond', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        ...colors,
        primary: colors.fuchsia,
      },
      typography: (theme) => ({
        DEFAULT: {
          color: theme('colors.primary'),
          css: {
            p: {
              color: theme('colors.zinc.500'),
              fontSize: theme('fontSize.lg'),
              lineHeight: theme('lineHeight.7')
            },
            blockquote: {
              borderColor: theme('colors.primary.400'),
              p: {
                color: theme('colors.zinc.400'),
              },
            },
            'h1, h2': {
              color: theme('colors.black'),
              fontWeight: 'bold',
            },
            a: {
              transition: 'all 100ms ease',
              color: theme('colors.black'),
              fontWeight: theme('fontWeight.medium'),
              textDecoration: 'none',
              borderBottom: '2px',
              borderStyle: 'solid',
              borderColor: theme('colors.primary.500'),
              '&:hover': {
                borderBottom: '4px',
                borderStyle: 'solid',
                borderColor: theme('colors.primary.500'),
              }
            },
            ...disabledCss,
          },
        },
        invert: {
          css: {
            p: {
              color: theme('colors.zinc.400')
            },
            blockquote: {
              borderColor: theme('colors.primary.400'),
              p: {
                color: theme('colors.zinc.400'),
              },
            },
            'h1, h2': {
              color: theme('colors.zinc.200'),
            },
            a: {
              color: theme('colors.zinc.300'),
              borderColor: theme('colors.primary.500'),
              '&:hover': {
                borderColor: theme('colors.primary.500'),
              },
            },
            ...disabledCss,
          },
        },
      }),
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
