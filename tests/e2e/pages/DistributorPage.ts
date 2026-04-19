import { Page, expect } from '@playwright/test';

export class DistributorPage {
  constructor(private page: Page) {
      this.page.on('console', msg => {
          if (msg.type() === 'error') console.log(`BROWSER ERROR: ${msg.text()}`);
          if (msg.text().includes('ALPINE:')) console.log(`APP LOG: ${msg.text()}`);
      });
  }

  async navigateToRouteOptimization() {
    await this.page.click('nav >> text=Route Planning 🚚');
    await expect(this.page).toHaveURL(/.*route-optimization/);
  }

  async navigateToDemandMap() {
    await this.page.click('nav >> text=Demand Map 🔥');
    await expect(this.page).toHaveURL(/.*demand-analytics/);
  }

  // Feature: Route Optimization
  async selectOrdersForOptimization(count: number = 2) {
    console.log('--- STARTING ORDER SELECTION ---');
    
    // GUARD: Ensure Alpine.js is fully loaded and initialized
    await this.page.waitForFunction(() => (window as any).Alpine && (window as any).Alpine.version, { timeout: 10000 });
    
    const orders = this.page.locator('[data-testid="order-card"]');
    await orders.first().waitFor({ state: 'visible', timeout: 15000 });

    const orderCount = await orders.count();
    const iterations = Math.min(count, orderCount);

    for(let i=0; i<iterations; i++) {
        const order = orders.nth(i);
        
        // Human-like click with tiny delay to allow Alpine event listeners to capture accurately
        await order.click({ force: true, delay: 150 });
        
        // Verify selection sync with a generous timeout
        const badge = order.locator('[data-testid="order-badge"]');
        await expect(badge).toHaveClass(/bg-nestle-blue/, { timeout: 10000 });
        console.log(`Order ${i+1} selection confirmed.`);
    }

    const generateBtn = this.page.locator('button:has-text("Generate Optimal Route")');
    await expect(generateBtn).toBeEnabled({ timeout: 5000 });
  }

  async generateRoute() {
    console.log('Clicking Generate Optimal Route...');
    const generateBtn = this.page.locator('button:has-text("Generate Optimal Route")');
    await generateBtn.click();
    await expect(this.page.locator('text=Optimized Sequence')).toBeVisible({ timeout: 15000 });
    console.log('Route generated successfully.');
  }

  async saveRoute(name: string) {
    this.page.once('dialog', dialog => dialog.accept(name));
    await this.page.click('text=Save & Assign Route');
    const toast = this.page.locator('text=Route saved successfully');
    await expect(toast).toBeVisible({ timeout: 10000 });
    console.log(`Route "${name}" saved.`);
  }

  // Feature: Priority Engine & Demand Map
  async verifyPriorityEngine() {
    await expect(this.page.locator('h2:has-text("Priority Engine")')).toBeVisible();
    await expect(this.page.locator('p:has-text("High Priority")').first()).toBeVisible();
  }

  async verifyHeatmap() {
    await expect(this.page.locator('#map')).toBeVisible();
  }

  async verifyDemandTrends() {
    await expect(this.page.locator('#demandChart')).toBeVisible();
  }
}
