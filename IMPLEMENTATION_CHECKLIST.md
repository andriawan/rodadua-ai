# rodaduaAI - Implementation Plan & Checklist

**Project**: AI-Powered Motorcycle Assistant for Indonesian Riders  
**Date Created**: 2026-06-18  
**Status**: Planning Phase

---

## 📋 Phase 1: Project Foundation Setup

### Backend Infrastructure
- [ ] Laravel 12 project initialization
  - [ ] Configure PHP 8.4+ environment
  - [ ] Install Laravel 12 dependencies
  - [ ] Set up environment variables (.env)
- [ ] Database setup
  - [ ] MySQL 8 configuration
  - [ ] Create initial database
  - [ ] Run migrations
- [ ] Redis configuration
  - [ ] Install and configure Redis
  - [ ] Set up cache store
  - [ ] Configure queue driver
- [ ] Laravel authentication
  - [ ] Install Laravel Sanctum
  - [ ] Configure API authentication
  - [ ] Set up token management

### Frontend Infrastructure
- [ ] Vue 3 + Vite project setup
  - [ ] Initialize Vite project
  - [ ] Configure Vue 3
- [ ] TypeScript configuration
  - [ ] Set up tsconfig.json
  - [ ] Configure strict mode (no `any`)
- [ ] TailwindCSS setup
  - [ ] Install and configure Tailwind
  - [ ] Create design token system
- [ ] State Management
  - [ ] Install and configure Pinia
  - [ ] Create store structure
- [ ] Build tools
  - [ ] Configure Vite build
  - [ ] Set up development server

### Project Structure
- [ ] Backend folder structure
  - [ ] Create app/Actions
  - [ ] Create app/DTOs
  - [ ] Create app/Services
  - [ ] Create app/Repositories
  - [ ] Create app/Jobs
  - [ ] Create app/Events
  - [ ] Create app/Policies
  - [ ] Create app/Exceptions
  - [ ] Organize Http (Controllers, Requests, Resources)
- [ ] Frontend folder structure
  - [ ] Create src/components
  - [ ] Create src/composables
  - [ ] Create src/stores
  - [ ] Create src/services
  - [ ] Create src/types
  - [ ] Create src/pages
  - [ ] Create src/layouts

---

## 🔐 Phase 2: Security & Authentication

### Authentication
- [ ] Implement Laravel Sanctum
  - [ ] Configure sanctum middleware
  - [ ] Set up token issuance
  - [ ] Implement logout/revoke
- [ ] Frontend authentication
  - [ ] Create auth composable
  - [ ] Implement login flow
  - [ ] Handle token storage
  - [ ] Implement logout

### Authorization
- [ ] Create base Policy class
- [ ] Implement Gates for global permissions
- [ ] User role structure
  - [ ] Define user roles
  - [ ] Create role migration
  - [ ] Implement role-based authorization

### Secrets Management
- [ ] Environment variables
  - [ ] Create .env.example
  - [ ] Document all required variables
  - [ ] Set up local .env
- [ ] AI Provider configuration
  - [ ] Configure OpenAI API key
  - [ ] Configure DeepSeek API key
  - [ ] Document provider selection

---

## 🏗️ Phase 3: Backend Architecture

### Data Models & Migrations
- [ ] User model and migration
- [ ] Motorcycle model and migration
  - [ ] Add indexes
  - [ ] Add foreign keys
- [ ] Workshop model and migration
- [ ] Spare parts model and migration
- [ ] Maintenance records model
- [ ] Create supporting models
  - [ ] Add created_at/updated_at timestamps
  - [ ] Implement soft deletes where needed

### DTOs (Data Transfer Objects)
- [ ] Create UserDTO
- [ ] Create MotorcycleDTO
- [ ] Create MaintenanceDTO
- [ ] Create TroubleshootingDTO
- [ ] Create ComparisonDTO
- [ ] Document DTO structure

