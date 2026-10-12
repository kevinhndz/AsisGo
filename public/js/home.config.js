
tailwind.config = {
  theme: {
    extend: {
      colors: {
        'bg-dark': '#0a0a0c',
        'accent-orange': '#f97316',
        'brand-navy': '#1e293b',
      },
      animation: {
        'float': 'float 8s ease-in-out infinite',
        'fade-up': 'fadeUp 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards',
        'pulse-glow': 'pulseGlow 3s infinite',
        'scan': 'scan 2.5s ease-in-out infinite',
        'ping-slow': 'ping 3s cubic-bezier(0, 0, 0.2, 1) infinite',
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0) scale(1)' },
          '50%': { transform: 'translateY(-20px) scale(1.01)' },
        },
        fadeUp: {
          '0%': { opacity: '0', transform: 'translateY(40px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        pulseGlow: {
          '0%, 100%': { boxShadow: '0 0 20px 0 rgba(249, 115, 22, 0.4)' },
          '50%': { boxShadow: '0 0 40px 10px rgba(249, 115, 22, 0.2)' },
        },
        scan: {
          '0%': { top: '0%' },
          '100%': { top: '100%' }
        }
      }
    }
  }
}
