/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: 'var(--color-primary, #3b82f6)',
        secondary: 'var(--color-secondary, #10b981)',
        danger: 'var(--color-danger, #ef4444)',
        warning: 'var(--color-warning, #f59e0b)',
        success: 'var(--color-success, #10b981)',
        info: 'var(--color-info, #3b82f6)',
      },
      spacing: {
        4: '4px',
        8: '8px',
        12: '12px',
        16: '16px',
        24: '24px',
        32: '32px',
        48: '48px',
        64: '64px',
      },
      fontSize: {
        xs: ['12px', { lineHeight: '16px' }],
        sm: ['14px', { lineHeight: '20px' }],
        base: ['16px', { lineHeight: '24px' }],
        lg: ['18px', { lineHeight: '28px' }],
        xl: ['20px', { lineHeight: '28px' }],
        '2xl': ['24px', { lineHeight: '32px' }],
        '3xl': ['30px', { lineHeight: '36px' }],
      },
    },
  },
  plugins: [],
}