### Services
- [ ] MotorcycleService
  - [ ] create()
  - [ ] update()
  - [ ] delete()
  - [ ] getSpecifications()
- [ ] MaintenanceService
  - [ ] getRecommendations()
  - [ ] logMaintenance()
- [ ] TroubleshootingService
  - [ ] analyzeProblem()
  - [ ] getSolutions()
- [ ] ComparisonService
  - [ ] compareMotorcycles()
- [ ] WorkshopService
  - [ ] searchWorkshops()
  - [ ] getRecommendations()
- [ ] AIService (Provider interface)
  - [ ] Implement AIProviderInterface
  - [ ] Create OpenAIProvider
  - [ ] Create DeepSeekProvider

### Repositories
- [ ] MotorcycleRepository
  - [ ] getAllWithRelations()
  - [ ] searchBySpecs()
  - [ ] implement eager loading
- [ ] WorkshopRepository
  - [ ] searchByLocation()
  - [ ] getByRating()
- [ ] MaintenanceRepository
  - [ ] getHistoryForMotorcycle()
  - [ ] getSchedule()

### Form Requests (Validation)
- [ ] StoreMotorcycleRequest
- [ ] UpdateMotorcycleRequest
- [ ] StoreTroubleshootRequest
- [ ] StoreMaintenanceRequest
- [ ] Implement custom validation rules

### Controllers
- [ ] MotorcycleController
  - [ ] Keep thin (delegate to services)
- [ ] MaintenanceController
- [ ] TroubleshootingController
- [ ] ComparisonController
- [ ] WorkshopController

### API Endpoints
- [ ] GET /api/motorcycles
- [ ] GET /api/motorcycles/{id}
- [ ] POST /api/motorcycles
- [ ] PUT /api/motorcycles/{id}
- [ ] DELETE /api/motorcycles/{id}
- [ ] GET /api/maintenance/recommendations
- [ ] POST /api/troubleshooting/analyze
- [ ] GET /api/motorcycles/{id}/compare/{otherId}
- [ ] GET /api/workshops/search
- [ ] Implement response format (success/error)

---

## 🎨 Phase 4: Frontend Architecture

### Design System
- [ ] Define color tokens
  - [ ] Create design tokens file
  - [ ] Use only design tokens (no hardcoded colors)
  - [ ] Configure Tailwind with tokens
- [ ] Define spacing scale
  - [ ] Allowed values: 4, 8, 12, 16, 24, 32, 48, 64
- [ ] Define typography tokens
  - [ ] Create font size system
  - [ ] Create font weight system

### Core Composables
- [ ] useAuth() - authentication state
- [ ] useMotorcycle() - motorcycle data fetching
- [ ] useMaintenance() - maintenance recommendations
- [ ] useTroubleshooting() - problem analysis
- [ ] useComparison() - motorcycle comparison
- [ ] useWorkshop() - workshop search

### Core Services
- [ ] api.ts - HTTP client setup
- [ ] MotorcycleService
- [ ] MaintenanceService
- [ ] TroubleshootingService
- [ ] ComparisonService
- [ ] WorkshopService

### Pinia Stores
- [ ] auth store
  - [ ] user state
  - [ ] token management
- [ ] motorcycle store
  - [ ] list state
  - [ ] current motorcycle
  - [ ] favorites
- [ ] UI store
  - [ ] loading states
  - [ ] modals/dialogs

### Pages/Views
- [ ] Dashboard/Home
- [ ] Motorcycle List
- [ ] Motorcycle Detail
- [ ] Add Motorcycle
- [ ] Maintenance Page
- [ ] Troubleshooting Page
- [ ] Comparison Page
- [ ] Workshop Finder
- [ ] User Profile
- [ ] Settings

### Components (Under 300 lines)
- [ ] MotorcycleCard
- [ ] MotorcycleForm
- [ ] MaintenanceCard
- [ ] RecommendationList
- [ ] TroubleshootingForm
- [ ] WorkshopCard
- [ ] SearchBar
- [ ] Navigation
- [ ] Footer
- [ ] LoadingSpinner
- [ ] ErrorAlert
- [ ] Modal wrapper

