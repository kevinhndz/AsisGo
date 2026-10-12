
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    "background":"#131313",
                    "surface":"#131313",
                    "surface-container":"#201f1f",
                    "surface-container-low":"#1c1b1b",
                    "surface-container-high":"#2a2a2a",
                    "surface-container-highest":"#353534",
                    "surface-container-lowest":"#0e0e0e",
                    "on-background":"#e5e2e1",
                    "on-surface":"#e5e2e1",
                    "on-surface-variant":"#d7c2ba",
                    "primary":"#ffb693",
                    "primary-container":"#ffb693",
                    "on-primary":"#50240b",
                    "on-primary-container":"#7a452a",
                    "primary-fixed":"#ffdbcc",
                    "on-primary-fixed":"#341000",
                    "on-primary-fixed-variant":"#6c3a1f",
                    "outline":"#9f8d85",
                    "outline-variant":"#52443d",
                    "error":"#ffb4ab",
                    "on-error":"#690005",
                    "success":"#a3e6b5"
                },
                fontFamily: {
                    "display": ["Hanken Grotesk", "sans-serif"],
                    "title": ["Hanken Grotesk", "sans-serif"],
                    "mono-caps": ["JetBrains Mono", "monospace"],
                    body: ["Inter", "sans-serif"],
                },
                fontSize: {
                    "label-caps": ["10px", { lineHeight:"12px", letterSpacing:"0.1em", fontWeight:"700" }],
                    "title-md": ["18px", { lineHeight:"24px", letterSpacing:"-0.01em", fontWeight:"600" }],
                    "display-lg": ["46px", { lineHeight:"50px", letterSpacing:"-0.03em", fontWeight:"700" }],
                },
                spacing: {
                    "gutter": "16px",
                    "grid-margin": "32px",
                    "container-padding": "24px"
                }
            }
        }
    }
    