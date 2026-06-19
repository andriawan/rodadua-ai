# Phase 4: Frontend Architecture - Implementation Checklist

Track your progress with these items:

- [x] **TypeScript Types** (30 min)
  - [x] types/api.ts
  - [x] types/user.ts
  - [x] types/motorcycle.ts
  - [x] types/maintenance.ts
  - [x] types/troubleshooting.ts
  - [x] types/workshop.ts

- [x] **Design System** (45 min)
  - [x] Update tailwind.config.js with color tokens
  - [x] Document spacing scale (4, 8, 12, 16, 24, 32, 48, 64)
  - [x] Document typography scale
  - [x] Create DESIGN_TOKENS.md

- [x] **Core Services** (2 hours)
  - [x] api.ts (HTTP client)
  - [x] AuthService.ts
  - [x] MotorcycleService.ts
  - [x] MaintenanceService.ts
  - [x] TroubleshootingService.ts
  - [x] WorkshopService.ts

- [x] **Pinia Stores** (1.5 hours)
  - [x] authStore.ts
  - [x] motorcycleStore.ts
  - [x] uiStore.ts

- [x] **Core Composables** (1 hour)
  - [x] useAuth.ts
  - [x] useMotorcycle.ts
  - [x] useMaintenance.ts
  - [x] useTroubleshooting.ts
  - [x] useWorkshop.ts

- [x] **Base Components** (2 hours)
  - [x] Create components directory structure
  - [x] BaseButton.vue
  - [x] BaseInput.vue
  - [x] BaseCard.vue
  - [x] LoadingSpinner.vue
  - [x] ErrorAlert.vue

- [x] **Feature Components** (2 hours)
  - [x] MotorcycleCard.vue
  - [x] MotorcycleForm.vue
  - [x] MaintenanceCard.vue
  - [x] RecommendationList.vue
  - [x] WorkshopCard.vue

- [x] **Pages/Views Structure** (1 hour)
  - [x] Create pages directory
  - [x] Create layout components (MainLayout, AuthLayout)
  - [x] Create page stubs (Home, List, Detail, Maintenance, Troubleshooting, WorkshopFinder)
  - [x] Create router (router/index.ts) with navigation guards
  - [x] Wire up Pinia + Router in main.js
  - [x] Update App.vue to use RouterView

- [ ] **Testing & Validation**
  - [ ] Verify types are strict (no `any`)
  - [ ] Test API client with backend
  - [ ] Test Pinia stores
  - [ ] Test composables

---

## Files Created / Modified Summary

### Types (`src/types/`)
| File | Status |
|------|--------|
| `api.ts` | ✅ Created |
| `user.ts` | ✅ Created |
| `motorcycle.ts` | ✅ Created |
| `maintenance.ts` | ✅ Created |
| `troubleshooting.ts` | ✅ Created |
| `workshop.ts` | ✅ Created |

### Services (`src/services/`)
| File | Status |
|------|--------|
| `api.ts` | ✅ Updated (fetch-based, fully typed) |
| `AuthService.ts` | ✅ Updated |
| `MotorcycleService.ts` | ✅ Created |
| `MaintenanceService.ts` | ✅ Created |
| `TroubleshootingService.ts` | ✅ Created |
| `WorkshopService.ts` | ✅ Created |

### Stores (`src/stores/`)
| File | Status |
|------|--------|
| `authStore.ts` | ✅ Created |
| `motorcycleStore.ts` | ✅ Created |
| `uiStore.ts` | ✅ Created |

### Composables (`src/composables/`)
| File | Status |
|------|--------|
| `useAuth.ts` | ✅ Created |
| `useMotorcycle.ts` | ✅ Created |
| `useMaintenance.ts` | ✅ Created |
| `useTroubleshooting.ts` | ✅ Created |
| `useWorkshop.ts` | ✅ Created |

### Components (`src/components/`)
| File | Status |
|------|--------|
| `BaseButton.vue` | ✅ Created |
| `BaseInput.vue` | ✅ Created |
| `BaseCard.vue` | ✅ Created |
| `LoadingSpinner.vue` | ✅ Created |
| `ErrorAlert.vue` | ✅ Created |
| `MotorcycleCard.vue` | ✅ Created |
| `MotorcycleForm.vue` | ✅ Created |
| `MaintenanceCard.vue` | ✅ Created |
| `RecommendationList.vue` | ✅ Created |
| `WorkshopCard.vue` | ✅ Created |

### Layouts (`src/layouts/`)
| File | Status |
|------|--------|
| `AuthLayout.vue` | ✅ Created |
| `MainLayout.vue` | ✅ Created |

### Pages (`src/pages/`)
| File | Status |
|------|--------|
| `Login.vue` | ✅ Created |
| `Register.vue` | ✅ Created |
| `Home.vue` | ✅ Created |
| `List.vue` | ✅ Created |
| `Detail.vue` | ✅ Created |
| `Maintenance.vue` | ✅ Created |
| `Troubleshooting.vue` | ✅ Created |
| `WorkshopFinder.vue` | ✅ Created |

### Router & Config
| File | Status |
|------|--------|
| `router/index.ts` | ✅ Created |
| `main.js` | ✅ Updated |
| `App.vue` | ✅ Updated |
| `tailwind.config.js` | ✅ Updated |
| `src/DESIGN_TOKENS.md` | ✅ Created |

---

**Status**: Phase 4 Complete ✅  
**Date**: 2026-06-19  
**Next**: Proceed to Phase 5 - Pages & Components (detailed implementation)
