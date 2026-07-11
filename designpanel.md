---
version: alpha
name: TailAdmin
description: A bright, airy admin-template system with rounded cards, bold blue accents, and a modern dashboard-first feel.
colors:
  primary: "#465FFF"
  secondary: "#344054"
  tertiary: "#64748B"
  neutral: "#FFFFFF"
  surface: "#F8FAFC"
  on-surface: "#1E293B"
  border: "#D0D5DD"
  muted-border: "#F2F4F7"
  error: "#EF4444"
  success: "#22C55E"
typography:
  headline-display:
    fontFamily: Outfit
    fontSize: 40px
    fontWeight: 700
    lineHeight: 1.1
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Outfit
    fontSize: 36px
    fontWeight: 700
    lineHeight: 40px
    letterSpacing: 0px
  headline-md:
    fontFamily: Outfit
    fontSize: 29px
    fontWeight: 500
    lineHeight: 35px
    letterSpacing: 0px
  headline-sm:
    fontFamily: Outfit
    fontSize: 24px
    fontWeight: 500
    lineHeight: 24px
    letterSpacing: 0px
  headline-xs:
    fontFamily: Outfit
    fontSize: 20px
    fontWeight: 500
    lineHeight: 24px
    letterSpacing: 0px
  body-lg:
    fontFamily: Outfit
    fontSize: 18px
    fontWeight: 400
    lineHeight: 28px
    letterSpacing: 0px
  body-md:
    fontFamily: Outfit
    fontSize: 16px
    fontWeight: 400
    lineHeight: 24px
    letterSpacing: 0px
  body-sm:
    fontFamily: Outfit
    fontSize: 14px
    fontWeight: 400
    lineHeight: 20px
    letterSpacing: 0px
  label-lg:
    fontFamily: Outfit
    fontSize: 16px
    fontWeight: 500
    lineHeight: 24px
    letterSpacing: 0px
  label-md:
    fontFamily: Outfit
    fontSize: 14px
    fontWeight: 500
    lineHeight: 20px
    letterSpacing: 0px
  label-sm:
    fontFamily: Outfit
    fontSize: 12px
    fontWeight: 500
    lineHeight: 16px
    letterSpacing: 0px
  caption:
    fontFamily: Outfit
    fontSize: 12px
    fontWeight: 400
    lineHeight: 16px
    letterSpacing: 0px
rounded:
  none: 0px
  sm: 4px
  md: 8px
  lg: 12px
  xl: 24px
  full: 9999px
spacing:
  xs: 8px
  sm: 16px
  md: 24px
  lg: 36px
  xl: 80px
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.neutral}"
    typography: "{typography.label-lg}"
    rounded: "{rounded.md}"
    padding: "12px 24px"
    minWidth: "156px"
    height: "50px"
  button-primary-hover:
    backgroundColor: "{colors.secondary}"
    textColor: "{colors.neutral}"
    typography: "{typography.label-lg}"
    rounded: "{rounded.md}"
  button-secondary:
    backgroundColor: "{colors.neutral}"
    textColor: "{colors.secondary}"
    typography: "{typography.label-lg}"
    rounded: "{rounded.md}"
    padding: "12px 24px"
    minWidth: "156px"
    height: "50px"
  button-link:
    backgroundColor: "transparent"
    textColor: "{colors.tertiary}"
    typography: "{typography.body-md}"
    rounded: "{rounded.none}"
    padding: "0px"
  card:
    backgroundColor: "{colors.neutral}"
    textColor: "{colors.tertiary}"
    rounded: "{rounded.xl}"
    padding: "10px"
  card-shell:
    backgroundColor: "{colors.neutral}"
    textColor: "{colors.tertiary}"
    rounded: "{rounded.xl}"
    padding: "24px"
  input:
    backgroundColor: "{colors.neutral}"
    textColor: "{colors.on-surface}"
    typography: "{typography.body-md}"
    rounded: "{rounded.md}"
    padding: "12px 16px"
  chip:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.secondary}"
    typography: "{typography.label-sm}"
    rounded: "{rounded.full}"
    padding: "8px 12px"
---

# TailAdmin

## Overview
TailAdmin feels like a polished, developer-friendly SaaS brand: clean, confident, and intentionally light. The page uses a spacious hero layout, strong centered hierarchy, and bright blue accenting to communicate clarity and productivity rather than luxury or playfulness. Overall it targets builders and teams evaluating an admin dashboard kit, so the tone is professional, modern, and slightly energetic.

