import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import path from 'path'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
  },
  server: {
    port: 5173,
    host: '0.0.0.0',
    proxy: {
      // 代理转发到 ThinkPHP 8 后端
      '/api/admin': {
        target: 'http://127.0.0.1:8001',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api\/admin/, ''),
      },
      // 代理转发到 Hyperf 协程后端
      '/api/hyperf': {
        target: 'http://127.0.0.1:9501',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api\/hyperf/, ''),
        ws: true,
      },
    },
  },
})
