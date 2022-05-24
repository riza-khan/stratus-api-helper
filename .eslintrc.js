module.exports = {
    env: {
        browser: true,
        es2021: true,
    },
    extends: ["eslint:recommended", "plugin:vue/essential", "prettier"],
    parserOptions: {
        ecmaVersion: 12,
        sourceType: "module",
    },
    plugins: ["vue"],
    rules: {
        "vue/no-multiple-template-root": 0,
        "vue/multi-word-component-names": [
            "error",
            {
                ignores: ["Dashboard", "Guest"],
            },
        ],
    },
};