## Colors
- **Primary (#465FFF):** A vivid electric blue used for the main CTA, brand marks, interactive emphasis, and the strongest highlights in the UI.
- **Secondary (#344054):** A deep slate used for prominent text and darker button treatments, giving the interface structure without feeling harsh black.
- **Tertiary (#64748B):** A muted blue-gray used for body copy, nav links, and supporting text; it keeps the layout calm and readable.
- **Neutral (#FFFFFF):** The dominant base surface for the page, cards, buttons, and most content containers.
- **Surface (#F8FAFC):** A very light cool background tone that can be used for subtle section differentiation or soft elevated panels.
- **On-surface (#1E293B):** A near-navy text color suited for headlines and high-contrast content in dense dashboard states.
- **Border (#D0D5DD):** A soft cool gray used for outlines on secondary buttons, inputs, and light separators.
- **Muted-border (#F2F4F7):** An even lighter border tone used around cards and shell frames to keep the design airy.
- **Error (#EF4444):** A clear red for negative states, validation, and downward changes.
- **Success (#22C55E):** A bright green for positive metrics and success indicators.

## Typography
Outfit is the defining typeface across the system, giving the interface rounded geometry and a contemporary product feel. Headlines are bold and compact, with `headline-lg` and `headline-display` carrying the hero message and `headline-md` through `headline-xs` supporting section titles and cards. Body copy stays regular weight and comfortable at `16px/24px`, while labels and CTAs use medium weight to feel crisp and actionable. Letter spacing is neutral and uppercase styling is not a primary visual convention here; the system leans on weight, size, and spacing instead.

## Layout
The layout is centered and container-based, with a very wide hero area and generous side gutters. Large vertical spacing separates navigation, hero copy, icon rows, CTA buttons, and the product preview, creating a calm one-screen landing flow. The spacing rhythm follows an 8px system from `xs` through `xl`, with `md` and `lg` used most often for breathing room between groups. Cards and shells feel padded rather than dense, with interior padding around `10px` on cards and broader section spacing for page-level composition.

## Elevation & Depth
Depth is restrained and mostly achieved through soft shadows, bright contrast, and layered white surfaces rather than heavy elevation. Primary buttons use a subtle shadow to lift them from the page, while cards rely more on pale borders and large rounded corners than on dramatic drop shadows. The overall effect is clean and lightweight, suitable for dashboard content where hierarchy should come from structure and color, not visual noise.

## Shapes
The shape language is rounded and friendly, with `8px` radii for controls and `24px` radii for larger cards and containers. Buttons and inputs feel accessible and modern, while outer shells and featured panels have a softer architectural curve. Full-pill chips reinforce the polished SaaS aesthetic without becoming decorative.

## Components
Buttons are the most important interactive element and should feel clear, decisive, and consistent. `button-primary` uses the blue fill, white text, `8px` radius, `12px 24px` padding, and a `50px` height for strong CTA presence. `button-secondary` inverts to a white background with a light border and dark text for lower-emphasis actions. `button-link` is minimal and text-only, suitable for navigation or utility actions where chrome would feel excessive. Hover states should deepen or darken the background slightly rather than introducing new colors.

Cards should stay white, softly bordered, and generously rounded, using `card` or `card-shell` as the base. Keep card interiors spacious and prefer subtle separation lines over heavy shadows. Inputs should match the same rounded `8px` language, use white surfaces, and rely on light borders with dark text for clarity. Chips and badges should be compact pill shapes with muted backgrounds so they support metadata without competing with primary actions.

Navigation items are understated and text-led, with the active state suggested by contrast or a soft background rather than bold underlines. Metric tiles, preview panes, and dashboard modules should continue the same pattern: white surfaces, cool-gray borders, compact iconography, and restrained shadows. Status indicators should use success green and error red sparingly, only where data meaning requires them.

## Do's and Don'ts
- Do keep layouts spacious and centered, especially in landing-page hero sections.
- Do use Outfit consistently for both display and UI text to preserve the brand voice.
- Do reserve the primary blue for the most important actions and highlighted states.
- Do favor light borders and white surfaces over heavy shadows and dense frames.
- Don't introduce sharp corners on main controls or featured cards.
- Don't use saturated accent colors beyond the blue, green, and red status roles.
- Don't make body copy darker than necessary; the system prefers a soft, readable slate.
- Don't overcrowd dashboard modules; the design depends on whitespace and clear grouping.