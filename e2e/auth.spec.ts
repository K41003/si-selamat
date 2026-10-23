import { test, expect } from './fixtures/auth.fixture';

test.describe('Auth Journey (Login, Validation, Recovery, Logout)', () => {
  test('Happy Path: Staff login with valid credentials & shift name', async ({ page }) => {
    await page.goto('/');

    // Verify form elements are visible
    await expect(page.locator('[data-testid="input-username"]')).toBeVisible();
    await expect(page.locator('[data-testid="input-password"]')).toBeVisible();

    // Fill credentials
    await page.fill('[data-testid="input-username"]', 'staff');
    // Shift input appears dynamically for staff
    await expect(page.locator('[data-testid="input-staff-name"]')).toBeVisible();
    await page.fill('[data-testid="input-staff-name"]', 'Amanda Rahmawati');
    await page.fill('[data-testid="input-password"]', 'password123');

    // Submit
    await page.click('[data-testid="btn-login-submit"]');

    // Should redirect to dashboard and show user identity in header
    await expect(page.locator('[data-testid="header-user-info"]')).toBeVisible();
    await expect(page.locator('[data-testid="text-header-staff-name"]')).toHaveText('Amanda Rahmawati');
  });

  test('Happy Path: Kades login with valid credentials', async ({ page }) => {
    await page.goto('/');

    await page.fill('[data-testid="input-username"]', 'kades');
    await page.fill('[data-testid="input-password"]', 'kades123');
    await page.click('[data-testid="btn-login-submit"]');

    // Should show Kades Header & Nav
    await expect(page.locator('[data-testid="header-user-info"]')).toBeVisible();
    await expect(page.locator('[data-testid="text-header-kades-name"]')).toContainText('H. Ahmad Fauzi');
    await expect(page.locator('[data-testid="nav-item-logs"]')).toBeVisible();
  });

  test('Failure State: Login with invalid password', async ({ page }) => {
    await page.goto('/');

    await page.fill('[data-testid="input-username"]', 'staff');
    await page.fill('[data-testid="input-staff-name"]', 'Amanda');
    await page.fill('[data-testid="input-password"]', 'wrongpass999');
    await page.click('[data-testid="btn-login-submit"]');

    await expect(page.locator('[data-testid="login-error-alert"]')).toBeVisible();
    await expect(page.locator('[data-testid="login-error-alert"]')).toContainText('Password');
  });

  test('Failure State: Login with unknown username', async ({ page }) => {
    await page.goto('/');

    await page.fill('[data-testid="input-username"]', 'unknown_user');
    await page.fill('[data-testid="input-password"]', 'password123');
    await page.click('[data-testid="btn-login-submit"]');

    await expect(page.locator('[data-testid="login-error-alert"]')).toBeVisible();
    await expect(page.locator('[data-testid="login-error-alert"]')).toContainText('tidak terdaftar');
  });

  test('Happy Path: Password recovery workflow via email OTP', async ({ page }) => {
    await page.goto('/');

    await page.click('[data-testid="btn-forgot-password"]');
    await expect(page.locator('[data-testid="form-forgot-step-1"]')).toBeVisible();

    // Step 1: Send OTP
    await page.fill('[data-testid="input-reset-email"]', 'staff@tanjungselamat.desa.id');
    await page.click('[data-testid="btn-send-otp"]');

    // Step 2: Verify OTP
    await expect(page.locator('[data-testid="form-forgot-step-2"]')).toBeVisible();
    const otpCode = await page.locator('[data-testid="text-simulated-otp"]').textContent();
    expect(otpCode).toBeTruthy();
    await page.fill('[data-testid="input-otp-code"]', otpCode!.trim());
    await page.click('[data-testid="btn-verify-otp"]');

    // Step 3: Set new password
    await expect(page.locator('[data-testid="form-forgot-step-3"]')).toBeVisible();
    await page.fill('[data-testid="input-new-password"]', 'newpassword123');
    await page.fill('[data-testid="input-confirm-password"]', 'newpassword123');
    await page.click('[data-testid="btn-save-new-password"]');

    // Automatically logged in with the new password
    await expect(page.locator('[data-testid="header-user-info"]')).toBeVisible();
  });

  test('Happy Path: Logout securely', async ({ staffPage }) => {
    await expect(staffPage.locator('[data-testid="btn-logout"]')).toBeVisible();
    await staffPage.click('[data-testid="btn-logout"]');

    // Should return to login page
    await expect(staffPage.locator('[data-testid="form-login"]')).toBeVisible();
  });
});
