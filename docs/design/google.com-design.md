---
version: alpha
name: Google Search Minimal
description: A clean, high-trust search interface with generous whitespace, restrained chrome, and one vivid action color.
colors:
  primary: "#0b57d0"
  secondary: "#1f1f1f"
  tertiary: "#f1f3f4"
  neutral: "#ffffff"
  surface: "#ffffff"
  on-surface: "#1f1f1f"
  border: "#e5e7eb"
  subtle: "#f8f9fa"
  link: "#1a0dab"
  error: "#d93025"
typography:
  headline-display:
    fontFamily: Roboto
    fontSize: 32px
    fontWeight: 700
    lineHeight: 38px
    letterSpacing: 0px
  headline-lg:
    fontFamily: Roboto
    fontSize: 24px
    fontWeight: 700
    lineHeight: 29px
    letterSpacing: 0px
  headline-md:
    fontFamily: Roboto
    fontSize: 20px
    fontWeight: 600
    lineHeight: 24px
    letterSpacing: 0px
  headline-sm:
    fontFamily: Roboto
    fontSize: 18px
    fontWeight: 600
    lineHeight: 22px
    letterSpacing: 0px
  body-lg:
    fontFamily: Roboto
    fontSize: 16px
    fontWeight: 400
    lineHeight: 24px
    letterSpacing: 0px
  body-md:
    fontFamily: Roboto
    fontSize: 14px
    fontWeight: 400
    lineHeight: 20px
    letterSpacing: 0px
  body-sm:
    fontFamily: Roboto
    fontSize: 12px
    fontWeight: 400
    lineHeight: 16px
    letterSpacing: 0px
  label-lg:
    fontFamily: Roboto
    fontSize: 16px
    fontWeight: 500
    lineHeight: 24px
    letterSpacing: 0px
  label-md:
    fontFamily: Roboto
    fontSize: 14px
    fontWeight: 500
    lineHeight: 20px
    letterSpacing: 0px
  label-sm:
    fontFamily: Roboto
    fontSize: 12px
    fontWeight: 500
    lineHeight: 16px
    letterSpacing: 0px
  utility-sm:
    fontFamily: Roboto
    fontSize: 14px
    fontWeight: 400
    lineHeight: 20px
    letterSpacing: 0px
  utility-xs:
    fontFamily: Roboto
    fontSize: 12px
    fontWeight: 400
    lineHeight: 16px
    letterSpacing: 0px
rounded:
  none: 0px
  sm: 4px
  md: 8px
  lg: 16px
  xl: 24px
  full: 9999px
spacing:
  xs: 4px
  sm: 12px
  md: 20px
  lg: 48px
  xl: 160px
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.neutral}"
    typography: "{typography.label-md}"
    rounded: "{rounded.full}"
    padding: "10px 12px"
    height: "48px"
  button-secondary:
    backgroundColor: "{colors.tertiary}"
    textColor: "{colors.on-surface}"
    typography: "{typography.label-md}"
    rounded: "{rounded.full}"
    padding: "10px 12px"
    height: "48px"
  button-link:
    backgroundColor: "transparent"
    textColor: "{colors.on-surface}"
    typography: "{typography.body-md}"
    rounded: "{rounded.none}"
    padding: "0px"
  card:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.on-surface}"
    rounded: "{rounded.md}"
    padding: "16px"
  input:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.on-surface}"
    rounded: "{rounded.full}"
    padding: "0px 16px"
    height: "48px"
  chip:
    backgroundColor: "{colors.subtle}"
    textColor: "{colors.on-surface}"
    typography: "{typography.label-sm}"
    rounded: "{rounded.full}"
    padding: "6px 12px"
  nav-link:
    backgroundColor: "transparent"
    textColor: "{colors.on-surface}"
    typography: "{typography.body-md}"
    rounded: "{rounded.none}"
    padding: "0px"
---

# Google Search Minimal

## Overview
This interface feels calm, efficient, and universally approachable. It is intentionally sparse, with a near-total emphasis on search, minimal chrome, and one strong blue action that signals the primary path forward. The tone is professional but friendly, optimized for speed, clarity, and trust rather than visual flourish.

