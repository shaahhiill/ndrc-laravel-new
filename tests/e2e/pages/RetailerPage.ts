import { Page, expect } from '@playwright/test';

export class RetailerPage {
  constructor(private page: Page) {}

  async navigateToSmartOrders() {
    await this.page.click('nav >> text=Smart Picks ✨');
    await expect(this.page).toHaveURL(/.*smart-orders/);
  }

  async verifyRecommendationsVisible() {
    // Target the specific recommendation card using the actual class
    const card = this.page.locator('.bg-white.rounded-\\[2\\.5rem\\]').first();
    await expect(card).toBeVisible();
    await expect(card).toContainText('AI Confidence');
  }

  async addRecommendedToCart() {
    await this.page.click('text=Add to Cart');
    // Verify toast or cart count change
    await expect(this.page.locator('text=Success')).toBeVisible();
  }
}
