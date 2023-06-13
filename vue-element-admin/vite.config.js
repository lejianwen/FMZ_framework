import { defineConfig } from 'vite'
import * as path from 'path'
import * as dotenv from 'dotenv'
import * as fs from 'fs'
import vue from '@vitejs/plugin-vue'

const NODE_ENV = process.env.NODE_ENV || 'development'
const envFile = `.env.${NODE_ENV}`
const envConfig = dotenv.parse(fs.readFileSync(envFile))
for (const k in envConfig) {
  process.env[k] = envConfig[k]
}

let alias = {
  '@': path.resolve(__dirname, './src'),
  'vue$': 'vue/dist/vue.runtime.esm-bundler.js',
}

/** @type {import('vite').UserConfig} */
const conf = {
  base: './', // index.html文件所在位置
  root: './', // js导入的资源路径，src
  server: {
    open: true,
    port: process.env.VITE_DEV_PORT,
    proxy: {
      [process.env.VITE_SERVER_API]: {
        target: process.env.VITE_SERVER_PATH,
        changeOrigin: true,
      },
    },
  },
  preview:{
    port: process.env.VITE_PREVIEW_PORT,
    proxy: {
      [process.env.VITE_SERVER_API]: {
        target: process.env.VITE_SERVER_PATH,
        rewrite: path => path.replace(/^\/api/, '/mock'), //为了模拟
        changeOrigin: true,
      },
    },
  },
  build: {
    target: 'es2015',
    minify: 'terser', // 是否进行压缩,boolean | 'terser' | 'esbuild',默认使用terser
    manifest: false, // 是否产出maifest.json
    sourcemap: false, // 是否产出soucemap.json
    outDir: 'dist', // 产出目录
  },
  css: {
    preprocessorOptions: {
      less: {
        // 支持内联 JavaScript
        javascriptEnabled: true,
      },
      preprocessorOptions: {
        scss: {
          javascriptEnabled: true,
        },
      },
    },
  },
  resolve: {
    alias,
  },
  plugins: [
    vue(),
  ],
}

// https://vitejs.dev/config/
export default defineConfig(conf)
