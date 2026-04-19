import { test, expect } from '@playwright/test';
import { LoginPage } from '../pages/LoginPage';
import { RetailerPage } from '../pages/RetailerPage';
import { DistributorPage } from '../pages/DistributorPage';
import { execSync } from 'child_process';

test.describe('NDRC Sprint 2 Features', () => {
    
  test.beforeEach(async ({ page }) => {
      // 1. CLEAR SESSIONS
      await page.context().clearCookies();
      
      // 2. REFRESH DATABASE (Ensure clean slate for every test)
      try {
          console.log('--- REFRESHING TEST DATABASE ---');
          execSync('php artisan db:seed --class=PlaywrightSeeder');
      } catch (e) {
          console.error('DATABASE SEEDING FAILED:', e);
      }
  });
  
  test('Feature 1: Smart Order Recommendations (Retailer)', async ({ page }) => {
    const loginPage = new LoginPage(page);
    const retailerPage = new RetailerPage(page);

    await loginPage.navigate();
    await loginPage.login(process.env.RETAILER_EMAIL!, process.env.TEST_PASSWORD!);
    
    await retailerPage.navigateToSmartOrders();
    await retailerPage.verifyRecommendationsVisible();
  });

  test('Feature 2: Route Optimization (Distributor)', async ({ page }) => {
    const loginPage = new LoginPage(page);
    const distributorPage = new DistributorPage(page);

    await loginPage.navigate();
    await loginPage.login(process.env.DISTRIBUTOR_EMAIL!, process.env.TEST_PASSWORD!);
    
    await distributorPage.navigateToRouteOptimization();
    await distributorPage.selectOrdersForOptimization(2);
    await distributorPage.generateRoute();
    await distributorPage.saveRoute('Playwright Test Route');
  });

  test('Feature 3 & 4: Priority Engine & Territory Demand Map (Distributor)', async ({ page }) => {
    const loginPage = new LoginPage(page);
    const distributorPage = new DistributorPage(page);

    await loginPage.navigate();
    await loginPage.login(process.env.DISTRIBUTOR_EMAIL!, process.env.TEST_PASSWORD!);
    
    await distributorPage.navigateToDemandMap();
    await distributorPage.verifyPriorityEngine();
    await distributorPage.verifyHeatmap();
    await distributorPage.verifyDemandTrends();
  });

  test('End-to-End Workflow: Retailer Demand to Distributor Optimization', async ({ page }) => {
    const loginPage = new LoginPage(page);
    const retailerPage = new RetailerPage(page);
    const distributorPage = new DistributorPage(page);

    // 1. Retailer checks recommendations
    await loginPage.navigate();
    await loginPage.login(process.env.RETAILER_EMAIL!, process.env.TEST_PASSWORD!);
    await retailerPage.navigateToSmartOrders();
    await retailerPage.verifyRecommendationsVisible();

    // 2. Distributor optimizes and maps
    await page.context().clearCookies();
    await loginPage.navigate();
    await loginPage.login(process.env.DISTRIBUTOR_EMAIL!, process.env.TEST_PASSWORD!);
    
    await distributorPage.navigateToRouteOptimization();
    await distributorPage.selectOrdersForOptimization(2);
    await distributorPage.generateRoute();
    await distributorPage.saveRoute('E2E Workflow Route');
  });
});