### TypeScript Types
- [ ] Create types/motorcycle.ts
- [ ] Create types/maintenance.ts
- [ ] Create types/troubleshooting.ts
- [ ] Create types/workshop.ts
- [ ] Create types/user.ts
- [ ] Create types/api.ts

---

## 🗄️ Phase 5: Database & Queries

### Database Design
- [ ] Create motorcycles table
  - [ ] Add indexes on brand, model, year
  - [ ] Add foreign keys
- [ ] Create users table
  - [ ] Add unique email index
  - [ ] Add role foreign key
- [ ] Create maintenance_records table
  - [ ] Foreign key to motorcycles
  - [ ] Foreign key to users
- [ ] Create spare_parts table
- [ ] Create workshops table
  - [ ] Geospatial indexes for location
- [ ] Create troubleshooting_history table
- [ ] Create ai_prompts table (versioned)
- [ ] Create comparison_history table

### Query Optimization
- [ ] Implement eager loading with() in repositories
- [ ] Implement lazy loading scenarios
- [ ] Use loadMissing() where needed
- [ ] Add database indexes
- [ ] Test N+1 query scenarios
- [ ] Create query scopes

### Migrations Strategy
- [ ] Create migration files
- [ ] Add proper constraints
- [ ] Document migration dependencies
- [ ] Test rollback scenarios

---

## 🤖 Phase 6: AI Integration

### AI Provider Architecture
- [ ] Create AIProviderInterface
- [ ] Implement OpenAIProvider
  - [ ] Setup OpenAI connection
  - [ ] Implement model selection
  - [ ] Handle API errors
- [ ] Implement DeepSeekProvider
  - [ ] Setup DeepSeek connection
  - [ ] Implement model selection
  - [ ] Handle API errors

### Prompt Management
- [ ] Create prompts database table
- [ ] Create prompt versioning system
- [ ] Store prompts in database (not hardcoded)
  - [ ] Troubleshooting prompts
  - [ ] Maintenance prompts
  - [ ] Comparison prompts
  - [ ] Recommendation prompts
- [ ] Document prompt versions

### AI Services
- [ ] TroubleshootingAIService
  - [ ] Analyze motorcycle problems
  - [ ] Generate solutions
- [ ] MaintenanceAIService
  - [ ] Generate maintenance recommendations
  - [ ] Schedule predictions
- [ ] ComparisonAIService
  - [ ] Compare motorcycles intelligently
  - [ ] Provide insights
- [ ] SpecificationAIService
  - [ ] Extract specs
  - [ ] Validate specs

### Queue Integration
- [ ] Setup Laravel Queue (Redis)
- [ ] Create AI processing jobs
  - [ ] TroubleshootingJob
  - [ ] MaintenanceJob
  - [ ] ComparisonJob
- [ ] Setup Laravel Horizon for monitoring
- [ ] Implement job retry logic
- [ ] Add logging for AI calls

### AI Safety
- [ ] Implement input validation
- [ ] Implement output validation
- [ ] Add content filtering
- [ ] Log all AI requests/responses
- [ ] Add rate limiting

---

## 🧪 Phase 7: Testing

### Backend Tests
- [ ] Setup test database
- [ ] Configure testing environment
- [ ] Feature Tests
  - [ ] Auth endpoints
  - [ ] Motorcycle CRUD
  - [ ] Maintenance endpoints
  - [ ] Troubleshooting endpoints
  - [ ] Comparison endpoints
  - [ ] Workshop search
- [ ] Unit Tests
  - [ ] Service tests
  - [ ] Repository tests
  - [ ] DTO tests
  - [ ] Validation tests
- [ ] AI Provider Tests
  - [ ] OpenAI mock tests
  - [ ] DeepSeek mock tests
  - [ ] Error handling