## Colors
- **Primary (#0b57d0):** A vivid Google blue used for the main action button, key emphasis, and high-priority interactive elements.
- **Secondary (#1f1f1f):** The core text color, a soft near-black that reads clearly without feeling harsh.
- **Tertiary (#f1f3f4):** A light neutral gray used for secondary buttons and subtle control backgrounds.
- **Neutral (#ffffff):** The dominant page and component background, supporting the airy, open composition.
- **Surface (#ffffff):** Card and control surfaces stay white to blend into the page and preserve a clean, low-noise hierarchy.
- **On-surface (#1f1f1f):** Default foreground color for labels, navigation, and utility text on light backgrounds.
- **Border (#e5e7eb):** A faint divider tone for soft outlines and delicate structural separation.
- **Subtle (#f8f9fa):** An even lighter neutral for gentle UI affordances, chip-like treatments, and background variation.
- **Link (#1a0dab):** The familiar link blue reserved for textual navigation and informational hyperlinks.
- **Error (#d93025):** A clear alert red for validation and destructive states, kept out of the main visual field unless needed.

## Typography
Roboto is the system voice throughout, giving the page a familiar, highly legible, product-grade feel. Headlines use strong weights and compact leading to create hierarchy without adding decorative styling; `headline-display` and `headline-lg` are suitable for large brand moments, while `headline-md` and `headline-sm` support smaller section titles. Body text stays neutral and readable with `body-md` and `body-lg`, and labels lean medium weight for controls so buttons, nav items, and utility actions remain crisp. Letter spacing is effectively neutral across the system, with no uppercase emphasis or tracking-heavy styling visible in the source.

## Layout
The layout is centered and expansive, with large amounts of white space framing the logo, search field, and primary actions. Content is arranged on a simple vertical stack rather than a dense grid, with the main interaction anchored in the middle of the viewport and secondary links pushed to the edges. The spacing rhythm follows a modest scale: 4px for fine adjustments, 12px for tight groupings, 20px for comfortable separation, and larger 48px and 160px values for dramatic breathing room between the page regions. Section padding is minimal because the interface depends on openness rather than boxed containers.

## Elevation & Depth
Depth is restrained and mostly functional. The search field and a few controls use a soft shadow and faint border to lift them from the white page without creating heavy layering. There are no dramatic overlays or stacked surfaces; hierarchy comes from contrast, outline weight, and small tonal shifts rather than strong elevation. This keeps the experience lightweight and focused on search rather than interface chrome.

## Shapes
The shape language is rounded and friendly, with pill-like controls defining the primary interaction style. Buttons and the main search field use `rounded.full`, while cards and supporting surfaces can use the subtler `rounded.md`. The overall feel is smooth and approachable, avoiding sharp corners except where text links intentionally remain plain and unframed.

## Components
Buttons are the clearest expression of hierarchy. `button-primary` should be used for the main call to action: blue background, white text, `label-md` typography, full pill radius, and a 48px height. `button-secondary` uses the light gray `tertiary` background with dark text for supportive actions like “I’m Feeling Lucky.” `button-link` is intentionally plain, with no background, no radius, and no padding, for quiet utility links such as footer and header navigation.

Cards should feel light and unobtrusive. Use the `card` token for white panels with subtle borders and an 8px radius when content needs separation from the page, but keep shadows minimal or absent unless a small lift is necessary. Inputs should be wide, centered, and pill-shaped, using `input` with a 48px height and generous horizontal padding; they should read as the primary workspace rather than a decorated form field. Chips may be used sparingly for compact feature tags or inline controls, using the light `subtle` background and `label-sm` typography. Navigation links should stay visually quiet with `nav-link` styling, relying on placement and spacing rather than decoration. Tooltips, checkboxes, and radio buttons are not prominent in the observed surface and should remain minimal if introduced, following the same quiet, high-clarity language.

## Do's and Don'ts
- Do keep the canvas spacious and centered, with the main action isolated and easy to find.
- Do use Roboto consistently for both navigation and controls to preserve the familiar Google feel.
- Do favor subtle borders and soft shadows over heavy elevation or dramatic layering.
- Do make primary actions blue and secondary actions light gray to preserve hierarchy.
- Don't introduce dense panels, busy gradients, or decorative backgrounds.
- Don't overuse rounded corners beyond the pill treatment already established by buttons and the search field.
- Don't make body text heavier than `body-md` unless it is truly a control label or action.
- Don't crowd the top bar, footer, or search area with extra UI that competes with search itself.