import { Page, expect } from '@playwright/test';

export class LoginPage {
  constructor(private page: Page) {}

  async navigate() {
    await this.page.goto('/login');
  }

  async login(email: string, password: string) {
    await this.page.fill('input[name="email"]', email);
    await this.page.fill('input[name="password"]', password);
    await Promise.all([
        this.page.waitForURL(/.*dashboard/),
        this.page.click('button[type="submit"]')
    ]);
    
    // Check if we are actually logged in by looking for a logout button or user name
    await expect(this.page.locator('text=Logout')).toBeVisible();
  }
}
