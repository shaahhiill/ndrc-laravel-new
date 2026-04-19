# NDRC Sprint 2 - QA Automation Documentation 🚀

This document outlines the End-to-End (E2E) testing strategy and implementation for the NDRC Platform, specifically focusing on the features delivered in Sprint 2.

## 🛠️ Testing Stack
- **Framework**: [Playwright](https://playwright.dev/) (v1.40+)
- **Language**: TypeScript (ESM)
- **Pattern**: Page Object Model (POM)
- **Data Management**: Custom Laravel Seeders (`PlaywrightSeeder`)

---

## 🏗️ Architecture Overview

The test suite is structured for maximum maintainability:

```text
tests/e2e/
├── pages/                # Page Object Model (POM) Classes
│   ├── LoginPage.ts      # Authentication logic and shared components
│   ├── RetailerPage.ts   # Smart Recommendations & Ordering
│   └── DistributorPage.ts # Route Optimization, Priority Engine, and Maps
├── specs/                # Test Specifications
│   └── sprint2.spec.ts   # Main feature-specific and integration tests
├── .env                  # Test environment variables (Credentials/URLs)
└── PlaywrightSeeder.php  # DB state preparation for deterministic testing
```

---

## 🧪 Features Tested (Sprint 2)

### 1. Smart Order Recommendations (Retailer)
- **Goal**: Verify that AI-driven product suggestions are rendered accurately.
- **Validations**:
  - Verification of "AI Confidence" indicators.
  - Presence of dynamic product categories (Dairy, Beverages, etc.).
  - Navigational flow from dashboard to Smart Picks.

### 2. Distributor Route Optimization
- **Goal**: Ensure the routing engine generates valid sequences and interactive maps.
- **Validations**:
  - Dynamic selection of pending orders.
  - Verification of "Optimized Sequence" calculation (Google Maps API / Fallback).
  - Saving and assignment of routes to drivers.

### 3. Delivery Priority Engine
- **Goal**: Confirm that high-value and urgent orders are correctly surfaced.
- **Validations**:
  - Presence of "High Priority" scoring badges.
  - Accurate rendering of the Priority Engine UI component.

### 4. Territory Demand Map
- **Goal**: Validate geospatial demand heatmaps and trend analytics.
- **Validations**:
  - Leaflet.js map container rendering.
  - Heatmap layer visibility.
  - ApexCharts demand trend rendering.

---

## 🏃 Automation Scripts

The following scripts are registered in the root `package.json`:

| Command | Description |
| :--- | :--- |
| `npm run test:e2e` | Runs all tests in headless mode (default: Chrome). |
| `npm run test:e2e:ui` | Opens the **Playwright UI Mode** for interactive debugging. |
| `npm run test:e2e:report` | Opens the graphical HTML test report after a run. |

---

## ⚙️ Environment Setup & Prerequisites

### 1. Database Preparation
To ensure tests don't fail due to missing data, run the dedicated playwright seeder:
```powershell
php artisan db:seed --class=PlaywrightSeeder
```

### 2. Configuration (`playwright.config.ts`)
- **Base URL**: Defaults to `http://127.0.0.1:8000`.
- **Workers**: Set to `1` for sequential execution (prevents database lock/session issues).
- **Timeout**: Extended to `60s` to account for Laravel/Vite compilation times.

---

## 💡 Suggestions for Future QA Sprints

1. **Visual Regression Testing**: Integrate Playwright's `expect(page).toHaveScreenshot()` to catch subtle styling regressions in the heatmap and charts.
2. **API Mocking**: Mock the Google Maps API in CI environments to reduce external dependency costs and increase test speed.
3. **Load Testing Integration**: Utilize Playwright traces to measure page load performance across different regions.
4. **Accessibility (a11y) Testing**: Add `@axe-core/playwright` to ensure the new dashboards are accessible to all users.

---

*Document generated for NDRC Platform Development.*