- [ ] Integration Tests
  - [ ] Queue job tests
  - [ ] Cache tests

### Frontend Tests
- [ ] Setup Vitest/Jest
- [ ] Component Tests
  - [ ] MotorcycleCard
  - [ ] Forms
  - [ ] Critical components
- [ ] E2E Tests
  - [ ] Login flow
  - [ ] Add motorcycle flow
  - [ ] Get maintenance recommendation
  - [ ] Troubleshoot flow
- [ ] Composable Tests
- [ ] Store Tests

### Test Coverage
- [ ] Backend: Aim for >80% coverage
- [ ] Frontend critical paths: 100%
- [ ] Services: >90% coverage

---

## 📦 Phase 8: Deployment & DevOps

### Environment Setup
- [ ] Production environment config
- [ ] Staging environment config
- [ ] CI/CD pipeline
  - [ ] Automated testing
  - [ ] Linting checks
  - [ ] Build pipeline
- [ ] Docker setup (optional)
  - [ ] Laravel container
  - [ ] PostgreSQL container
  - [ ] Redis container

### Performance & Monitoring
- [ ] Setup error logging (Sentry)
- [ ] Setup performance monitoring
- [ ] Setup API monitoring
- [ ] Database query optimization
- [ ] Caching strategy
  - [ ] Query result caching
  - [ ] Page caching

### Security Hardening
- [ ] CORS configuration
- [ ] Rate limiting
- [ ] SQL injection prevention
- [ ] XSS prevention
- [ ] CSRF token handling
- [ ] Security headers

---

## ✅ Phase 9: Code Quality & Standards

### Code Standards
- [ ] PHP Coding Standards (PSR-12)
- [ ] TypeScript strict mode enabled
- [ ] ESLint configuration
- [ ] Prettier configuration
- [ ] Laravel Pint setup
- [ ] Static analysis
  - [ ] PHPStan
  - [ ] ESLint
  - [ ] TypeScript compiler

### Git Workflow
- [ ] Setup commit message standards
  - [ ] feat(feature): description
  - [ ] fix(area): description
  - [ ] refactor(area): description
  - [ ] test(area): description
- [ ] Pull request template
- [ ] Branch naming conventions

### Documentation
- [ ] API documentation
  - [ ] Endpoint descriptions
  - [ ] Request/response examples
  - [ ] Error codes
- [ ] Architecture documentation
- [ ] Setup guide
- [ ] Contributing guidelines
- [ ] Database schema documentation
- [ ] Code style guide

---

## 💰 Phase 10: Revenue Strategy
 
> rodaduaAI uses a freemium model combined with B2B workshop partnerships, built for the Indonesian motorcycle market. The goal: large free user base → convert high-intent users to paid tiers + recurring B2B income from local bengkel.
 
### 10.1 Freemium Tier Structure
 
| Feature | Free | Plus | Pro |
|---|---|---|---|
| AI chat / day | 10–20 messages | Unlimited | Unlimited |
| Vehicles registered | Up to 5 | Up to 20 | Unlimited |
| Troubleshooting history | Last 30 days | Full history | Full history |
| Maintenance recommendations | Basic | Advanced + schedule | Advanced + schedule |
| Motorcycle comparison | 2 at a time | Up to 5 | Up to 10 |
| Workshop finder | Search only | Search + reviews | Search + priority booking |
| AI queue priority | Standard | Priority | Dedicated |
| Ads | Yes | No | No |
| Export (PDF/CSV) | No | Yes | Yes |
| API access | No | No | Yes |
 
---
 
### 10.2 Subscription Plans
 
