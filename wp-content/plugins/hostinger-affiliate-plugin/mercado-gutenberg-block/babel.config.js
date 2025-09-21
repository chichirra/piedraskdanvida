module.exports = {
    presets: ["@babel/preset-typescript", "@babel/preset-env", "@babel/preset-react"],
    ignore: [/node_modules/],
    overrides: [
        {
            test: [
                "./src",
                "../../amazon-gutenberg-block/src"
            ],
            presets: ["@babel/preset-env", "@babel/preset-react"]
        }
    ]
};
