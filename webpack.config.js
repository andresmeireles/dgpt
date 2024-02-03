const path = require("path")

module.exports = {
    mode: "development",
    entry: './assets/js/index.js',
    output: {
        path: path.resolve(__dirname, 'public/js'),
        filename: 'app.js'
    }
}