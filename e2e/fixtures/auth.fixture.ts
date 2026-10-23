import { test as base, expect, Page } from '@playwright/test';

type AuthFixtures = {
  staffPage: Page;
  kadesPage: Page;
  loginAsStaff: (name?: string) => Promise<void>;
  loginAsKades: () => Promise<void>;
};

export const test = base.extend<AuthFixtures>({
  loginAsStaff: async ({ page }, use) => {
    const doLogin = async (staffName = 'Amanda Rahmawati') => {
      await page.goto('/');
      await page.fill('[data-testid="input-username"]', 'staff');
      await page.fill('[data-testid="input-staff-name"]', staffName);
      await page.fill('[data-testid="input-password"]', 'password123');
      await page.click('[data-testid="btn-login-submit"]');
      await expect(page.locator('[data-testid="header-user-info"]')).toBeVisible();
    };
    await use(doLogin);
  },

  loginAsKades: async ({ page }, use) => {
    const doLogin = async () => {
      await page.goto('/');
      await page.fill('[data-testid="input-username"]', 'kades');
      await page.fill('[data-testid="input-password"]', 'kades123');
      await page.click('[data-testid="btn-login-submit"]');
      await expect(page.locator('[data-testid="header-user-info"]')).toBeVisible();
    };
    await use(doLogin);
  },

  staffPage: async ({ page }, use) => {
    await page.goto('/');
    await page.fill('[data-testid="input-username"]', 'staff');
    await page.fill('[data-testid="input-staff-name"]', 'Amanda Rahmawati');
    await page.fill('[data-testid="input-password"]', 'password123');
    await page.click('[data-testid="btn-login-submit"]');
    await expect(page.locator('[data-testid="header-user-info"]')).toBeVisible();
    await use(page);
  },

  kadesPage: async ({ page }, use) => {
    await page.goto('/');
    await page.fill('[data-testid="input-username"]', 'kades');
    await page.fill('[data-testid="input-password"]', 'kades123');
    await page.click('[data-testid="btn-login-submit"]');
    await expect(page.locator('[data-testid="header-user-info"]')).toBeVisible();
    await use(page);
  },
});

export { expect };
