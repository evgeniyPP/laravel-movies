module.exports = {
    theme: {
        extend: {
            width: {
                "96": "24rem"
            }
        },
        spinner: () => ({
            default: {
                color: "#dae1e7",
                size: "1em",
                border: "2px",
                speed: "500ms"
            }
        })
    },
    variants: {
        spinner: ["responsive"]
    },
    plugins: [require("tailwindcss-spinner")()]
};
