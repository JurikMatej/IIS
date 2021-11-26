const path = require('path');

module.exports = {
    mode: 'development', // TODO rewrite to 'production'
    // Clear bundled code
    devtool: false,
    // App entrypoint
    entry: './public/assets/js/modules/main.js',
    // Transpiled & bundled output (must be absolute path)
    output: {
        path: path.resolve(__dirname, 'public/assets/js'),
        filename: 'bundle.js'
    },
    // Loaders (import system)
    module: {
        rules: [
            // js loader
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                },
            },
            // css loader
            {
                test: /\.css$/,
                use: [
                    {
                        loader: 'css-loader',
                        options: {
                            modules: true
                        }
                    }
                ]
            },
            // scss loader
            {
                test: /\.scss$/,
                use: [ // In order from the last index - parse scss, parse css, inject styles
                    'style-loader',
                    'css-loader',
                    'sass-loader'
                ]
            }
        ]
    },
    resolve: {
        extensions: [
            '.js',
        ]
    }
}