#### Free Plan
- [ ] 10–20 AI chat messages per day (resets midnight WIB via Redis job)
- [ ] Max 5 vehicles registered
- [ ] Basic maintenance reminders
- [ ] Ad-supported
#### Plus Plan — Rp 29.000–49.000 / month
- [ ] Unlimited AI chat
- [ ] Up to 20 vehicles
- [ ] Full troubleshooting & maintenance history
- [ ] Advanced AI recommendations with service scheduling
- [ ] No ads, priority AI queue
- [ ] PDF/CSV data export
#### Pro Plan — Rp 89.000–149.000 / month
- [ ] Everything in Plus
- [ ] Unlimited vehicle registrations
- [ ] API access (for fleet managers, dealerships)
- [ ] Up to 10 simultaneous motorcycle comparisons
- [ ] Dedicated AI queue + priority support
**Implementation checklist:**
- [ ] Design tier permission matrix
- [ ] Implement per-user daily AI message counter in Redis
- [ ] Midnight quota reset job (Laravel Scheduler)
- [ ] Paywall UI components (upgrade prompts, limit warnings)
- [ ] Integrate Midtrans / Xendit payment gateway (IDR)
- [ ] Subscription lifecycle management (create, cancel, upgrade, downgrade)
- [ ] Webhook handling for payment events
- [ ] 7-day free Plus trial on signup
---
 
### 10.3 B2B Workshop Partnership Program
 
Partner with local bengkel across Indonesia for referral and lead generation revenue.
 
**How it works:**
- Users find nearby workshops via the Workshop Finder
- Partnered workshops get a verified badge, higher search ranking, and a direct booking button
- rodaduaAI earns a monthly SaaS fee + per-booking commission from each partner
- Workshops get a business dashboard for bookings, reviews, and customer history
#### Workshop Partner Tiers
 
| Tier | Monthly Fee | Benefits |
|---|---|---|
| Basic | Rp 99.000 | Verified badge, basic listing, appear in search |
| Standard | Rp 249.000 | Priority ranking, booking button, reviews, analytics |
| Premium | Rp 499.000 | Top placement, push notifications to nearby users, monthly report, API integration |
 
**Additional workshop revenue:**
- Per-booking commission: Rp 5.000–15.000 per confirmed appointment
- Promoted listing: workshops bid for top placement in their area
- Spare parts inventory: workshops list parts, users buy via app (marketplace commission)
**Implementation checklist:**
- [ ] Workshop registration & onboarding flow
- [ ] Workshop business dashboard (bookings, reviews, analytics)
- [ ] Verified partner badge system
- [ ] Ranking algorithm with partner tier weighting
- [ ] Booking/appointment system with confirmation flow
- [ ] Commission tracking and automated invoicing
- [ ] Workshop performance analytics
- [ ] Partner contract & agreement management
---
 
### 10.4 Additional Revenue Streams
 
#### Spare Parts Marketplace Commission
- [ ] Allow spare parts sellers to list inventory on the platform
- [ ] Earn 3–8% commission per transaction
- [ ] AI recommends specific parts based on model and diagnosed issues
- [ ] Integrate with Tokopedia/Shopee API for expanded catalog
#### In-App Advertising (Free Tier Only)
- [ ] Show motorcycle-relevant ads to free users only (helmet brands, lubricants, tires)
- [ ] Google AdMob or in-house ad server
- [ ] Strict category filter — motorcycle-related ads only
#### B2B Data Insights
- [ ] Sell anonymized, aggregated market data to motorcycle manufacturers
- [ ] Common breakdown patterns by model/year/region
- [ ] Maintenance trend reports for dealership networks
- [ ] Quarterly industry reports (Rp 5–20 juta per report)
> ⚠️ Requires explicit user consent and full anonymization before any data sale. Legal review mandatory.
 
#### Dealership & Manufacturer Partnerships
- [ ] Honda, Yamaha, Suzuki, Kawasaki dealer integrations
- [ ] Sponsored recommendations ("Your Honda Beat needs service — book at authorized dealer")
- [ ] New model promotion placements in comparison feature
- [ ] Annual partnership contracts (Rp 50–200 juta / brand)
#### Premium AI Add-ons (Pay-per-use)
- [ ] Photo diagnosis: upload engine photo, AI identifies issues — Rp 5.000/scan
- [ ] Voice troubleshooting: describe problem via voice, get AI diagnosis
- [ ] Pre-purchase inspection report: AI analysis before buying a used motorcycle
---
 
