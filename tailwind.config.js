const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
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
      typography: (theme) => ({
        DEFAULT: {
          color: theme('colors.violet'),
          css: {
            p: {
              color: theme('colors.zinc.500'),
              fontSize: theme('fontSize.lg'),
              lineHeight: theme('lineHeight.7')
            },
            blockquote: {
              borderColor: theme('colors.violet.400'),
              p: {
                color: theme('colors.zinc.400'),
              },
            },
            h1: {
              fontFamily: theme('fontFamily.serif').join(', '),
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
              borderColor: theme('colors.violet.500'),
              '&:hover': {
                borderBottom: '4px',
                borderStyle: 'solid',
                borderColor: theme('colors.violet.500'),
              }
            }
          },
        },
        invert: {
          css: {
            p: {
              color: theme('colors.zinc.400')
            },
            blockquote: {
              borderColor: theme('colors.violet.400'),
              p: {
                color: theme('colors.zinc.400'),
              },
            },
            'h1, h2': {
              color: theme('colors.zinc.200'),
            },
            a: {
              color: theme('colors.zinc.300'),
              borderColor: theme('colors.violet.500'),
              '&:hover': {
                borderColor: theme('colors.violet.500'),
              },
            }
          },
        },
      }),
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
