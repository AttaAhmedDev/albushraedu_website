import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
  base: '/OnlineLearningPlatform/',
  server: {
    proxy: {
      '/OnlineLearningPlatform/api': {
        target: 'http://localhost',
        changeOrigin: true,
      },
    },
  },
})
