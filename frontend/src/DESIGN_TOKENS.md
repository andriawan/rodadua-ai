# Design System & Tokens Reference

This document outlines the design tokens configured for the Rodadua Frontend application. These tokens ensure visual consistency across all components.

## Colors

### Primary (Motorcycle/Performance Theme)
- Purple-based color palette representing speed, precision, and quality.
- Class: `text-primary-500`, `bg-primary-600`, etc.
- **Scale**:
  - `50`: `#f5f3ff`
  - `100`: `#ede9fe`
  - `200`: `#ddd6fe`
  - `300`: `#c4b5fd`
  - `400`: `#a78bfa`
  - `500`: `#8b5cf6` (Default Primary)
  - `600`: `#7c3aed`
  - `700`: `#6d28d9`
  - `800`: `#5b21b6`
  - `900`: `#4c1d95`

*Usage Example*:
```vue
<button class="bg-primary-500 hover:bg-primary-600 text-white font-medium px-4 py-2 rounded-md">
  Register Motorcycle
</button>
```

### Secondary (Action / Success Highlight)
- Green-based palette for active indicators or confirmation states.
- **Scale**:
  - `50` to `900` starting from `#f0fdf4` to `#145231`.
  - `500`: `#22c55e` (Default Secondary)

### Semantic State Colors
- **Danger**: Red colors (`danger-50`: `#fef2f2`, `danger-500`: `#ef4444`, `danger-700`: `#b91c1c`)
- **Warning**: Amber colors (`warning-50`: `#fffbeb`, `warning-500`: `#f59e0b`, `warning-700`: `#b45309`)
- **Success**: Emerald colors (`success-50`: `#f0fdf4`, `success-500`: `#10b981`, `success-700`: `#047857`)
- **Info**: Blue colors (`info-50`: `#eff6ff`, `info-500`: `#3b82f6`, `info-700`: `#1d4ed8`)

### Neutral Colors
- Gray palette for backgrounds, borders, and text hierarchy.
- Scale: `neutral-50` (`#fafafa`) to `neutral-900` (`#171717`).

---

## Spacing Scale
- `4` (4px) - Extra small gaps, tight paddings
- `8` (8px) - Small paddings
- `12` (12px) - Standard layout elements padding
- `16` (16px) - Card and container internal padding
- `24` (24px) - Section spacing, larger layouts
- `32` (32px) - Content margins
- `48` (12rem/192px) - Structural spacing
- `64` (16rem/256px) - Large spacing gaps

---

## Typography

### Font Sizes
- `xs`: `0.75rem` (12px) - Captions, status info
- `sm`: `0.875rem` (14px) - Meta texts, helper text
- `base`: `1rem` (16px) - Body text
- `lg`: `1.125rem` (18px) - Subheadings
- `xl`: `1.25rem` (20px) - Component titles
- `2xl`: `1.5rem` (24px) - Modal / Card titles
- `3xl`: `1.875rem` (30px) - Page titles
- `4xl`: `2.25rem` (36px) - Marketing headlines

### Font Weights
- `light`: `300`
- `normal`: `400`
- `medium`: `500`
- `semibold`: `600`
- `bold`: `700`

---

## Border Radius
- `sm`: `0.25rem` (4px)
- `base`: `0.375rem` (6px)
- `md`: `0.5rem` (8px)
- `lg`: `0.75rem` (12px)
- `xl`: `1rem` (16px)

---

## Shadows
- `sm`: `0 1px 2px 0 rgba(0, 0, 0, 0.05)`
- `base`: `0 1px 3px 0 rgba(0, 0, 0, 0.1)`
- `md`: `0 4px 6px -1px rgba(0, 0, 0, 0.1)`
- `lg`: `0 10px 15px -3px rgba(0, 0, 0, 0.1)`
