const plugin = require("tailwindcss/plugin");

/**
 * TAILWIND CONFIG - PRODUCTION READY
 * 
 * Refactored theo chuẩn frontend production:
 * - Không scale layout theo viewport
 * - Không ép root font-size
 * - Spacing dùng rem chuẩn (base 16px)
 * - Container max-width: 1512px, center với margin auto
 * - Typography: rem cho body, clamp CHỈ cho heading lớn nếu cần
 * - Breakpoints rõ ràng, dễ maintain
 */

module.exports = {
  content: [
    "./src/dist/**/*.{html,js}",
    "./src/pages/**/*.{html,pug}",
    "./src/components/**/*.{html,pug,sass,js}",
  ],
  theme: {
    // ==========================================================
    // BREAKPOINTS - Chuẩn, rõ ràng, không magic number
    // ==========================================================
    screens: {
      xs: "320px",
      sm: "576px",
      md: "768px",
      lg: "1024px",
      xl: "1200px",
      "2xl": "1512px", // Design reference
      "-xs": { max: "409.98px" },
      "-sm": { max: "575.98px" },
      "-md": { max: "767.98px" },
      "-lg": { max: "1023.98px" },
      "-xl": { max: "1199.98px" },
    },

    // ==========================================================
    // CONTAINER - Fixed max-width, centered
    // ==========================================================
    container: {
      center: true,
      padding: {
        DEFAULT: "1rem",      // 16px mobile
        sm: "1.5rem",         // 24px
        md: "2rem",           // 32px
        lg: "2.5rem",         // 40px
        xl: "2.5rem",         // 40px
        "2xl": "2.5rem",      // 40px
      },
      screens: {
        xs: "100%",
        sm: "100%",
        md: "100%",
        lg: "100%",
        xl: "100%",
        "2xl": "1512px",      // Fixed max-width at design reference
      },
    },

    // ==========================================================
    // SPACING - rem chuẩn dựa trên 16px base
    // Mapping: key → actual px value (rem = px/16)
    // ==========================================================
    spacing: {
      0: "0",
      px: "1px",
      0.5: "0.125rem",    // 2px
      1: "0.25rem",       // 4px
      1.5: "0.375rem",    // 6px
      2: "0.5rem",        // 8px
      2.5: "0.625rem",    // 10px
      3: "0.75rem",       // 12px
      3.5: "0.875rem",    // 14px
      4: "1rem",          // 16px
      4.5: "1.125rem",    // 18px
      5: "1.25rem",       // 20px
      5.5: "1.375rem",    // 22px
      6: "1.5rem",        // 24px
      6.5: "1.625rem",    // 26px
      7: "1.75rem",       // 28px
      7.5: "1.875rem",    // 30px
      8: "2rem",          // 32px
      8.5: "2.125rem",    // 34px
      9: "2.25rem",       // 36px
      9.5: "2.375rem",    // 38px
      10: "2.5rem",       // 40px
      10.5: "2.625rem",   // 42px
      11: "2.75rem",      // 44px
      11.5: "2.875rem",   // 46px
      12: "3rem",         // 48px
      12.5: "3.125rem",   // 50px
      13: "3.25rem",      // 52px
      14: "3.5rem",       // 56px
      15: "3.75rem",      // 60px
      16: "4rem",         // 64px
      17: "4.25rem",      // 68px
      17.5: "4.375rem",   // 70px
      18: "4.5rem",       // 72px
      19: "4.75rem",      // 76px
      20: "5rem",         // 80px
      22: "5.5rem",       // 88px
      22.5: "5.625rem",   // 90px
      24: "6rem",         // 96px
      25: "6.25rem",      // 100px
      26: "6.5rem",       // 104px
      28: "7rem",         // 112px
      30: "7.5rem",       // 120px
      32: "8rem",         // 128px
      34: "8.5rem",       // 136px
      36: "9rem",         // 144px
      40: "10rem",        // 160px
      42: "10.5rem",      // 168px
      44: "11rem",        // 176px
      48: "12rem",        // 192px
      50: "12.5rem",      // 200px
      52: "13rem",        // 208px
      56: "14rem",        // 224px
      60: "15rem",        // 240px
      64: "16rem",        // 256px
      72: "18rem",        // 288px
      80: "20rem",        // 320px
      96: "24rem",        // 384px
      full: "100%",
      screen: "100vw",
      "2full": "200%",
    },

    // ==========================================================
    // FONT FAMILY
    // ==========================================================
    fontFamily: {
      primary: ["SFU", "sans-serif"],
      awesome: ['"Font Awesome 6 Pro"'],
      awesomeSharp: ['"Font Awesome 6 Sharp"'],
      inter: ["Inter", "sans-serif"],
      sfu: ["SFU", "sans-serif"],
      Awesome6: ["'Font Awesome 6 Pro'"],
      Awesome6Brands: ["'Font Awesome 6 Brands'"],
    },

    // ==========================================================
    // FONT SIZE - rem chuẩn với line-height
    // ==========================================================
    fontSize: {
      xs: ["0.75rem", { lineHeight: "1rem" }],           // 12px
      sm: ["0.875rem", { lineHeight: "1.25rem" }],       // 14px
      base: ["1rem", { lineHeight: "1.5rem" }],          // 16px
      lg: ["1.125rem", { lineHeight: "1.75rem" }],       // 18px
      xl: ["1.25rem", { lineHeight: "1.75rem" }],        // 20px
      "2xl": ["1.5rem", { lineHeight: "2rem" }],         // 24px
      "3xl": ["1.875rem", { lineHeight: "2.25rem" }],    // 30px
      "4xl": ["2.25rem", { lineHeight: "2.5rem" }],      // 36px
      "5xl": ["2.5rem", { lineHeight: "1" }],            // 40px
      "6xl": ["3rem", { lineHeight: "1" }],              // 48px
      "7xl": ["4.5rem", { lineHeight: "1" }],            // 72px
      "8xl": ["5.25rem", { lineHeight: "1" }],           // 84px
      "9xl": ["6rem", { lineHeight: "1" }],              // 96px
      // Custom sizes for design matching
      "15px": ["0.9375rem", { lineHeight: "1.4" }],      // 15px
      28: ["1.75rem", { lineHeight: "1.3" }],            // 28px
      30: ["1.875rem", { lineHeight: "1.3" }],           // 30px
      32: ["2rem", { lineHeight: "1.2" }],               // 32px
      38: ["2.375rem", { lineHeight: "1.2" }],           // 38px
      40: ["2.5rem", { lineHeight: "1.2" }],             // 40px
      42: ["2.625rem", { lineHeight: "1.2" }],           // 42px
      48: ["3rem", { lineHeight: "1.1" }],               // 48px
      60: ["3.75rem", { lineHeight: "1.1" }],            // 60px
      64: ["4rem", { lineHeight: "1.1" }],               // 64px
      80: ["5rem", { lineHeight: "1.1" }],               // 80px
      96: ["6rem", { lineHeight: "1" }],                 // 96px
      120: ["7.5rem", { lineHeight: "1" }],              // 120px
      140: ["8.75rem", { lineHeight: "1" }],             // 140px
    },

    // ==========================================================
    // BORDER WIDTH - Fixed px values
    // ==========================================================
    borderWidth: {
      DEFAULT: "1px",
      0: "0",
      1: "1px",
      2: "2px",
      3: "3px",
      4: "4px",
      5: "5px",
      6: "6px",
      8: "8px",
    },

    // ==========================================================
    // ASPECT RATIO
    // ==========================================================
    aspectRatio: {
      auto: "auto",
      square: "1 / 1",
      video: "16 / 9",
      1: "1",
      2: "2",
      3: "3",
      4: "4",
      5: "5",
      6: "6",
      7: "7",
      8: "8",
      9: "9",
      10: "10",
      11: "11",
      12: "12",
      13: "13",
      14: "14",
      15: "15",
      16: "16",
    },

    // ==========================================================
    // SCALE
    // ==========================================================
    scale: {
      0: "0",
      50: ".5",
      70: ".70",
      75: ".75",
      80: ".8",
      85: ".85",
      90: ".9",
      95: ".95",
      100: "1",
      105: "1.05",
      110: "1.1",
      115: "1.15",
      120: "1.2",
      125: "1.25",
      150: "1.5",
      200: "2",
    },

    // ==========================================================
    // OPACITY
    // ==========================================================
    opacity: {
      0: "0",
      5: "0.05",
      10: "0.1",
      15: "0.15",
      20: "0.2",
      25: "0.25",
      30: "0.3",
      35: "0.35",
      40: "0.4",
      45: "0.45",
      50: "0.5",
      55: "0.55",
      60: "0.6",
      65: "0.65",
      70: "0.7",
      75: "0.75",
      80: "0.8",
      85: "0.85",
      90: "0.9",
      95: "0.95",
      100: "1",
    },

    // ==========================================================
    // OUTLINE OFFSET
    // ==========================================================
    outlineOffset: {
      0: "0",
      1: "1px",
      2: "2px",
      3: "3px",
      4: "4px",
      5: "5px",
      8: "8px",
    },

    // ==========================================================
    // EXTEND - Additional values
    // ==========================================================
    extend: {
      // Min/Max Width
      minWidth: {
        fit: "fit-content",
        40: "2.5rem",       // 40px
        124: "7.75rem",     // 124px
        130: "8.125rem",    // 130px
        160: "10rem",       // 160px
      },
      maxWidth: {
        fit: "fit-content",
        40: "2.5rem",
        124: "7.75rem",
        130: "8.125rem",
        160: "10rem",
        300: "18.75rem",    // 300px
        573: "35.8125rem",  // 573px
        815: "50.9375rem",  // 815px
        1512: "94.5rem",    // 1512px - Design reference
      },
      minHeight: {
        fit: "fit-content",
        40: "2.5rem",
        60: "3.75rem",      // 60px
        124: "7.75rem",
        130: "8.125rem",
        160: "10rem",
        680: "42.5rem",     // 680px
      },
      maxHeight: {
        fit: "fit-content",
        40: "2.5rem",
        60: "3.75rem",
        124: "7.75rem",
        130: "8.125rem",
        160: "10rem",
        680: "42.5rem",
      },

      // ==========================================================
      // COLORS - Unchanged from original
      // ==========================================================
      colors: {
        primary: {
          1: "#0E1010",
          2: "#ED1C24",
          4: "#fde4d3",
          5: "#f4914c",
          6: "#19517f",
          black05: "#0E1010",
          black04: "#504B4C",
          black03: "#7C7879",
          black02: "#A6A5A5",
          black01: "#D3D3D3",
          "3 BG": "#f7fced",
          "2 - 80%": "#000000",
          "background-2": "#f5f5f5",
        },
        secondary: {
          3: "#000000",
          4: "#000000",
          5: "#000000",
        },
        utility: {
          50: "#f6f6f6",
          100: "#efefef",
          200: "#dcdcdc",
          300: "#bdbdbd",
          400: "#989898",
          500: "#818181",
          600: "#656565",
          700: "#525252",
          800: "#464646",
          900: "#3d3d3d",
          950: "#292929",
          2929: "#292929",
          "white - 80%": "#ffffff",
          "white - 25": "#ffffff",
          "white - 15": "#ffffff",
          "1- 10%": "#4d9846",
          white: "#ffffff",
          black: "#000000",
          gray03: "#CECED3",
          error: "#e30000",
          "error-1": "#e30000",
          "error-2": "#e30000",
          "error-3": "#e30000",
          correct: "#0079d5",
          "correct-1": "#0079d5",
          "correct-2": "#0079d5",
          "correct-3": "#0079d5",
          f6f6: "#f6f6f6",
          efef: "#efefef",
          dcdc: "#dcdcdc",
          bdbd: "#bdbdbd",
          9898: "#989898",
          8181: "#818181",
          6565: "#656565",
          5252: "#525252",
        },
      },

      // Grid
      gridTemplateColumns: {
        "6-max": "repeat(6, max-content)",
      },

      // Background Image
      backgroundImage: {
        "workflow-gradient": "linear-gradient(142deg, #FFF 14.64%, #D7D6D6 88.5%)",
        "linear-1": "linear-gradient(90deg, #181830 -0.01%, #1D1D38 19.26%, #141228 40.12%, #2C223A 75.47%, #231B33 99.98%)",
        "linear-top-to-bottom": "linear-gradient(180deg, #C2CCE7 0%, #F5F8FF 47.6%)",
        "linear-1-top-to-bottom": "linear-gradient(180deg, #C2CCE7 0%, #DCE2F3 77.36%, #FFF 99.22%)",
        "linear-1-bottom-to-top": "linear-gradient(180deg, #FFF 0%, #E3EBFF 41%, #C2CCE7 100%)",
        "linear-2": "linear-gradient(180deg, #FFF 0%, #E3EBFF 41%, #C2CCE7 100%)",
      },

      // Background Position/Size
      backgroundPosition: {
        "pos-100-0": "100% 0%",
      },
      backgroundSize: {
        "0-100": "0 100%",
        "100-100": "100% 100%",
        "200-100": "200% 100%",
      },

      // Blur
      blur: {
        DEFAULT: "10px",
      },

      // ==========================================================
      // BORDER RADIUS - Fixed rem values
      // ==========================================================
      borderRadius: {
        1: "0.25rem",       // 4px
        2: "0.5rem",        // 8px
        3: "0.75rem",       // 12px
        4: "1rem",          // 16px
        5: "1.25rem",       // 20px
        6: "1.5rem",        // 24px
        7: "1.75rem",       // 28px
        8: "2rem",          // 32px
        9: "2.25rem",       // 36px
        10: "2.5rem",       // 40px
        11: "2.75rem",      // 44px
        12: "3rem",         // 48px
        13: "3.25rem",      // 52px
        14: "3.5rem",       // 56px
        15: "3.75rem",      // 60px
        16: "4rem",         // 64px
        17: "4.25rem",      // 68px
        18: "4.5rem",       // 72px
        19: "4.75rem",      // 76px
        20: "5rem",         // 80px
      },

      // ==========================================================
      // TYPOGRAPHY PLUGIN CONFIG
      // ==========================================================
      typography: {
        DEFAULT: {
          css: {
            "--tw-prose-body": "inherit",
            "h1,h2,h3,h4,h5,h6": {
              fontSize: "1.25rem", // 20px
              fontWeight: "700",
              lineHeight: 1.3,
              color: "theme('colors.primary.1')",
            },
            strong: {
              color: "inherit",
              fontWeight: "700",
            },
            blockquote: {
              color: "#white",
              borderInlineStartColor: "theme('colors.primary.1')",
              backgroundColor: "theme('colors.secondary.1')",
              paddingTop: "1rem",
              paddingBottom: "1rem",
              fontStyle: "normal",
            },
            figcaption: {
              fontSize: "0.9375rem", // 15px
            },
            fontSize: "inherit",
            lineHeight: "inherit",
            "*": { margin: "1.25rem 0" },
            "> *:first-child": { marginTop: 0 },
            "> *:last-child": { marginBottom: 0 },
            div: { margin: "1.25rem 0" },
            margin: 0,
            maxWidth: "unset",
            a: {
              color: "theme('colors.primary.2')",
              textDecoration: "underline",
              "&:hover": {
                color: "#EE0000",
              },
              "&:visited": {
                color: "#551A8B",
              },
            },
            ul: {
              "padding-left": "1.5rem",
              li: {
                paddingLeft: 0,
                margin: "0 0",
                "&::marker": {
                  color: "theme('colors.neutral.950')",
                },
              },
            },
            table: {
              td: {
                border: "thin solid #e8e8e8",
                padding: "0.5rem",
              },
            },
          },
        },
        "white-marker": {
          css: {
            ul: {
              li: {
                "&::marker": {
                  color: "#fff",
                },
              },
            },
          },
        },
        "no-space": {
          css: {
            "*": { margin: "0 0" },
            div: { margin: "0 0" },
          },
        },
        "space-y-3": {
          css: {
            "*": { margin: "0.75rem 0" },
            div: { margin: "0.75rem 0" },
            "> *:first-child": { marginTop: 0 },
            "> *:last-child": { marginBottom: 0 },
          },
        },
        "space-y-6": {
          css: {
            "*": { margin: "1.5rem 0" },
            div: { margin: "1.5rem 0" },
            "> *:first-child": { marginTop: 0 },
            "> *:last-child": { marginBottom: 0 },
          },
        },
      },

      // ==========================================================
      // BOX SHADOW
      // ==========================================================
      boxShadow: {
        "Shadow 1": "0px 4px 4px 0px rgba(31,34,39,0.08)",
        "Shadow 2": "0px 4px 8px 0px rgba(31,34,39,0.08)",
        "Shadow 3": "0px 8px 16px 0px rgba(31,34,39,0.08)",
        "Shadow 4": "0px 8px 24px 0px rgba(31,34,39,0.06)",
        "Shadow card": "4px 4px 32px 16px rgba(0, 0, 0, 0.08)",
        number: "4px 4px 5px rgba(0, 0, 0, 0.15)",
        "shadow-light": "0 0 12px 0 rgba(0, 0, 0, 0.06)",
        "shadow-medium": "0px 8px 24px rgba(0, 0, 0, 0.16)",
        "shadow-hard": "0px 12px 48px rgba(0, 0, 0, 0.24)",
        "dropshadow-light": "4px 4px 32px 16px rgba(0,0,0,0.08)",
        "dropshadow-medium": "4px 4px 8px 4px rgba(0,0,0,0.24)",
        "dropshadow-hard": "8px 8px 16px 8px rgba(0,0,0,0.4)",
        "shadow-card": "4px 4px 32px 16px rgba(0, 0, 0, 0.08)",
      },

      // Line Clamp
      lineClamp: {
        6: "6",
        7: "7",
        8: "8",
        9: "9",
        10: "10",
      },

      // Line Height
      lineHeight: {
        1.125: "1.125",
        1.3: "1.3",
        1.33: "1.33",
        1.4: "1.4",
        1.44: "1.44",
        1.5: "1.5",
      },

      // Keyframes
      keyframes: {
        bgGradient: {
          "0%": { backgroundPosition: "0% 50%" },
          "50%": { backgroundPosition: "100% 50%" },
          "100%": { backgroundPosition: "0% 50%" },
        },
        fadeIn: {
          "0%": { opacity: "0" },
          "50%": { opacity: "1" },
          "100%": { opacity: "0" },
        },
        rotateCircle: {
          "0%": { transform: "translate(-50%, -50%) rotate(0)" },
          "100%": { transform: "translate(-50%, -50%) rotate(360deg)" },
        },
        spin: {
          "0%": { transform: "rotate(0deg)" },
          "100%": { transform: "rotate(360deg)" },
        },
      },

      // Animation
      animation: {
        "spin-circle": "rotateCircle 20s linear infinite",
        "fade-in": "fadeIn 2s linear infinite",
        spin: "spin 2s linear infinite",
      },

      // Z-Index
      zIndex: {
        1: "1",
        2: "2",
        3: "3",
        4: "4",
        5: "5",
        6: "6",
        7: "7",
        8: "8",
        9: "9",
        11: "11",
        12: "12",
        100: "100",
        999: "999",
        1000: "1000",
      },
    },
  },

  // ==========================================================
  // CORE PLUGINS
  // ==========================================================
  corePlugins: {
    aspectRatio: true,
  },

  variants: {
    aspectRatio: ["responsive", "hover"],
    lineClamp: ["responsive", "hover"],
  },

  // ==========================================================
  // PLUGINS
  // ==========================================================
  plugins: [
    plugin(function ({ addBase, addComponents, addVariant, matchUtilities, addUtilities, theme }) {
      addBase({});

      // ==========================================================
      // TYPOGRAPHY COMPONENTS - Responsive với rem chuẩn
      // Dùng clamp CHỈ cho heading lớn để có fluid scaling hợp lý
      // ==========================================================
      addComponents({
        // Title 60px - Largest heading, uses clamp for fluid scaling
        ".title-60": {
          fontWeight: "700",
          fontSize: "2.25rem", // 36px mobile
          [`@media (min-width: ${theme("screens.md")})`]: {
            fontSize: "2.5rem", // 40px
          },
          [`@media (min-width: ${theme("screens.lg")})`]: {
            fontSize: "3rem", // 48px
          },
          [`@media (min-width: ${theme("screens.xl")})`]: {
            fontSize: "3.75rem", // 60px
          },
        },

        // Title 140px - Hero heading
        ".title-140": {
          fontWeight: "600",
          lineHeight: "1.18",
          fontSize: "4rem", // 64px mobile
          [`@media (min-width: ${theme("screens.md")})`]: {
            fontSize: "6rem", // 96px
          },
          [`@media (min-width: ${theme("screens.lg")})`]: {
            fontSize: "8.125rem", // 130px
          },
          [`@media (min-width: ${theme("screens.xl")})`]: {
            fontSize: "8.75rem", // 140px
          },
        },

        ".title-138": {
          fontWeight: "600",
          lineHeight: "1.18",
          fontSize: "4rem",
          [`@media (min-width: ${theme("screens.md")})`]: {
            fontSize: "5.5rem", // 88px
          },
          [`@media (min-width: ${theme("screens.lg")})`]: {
            fontSize: "7rem", // 112px
          },
          [`@media (min-width: ${theme("screens.xl")})`]: {
            fontSize: "8.625rem", // 138px
          },
        },

        ".title-80": {
          fontWeight: "600",
          lineHeight: "1.18",
          fontSize: "2.5rem", // 40px mobile
          [`@media (min-width: ${theme("screens.md")})`]: {
            fontSize: "3.25rem", // 52px
          },
          [`@media (min-width: ${theme("screens.lg")})`]: {
            fontSize: "3.75rem", // 60px
          },
          [`@media (min-width: ${theme("screens.xl")})`]: {
            fontSize: "5rem", // 80px
          },
        },

        ".title-48": {
          fontWeight: "600",
          lineHeight: "1.18",
          fontSize: "1.75rem", // 28px mobile
          [`@media (min-width: ${theme("screens.md")})`]: {
            fontSize: "2.25rem", // 36px
          },
          [`@media (min-width: ${theme("screens.xl")})`]: {
            fontSize: "3rem", // 48px
          },
        },

        ".title-42": {
          fontWeight: "700",
          fontSize: "1.75rem", // 28px mobile
          [`@media (min-width: ${theme("screens.md")})`]: {
            fontSize: "2.25rem", // 36px
          },
          [`@media (min-width: ${theme("screens.lg")})`]: {
            fontSize: "2.625rem", // 42px
          },
        },

        ".title-40": {
          fontWeight: "700",
          fontSize: "1.75rem", // 28px
          [`@media (min-width: ${theme("screens.md")})`]: {
            fontSize: "2.25rem", // 36px
          },
          [`@media (min-width: ${theme("screens.lg")})`]: {
            fontSize: "2.5rem", // 40px
          },
        },

        ".title-36": {
          fontWeight: "700",
          fontSize: "1.5rem", // 24px mobile
          [`@media (min-width: ${theme("screens.md")})`]: {
            fontSize: "2rem", // 32px
          },
          [`@media (min-width: ${theme("screens.lg")})`]: {
            fontSize: "2.25rem", // 36px
          },
        },

        ".title-32": {
          fontWeight: "700",
          fontSize: "1.25rem", // 20px mobile
          [`@media (min-width: ${theme("screens.md")})`]: {
            fontSize: "1.75rem", // 28px
          },
          [`@media (min-width: ${theme("screens.lg")})`]: {
            fontSize: "2rem", // 32px
          },
        },

        ".title-30": {
          fontWeight: "700",
          fontSize: "1.25rem", // 20px mobile
          [`@media (min-width: ${theme("screens.md")})`]: {
            fontSize: "1.625rem", // 26px
          },
          [`@media (min-width: ${theme("screens.lg")})`]: {
            fontSize: "1.875rem", // 30px
          },
        },

        ".title-28": {
          fontSize: "1.125rem", // 18px mobile
          [`@media (min-width: ${theme("screens.md")})`]: {
            fontSize: "1.25rem", // 20px
          },
          [`@media (min-width: ${theme("screens.lg")})`]: {
            fontSize: "1.75rem", // 28px
          },
        },

        ".title-24": {
          fontSize: "1rem", // 16px mobile
          [`@media (min-width: ${theme("screens.md")})`]: {
            fontSize: "1.125rem", // 18px
          },
          [`@media (min-width: ${theme("screens.lg")})`]: {
            fontSize: "1.5rem", // 24px
          },
        },

        ".title-20": {
          fontSize: "0.875rem", // 14px mobile
          [`@media (min-width: ${theme("screens.md")})`]: {
            fontSize: "1rem", // 16px
          },
          [`@media (min-width: ${theme("screens.lg")})`]: {
            fontSize: "1.25rem", // 20px
          },
        },

        // Body sizes - Fixed rem
        ".body-14": {
          fontSize: "0.875rem", // 14px
        },
        ".body-16": {
          fontSize: "1rem", // 16px
        },
        ".body-18": {
          fontSize: "1.125rem", // 18px
        },

        // ==========================================================
        // LAYOUT UTILITIES
        // ==========================================================
        ".absolute-center-y": {
          position: "absolute",
          top: "50%",
          transform: "translateY(-50%)",
        },
        ".absolute-center-x": {
          position: "absolute",
          left: "50%",
          transform: "translateX(-50%)",
        },
        ".absolute-center": {
          position: "absolute",
          left: "50%",
          top: "50%",
          transform: "translate(-50%, -50%)",
        },

        // Gap utilities - Responsive với breakpoints
        ".gap-base": {
          gap: "0.9375rem", // 15px mobile
          [`@media (min-width: ${theme("screens.lg")})`]: {
            gap: "2.5rem", // 40px
          },
        },

        ".mb-base": {
          marginBottom: "1.875rem", // 30px mobile
          [`@media (min-width: ${theme("screens.lg")})`]: {
            marginBottom: "2.5rem", // 40px
          },
        },

        // Section padding - Responsive với breakpoints
        ".section-py": {
          paddingTop: "2.5rem", // 40px mobile
          paddingBottom: "2.5rem",
          [`@media (min-width: ${theme("screens.lg")})`]: {
            paddingTop: "3.75rem", // 60px
            paddingBottom: "3.75rem",
          },
          [`@media (min-width: ${theme("screens.xl")})`]: {
            paddingTop: "5rem", // 80px
            paddingBottom: "5rem",
          },
        },

        // Transitions
        ".transition-all": {
          transition: "all 200ms ease",
        },
        ".transition-300": {
          transition: "all .3s ease",
        },
        ".transition-400": {
          transition: "all .4s ease",
        },
        ".transition-500": {
          transition: "all .5s ease",
        },
        ".transition-ease-in-quad": {
          transition: "all 200ms cubic-bezier(.55, .085, .68, .53)",
        },
        ".transition-ease-in-cubic": {
          transition: "all 200ms cubic-bezier(.550, .055, .675, .19)",
        },
        ".transition-ease-in-quart": {
          transition: "all 200ms cubic-bezier(.895, .03, .685, .22)",
        },
        ".transition-ease-in-quint": {
          transition: "all 200ms cubic-bezier(.755, .05, .855, .06)",
        },
        ".transition-ease-in-expo": {
          transition: "all 200ms cubic-bezier(.95, .05, .795, .035)",
        },
        ".transition-ease-in-circ": {
          transition: "all 200ms cubic-bezier(.6, .04, .98, .335)",
        },
        ".transition-ease-out-quad": {
          transition: "all 200ms cubic-bezier(.25, .46, .45, .94)",
        },
        ".transition-ease-out-cubic": {
          transition: "all 200ms cubic-bezier(.215, .61, .355, 1)",
        },
        ".transition-ease-out-quart": {
          transition: "all 200ms cubic-bezier(.165, .84, .44, 1)",
        },
        ".transition-ease-out-quint": {
          transition: "all 200ms cubic-bezier(.23, 1, .32, 1)",
        },
        ".transition-ease-out-expo": {
          transition: "all 200ms cubic-bezier(.19, 1, .22, 1)",
        },
        ".transition-ease-out-circ": {
          transition: "all 200ms cubic-bezier(.075, .82, .165, 1)",
        },
        ".transition-ease-in-out-quad": {
          transition: "all 200ms cubic-bezier(.455, .03, .515, .955)",
        },
        ".transition-ease-in-out-cubic": {
          transition: "all 200ms cubic-bezier(.645, .045, .355, 1)",
        },
        ".transition-ease-in-out-quart": {
          transition: "all 200ms cubic-bezier(.77, 0, .175, 1)",
        },
        ".transition-ease-in-out-quint": {
          transition: "all 200ms cubic-bezier(.86, 0, .07, 1)",
        },
        ".transition-ease-in-out-expo": {
          transition: "all 200ms cubic-bezier(1, 0, 0, 1)",
        },
        ".transition-ease-in-out-circ": {
          transition: "all 200ms cubic-bezier(.785, .135, .15, .86)",
        },

        // Flex utilities
        ".flex-center": {
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
        },
        ".flex-between": {
          display: "flex",
          alignItems: "center",
          justifyContent: "space-between",
        },
        ".overflow-overlay": {
          overflowY: "overlay",
        },
        ".absolute-full": {
          position: "absolute",
          top: "0",
          left: "0",
          width: "100%",
          height: "100%",
        },
        ".filter-white": {
          filter: "brightness(0) invert(1)",
          transition: "all .3s ease",
        },
      });

      // Square utility - uses spacing values
      matchUtilities(
        {
          sq: (value) => ({
            height: value,
            width: value,
          }),
        },
        { values: theme("spacing") }
      );

      // Additional utilities
      const newUtilities = {
        ".linear-border-box": {
          "background-origin": "border-box",
          "background-clip": "padding-box, border-box",
        },
        ".horizontal-tb": {
          writingMode: "horizontal-tb",
        },
        ".vertical-rl": {
          writingMode: "vertical-rl",
        },
        ".vertical-lr": {
          writingMode: "vertical-lr",
        },
        ".text-last-center": {
          "text-align-last": "center",
        },
      };
      addUtilities(newUtilities);

      // Custom variants
      addVariant("optional", "&:optional");
      addVariant("hocus", ["&:hover", "&:focus"]);
      addVariant("supports-grid", "@supports (display: grid)");
    }),

    // ==========================================================
    // RATIO PLUGIN - GIỮ NGUYÊN vì đây là aspect ratio, không phải viewport scaling
    // Dùng cho img-ratio, padding-top trick
    // ==========================================================
    plugin(({ addVariant, e }) => {
      addVariant("ratio", ({ container, separator }) => {
        container.walkRules((rule) => {
          rule.selector = `.${e(`ratio${separator}`)}${rule.selector.slice(1)}`;
          rule.walkDecls((decl) => {
            const ratioValues = decl.value.split(" ");
            if (ratioValues.length === 2) {
              const num1 = parseInt(ratioValues[0]);
              const num2 = parseInt(ratioValues[1]);
              if (!isNaN(num1) && !isNaN(num2) && num2 !== 0) {
                const percentage = `${(num1 / num2) * 100}%`;
                decl.value = `${percentage}`;
              }
            }
          });
        });
      });
    }),

    // ==========================================================
    // COLUMN UTILITIES
    // ==========================================================
    plugin(function ({ addUtilities, theme }) {
      const breakpoints = ["sm", "md", "lg", "xl"];
      const columns = 12;
      const columnUtilities = {};

      for (let i = 1; i <= columns; i++) {
        columnUtilities[`.col-${i}`] = {};
      }

      breakpoints.forEach((bp) => {
        for (let i = 1; i <= columns; i++) {
          columnUtilities[`.col-${bp}-${i}`] = {};
        }
        columnUtilities[`.col-${bp}-auto`] = {};
      });

      columnUtilities[".row"] = {};
      columnUtilities[".col-auto"] = {};

      addUtilities(columnUtilities);
    }),
  ],
};
