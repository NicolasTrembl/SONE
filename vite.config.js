import { defineConfig } from 'vite';

export default defineConfig({
  server: {
    open: true,
  },
  build: {
    outDir: "public/dist",
  }
});
