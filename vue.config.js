// vue.config.js
module.exports = {
  devServer: {
    host: 'sms.test',
    port: 81,
    proxy: {
      '^/api': {
        target: 'https://sms.test:80',
        ws: true,
        changeOrigin: true
      }
    }
  }
}