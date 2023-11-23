/** @type {import('tailwindcss').Config} */
export default {
  prefix: 't-',
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
       colors:{
         logoBlue: '#5176E4',
         logoBluePartner: '#70B6E4',
         logoOrange: '#FCB053',
         logoYellow: '#F5D04C',
         background: '#f2f2f2',
         available: "rgba(75,192,192,0.5)"
       }
    },
  },
  plugins: [],
}

