module.exports = {
  mode: 'jit',
  content: ['./public/**/*.{php, html, js}', './public/template/**/*.{php, html, js}', './node_modules/flowbite/**/*.js'],
  darkMode: "class", // or 'media' or 'class'
  theme: {
    fontFamily: {
      'sans': ['Ubuntu', 'Sans-serif']
    },
    extend: {
      container: {
        screens: {
          DEFAULT: '100%',
          sm: '640px',
          md: '768px',
          lg: '1024px',
          xl: '1280px',
          '2xl': '1280px',
        }
      },
      height: {
        '120': '30rem',
        '160': '40rem',
        '200': '50rem',
        '240': '60rem',
      },
      width: {
        '120': '30rem',
        '160': '40rem',
        '200': '50rem',
        '240': '60rem',
        '280': '70rem',
        '320': '80rem',
        'inherit': 'inherit',
      },
      extend: {
        spacing: {
          '72': '18rem',
          '84': '21rem',
          '96': '24rem',
        },
      }
    }
  },
  variants: {
    extend: {
     width: ['hover', 'focus'],
    }
  },
  plugins: [
    require('flowbite/plugin')
  ]
}