### 10.5 Revenue Projections (Conservative)
 
| Revenue Stream | Year 1 | Year 2 | Notes |
|---|---|---|---|
| Plus subscriptions | Rp 50 juta | Rp 250 juta | ~2% conversion of free users |
| Pro subscriptions | Rp 20 juta | Rp 100 juta | Fleet owners, power users |
| Workshop partnerships | Rp 30 juta | Rp 200 juta | 100 partners Y1 → 500 Y2 |
| In-app ads | Rp 15 juta | Rp 50 juta | Free tier only |
| Marketplace commissions | Rp 10 juta | Rp 80 juta | Parts & bookings |
| B2B data/partnerships | — | Rp 100 juta | Requires scale first |
| **TOTAL** | **~Rp 125 juta** | **~Rp 780 juta** | Conservative estimate |
 
---
 
### 10.6 Monetization Implementation Checklist
- [ ] Design and implement usage limit middleware (AI quota per tier)
- [ ] Build subscription management system
- [ ] Integrate Midtrans or Xendit payment gateway
- [ ] Upgrade/downgrade flow with prorated billing
- [ ] 7-day free Plus trial on registration
- [ ] Workshop partner portal & dashboard
- [ ] Verified badge and ranking system
- [ ] Booking and commission tracking system
- [ ] Ad serving for free tier users
- [ ] Marketplace commission tracking
- [ ] Workshop partnership agreement template
- [ ] Internal revenue dashboard (MRR, churn, LTV)
- [ ] Subscription analytics
- [ ] Terms of service & privacy policy covering data usage
- [ ] User consent flows for data usage

---

## 🎯 Definition of Done Checklist

For **EVERY** feature implementation:

- [ ] Feature is fully implemented
- [ ] Input validation is implemented (Form Request)
- [ ] Authorization is implemented (Policy/Gate)
- [ ] Tests are implemented (Feature + Unit minimum)
- [ ] TypeScript types are added (if frontend)
- [ ] No linting errors (ESLint/Pint)
- [ ] No static analysis errors (PHPStan/TypeScript)
- [ ] Documentation is updated
- [ ] Code follows project conventions
- [ ] No N+1 queries (backend)
- [ ] No hardcoded secrets
- [ ] PR reviewed and approved

---

## 📊 Progress Tracking

| Phase | Status | Start Date | End Date | Notes |
|-------|--------|-----------|----------|-------|
| 1. Foundation | ⬜ | - | - | Not started |
| 2. Security | ⬜ | - | - | Not started |
| 3. Backend | ⬜ | - | - | Not started |
| 4. Frontend | ⬜ | - | - | Not started |
| 5. Database | ⬜ | - | - | Not started |
| 6. AI Integration | ⬜ | - | - | Not started |
| 7. Testing | ⬜ | - | - | Not started |
| 8. Deployment | ⬜ | - | - | Not started |
| 9. Code Quality | ⬜ | - | - | Not started |

---

## 🚀 Quick Start Order

**Recommended implementation order for MVP:**

1. **Foundation** → Project setup & basic structure
2. **Database** → Core data models
3. **Backend** → Services, repositories, controllers
4. **Security** → Auth, authorization, validation
5. **Frontend** → Pages, components, composables
6. **Testing** → Add tests for critical flows
7. **AI Integration** → Connect to AI providers
8. **Code Quality** → Final cleanup, documentation
9. **Deployment** → Setup production environment

---

## 📝 Notes

- Follow ALL rules from AGENTS.md strictly
- Never compromise on security
- Prioritize maintainability over clever code
- Keep components and services under size limits
- Always validate and authorize
- Test-first approach preferred
