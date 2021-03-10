const defaultTheme = require('tailwindcss/defaultTheme')
module.exports = {
  purge:false,
  theme: {
    extend: {
      fontFamily: {
        'title': [
          '"Exo 2"',
        ],
        'normal': [
          'Montserrat'
        ],
        'sarabun': 'Sarabun',
        'nunito': 'Nunito',
        'exo-2': 'Exo 2',
      },
      height: {
        72: '18rem',
        80: '20rem',
      },
      minHeight: {
        ...defaultTheme.spacing,
        30: '30%',
        99: '99%'
      },
      maxHeight: {

        ...defaultTheme.spacing,
        80: '80%',
        '1/2': '50%',
        '1/3': '33.333333%',
        '2/3': '66.666667%',
        '1/4': '25%',
        '2/4': '50%',
        '3/4': '75%',
        '1/5': '20%',
        '2/5': '40%',
        '3/5': '60%',
        '4/5': '80%',
        '1/6': '16.666667%',
        '2/6': '33.333333%',
        '3/6': '50%',
        '4/6': '66.666667%',
        '5/6': '83.333333%',
        '1/12': '8.333333%',
        '2/12': '16.666667%',
        '3/12': '25%',
        '4/12': '33.333333%',
        '5/12': '41.666667%',
        '6/12': '50%',
        '7/12': '58.333333%',
        '8/12': '66.666667%',
        '9/12': '75%',
        '10/12': '83.333333%',
        '11/12': '90.666667%',
      },
      minWidth: {
        ...defaultTheme.spacing,
        30: '30%',
      },
      maxWidth: {
        ...defaultTheme.spacing,
        80: '80%'
      },
      flex: {
        '2': '2 2 0%',
        '3': '3 3 0%',
        '4': '4 4 0%',
        '5': '5 5 0%',
        '6': '6 6 0%',
        '7': '7 7 0%',
        '8': '8 8 0%',
        '9': '9 9 0%',
        '10': '10 10 0%',
        '11': '11 11 0%',
        '12': '12 12 0%',
      },
      colors: {
        grey: '#444444',
        'ap-blue': '#1A6BF7',
        'ap-gray': '#77838E',
        'ap-red': '#F82C60',
        'ap-cream': '#F7F7F7',
        'ap-light-gray': '#DDDDDD',
        gray: {
          light: '#F8F8F8',
          lighter: '#DDDDDD',
          medium: '#B3B3B3',
          darker: '#888888',
          dark: '#444444',
          ...defaultTheme.colors.gray
        },
        'gray-blueish': {
          light: '#D1D8E0',
          dark: '#A5B1C2'
        },
        blue: {
          light: '#ECF9FF',
          lighter: '#00B2FF',
          medium: '#1A6BF7',
          darker: '#1450B8',
          ...defaultTheme.colors.blue
        },
        yellow: {
          light: '#FEFDF9',
          lighter: '#FFF8D2',
          medium: '#FFC539',
          dark: '#FFBB44',
          ...defaultTheme.colors.yellow
        }
      },
      boxShadow: {
        'tools': '0px 7px 7px rgba(0, 0, 0, 0.15)',
        'canvas': '0px 7px 40px rgba(0, 0, 0, 0.15)',
        'head': '0px 7px 10px rgba(0, 0, 0, 0.15)',
        header: '0px 4px 4px rgba(0, 0, 0, 0.25)',
        challenge: '0px 4px 15px rgba(165, 177, 194, 0.4)',
      },
      spacing: {
        '1/2': '50%',
        '1/3': '33.333333%',
        '2/3': '66.666667%',
        '1/4': '25%',
        '2/4': '50%',
        '3/4': '75%',
        '1/5': '20%',
        '2/5': '40%',
        '3/5': '60%',
        '4/5': '80%',
        '1/6': '16.666667%',
        '2/6': '33.333333%',
        '3/6': '50%',
        '4/6': '66.666667%',
        '5/6': '83.333333%',
        '1/12': '8.333333%',
        '2/12': '16.666667%',
        '3/12': '25%',
        '4/12': '33.333333%',
        '5/12': '41.666667%',
        '6/12': '50%',
        '7/12': '58.333333%',
        '8/12': '66.666667%',
        '9/12': '75%',
        '10/12': '83.333333%',
        '11/12': '91.666667%',
        '16-9': '56.25%'
      },
    },
    customForms: theme => ({
      default: {
        'input, checkbox, radio, textarea': {
          '&:focus': {
            boxShadow: undefined,
            borderColor: undefined,
          },
        }
      }
    }),
  },
  variants: {
    textColor: ['responsive', 'hover', 'focus', 'active'],
    display: ['responsive', 'hover'],
    zIndex: ['responsive', 'hover'],
  },
  plugins: [
    require('@tailwindcss/custom-forms'),
    function ({
                addUtilities
              }) {
      const new_utilities = {
        '.no-select': {
          WebkitTouchCallout: 'none',
          /* iOS Safari */
          WebkitUserselect: 'none',
          /* Safari */
          KhtmlUserSelect: 'none',
          /* Konqueror HTML */
          MozUserSelect: 'none',
          /* Old versions of Firefox */
          MsUserSelect: 'none',
          /* Internet Explorer/Edge */
          userSelect: 'none',
          /* Non-prefixed version, currently supported by Chrome, Opera and Firefox */
        }
      };
      addUtilities(new_utilities);
    }
  ],
}
