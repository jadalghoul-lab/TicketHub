---
name: Professional Ticketing Interface
colors:
  surface: '#f7f9fb'
  surface-dim: '#d8dadc'
  surface-bright: '#f7f9fb'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f4f6'
  surface-container: '#eceef0'
  surface-container-high: '#e6e8ea'
  surface-container-highest: '#e0e3e5'
  on-surface: '#191c1e'
  on-surface-variant: '#464555'
  inverse-surface: '#2d3133'
  inverse-on-surface: '#eff1f3'
  outline: '#777587'
  outline-variant: '#c7c4d8'
  surface-tint: '#4d44e3'
  primary: '#3525cd'
  on-primary: '#ffffff'
  primary-container: '#4f46e5'
  on-primary-container: '#dad7ff'
  inverse-primary: '#c3c0ff'
  secondary: '#515f74'
  on-secondary: '#ffffff'
  secondary-container: '#d5e3fd'
  on-secondary-container: '#57657b'
  tertiary: '#41485e'
  on-tertiary: '#ffffff'
  tertiary-container: '#586076'
  on-tertiary-container: '#d4dbf5'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#e2dfff'
  primary-fixed-dim: '#c3c0ff'
  on-primary-fixed: '#0f0069'
  on-primary-fixed-variant: '#3323cc'
  secondary-fixed: '#d5e3fd'
  secondary-fixed-dim: '#b9c7e0'
  on-secondary-fixed: '#0d1c2f'
  on-secondary-fixed-variant: '#3a485c'
  tertiary-fixed: '#dae2fd'
  tertiary-fixed-dim: '#bec6e0'
  on-tertiary-fixed: '#131b2e'
  on-tertiary-fixed-variant: '#3f465c'
  background: '#f7f9fb'
  on-background: '#191c1e'
  surface-variant: '#e0e3e5'
typography:
  h1:
    fontFamily: Inter
    fontSize: 36px
    fontWeight: '700'
    lineHeight: 44px
    letterSpacing: -0.02em
  h2:
    fontFamily: Inter
    fontSize: 30px
    fontWeight: '600'
    lineHeight: 38px
    letterSpacing: -0.02em
  h3:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 4px
  xs: 0.5rem
  sm: 1rem
  md: 1.5rem
  lg: 2rem
  xl: 3rem
  gutter: 1.5rem
  margin: 2rem
---

## Brand & Style

The brand personality of the design system is anchored in reliability, efficiency, and clarity. It is designed specifically for a multi-tenant SaaS environment where support agents and IT managers require a tool that feels authoritative yet remains unobtrusive during high-stress workflows. 

The design movement is **Corporate / Modern**. It prioritizes high-fidelity execution through meticulous alignment and refined visual hierarchies. The aesthetic response should be one of "effortless organization," utilizing generous whitespace to reduce cognitive load when navigating complex data sets like service level agreements (SLAs) and ticket queues.

## Colors

The color palette establishes professional grounding through deep, authoritative tones balanced by a vibrant action color. 

- **Primary Indigo (#4F46E5):** Used exclusively for high-intent actions, primary buttons, and active navigational states.
- **Deep Navy (#0F172A):** Reserved for high-level structural elements like the sidebar or main header to provide a "frame" for the application.
- **Slate (#334155):** The workhorse color for secondary text, icons, and borders, ensuring high legibility without the harshness of pure black.
- **Neutral / Background (#F8FAFC):** A cool-toned off-white that prevents screen glare and differentiates content areas from the structural navy frame.

## Typography

This design system utilizes **Inter** for all typographic needs to take advantage of its exceptional legibility and systematic weight distribution. 

Headlines use tighter letter spacing and heavier weights to maintain a strong presence against data-heavy tables. Body text is optimized for long-form ticket descriptions with generous line heights. Labels utilize a medium weight or uppercase treatment to differentiate metadata from user-generated content.

## Layout & Spacing

The layout follows a **Fluid Grid** model with a standard 12-column system. For multi-tenant dashboards, the sidebar is fixed at 280px, while the main content area expands to fill the viewport.

Spacing is based on a 4px baseline grid to ensure mathematical harmony between elements. Content-heavy pages, such as the Ticket Listing, use the `md` (24px) spacing for margins to ensure the interface feels airy and premium, preventing "data claustrophobia."

## Elevation & Depth

This design system uses a combination of **Tonal Layers** and **Ambient Shadows** to create a sense of high-fidelity depth. 

- **Surface Level 0:** The main background (#F8FAFC).
- **Surface Level 1:** Card containers and data tables. These use a 1px border (#E2E8F0) and a very soft, diffused shadow (0px 4px 6px -1px rgba(15, 23, 42, 0.05)).
- **Surface Level 2:** Modals, dropdowns, and fly-out menus. These use a more pronounced shadow (0px 10px 15px -3px rgba(15, 23, 42, 0.1)) to indicate temporary interaction layers.

Shadows should never be pure black; they are tinted with the Deep Navy (#0F172A) to maintain color harmony across the interface.

## Shapes

The shape language is **Rounded**, reflecting a modern and approachable software aesthetic. 

Primary UI elements like buttons and input fields use a base radius of `0.5rem` (8px). Larger structural components like cards or dashboard widgets use the `rounded-lg` (16px) or `rounded-xl` (24px) property to soften the overall appearance of the platform and distinguish the platform from legacy, sharp-edged enterprise software.

## Components

### Buttons
Primary buttons use the Vibrant Indigo (#4F46E5) with white text. Secondary buttons use a Slate border with a transparent background. All buttons feature a 0.5rem corner radius and a subtle 2px hover transition.

### Chips & Status Badges
Used for ticket priority (Low, Medium, High). These utilize high-contrast, low-saturation backgrounds (e.g., a soft red background with a deep red text) to ensure status visibility without overwhelming the page.

### Input Fields
Inputs are styled with a Slate-200 border and a subtle inner shadow. On focus, the border transitions to Primary Indigo with a soft 3px outer glow.

### Data Tables
Tables are the heart of the ticketing platform. They feature "Zebra" striping on hover only, 16px cell padding, and sticky headers for long lists. Row height is generous (at least 56px) to maintain the clean, whitespace-forward aesthetic.

### Cards
Individual ticket summaries or pricing tiers are housed in cards with Surface Level 1 elevation. They feature a vertical color-coded bar on the left edge to denote ticket category or tenant branding.