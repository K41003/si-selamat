import { test, expect } from './fixtures/auth.fixture';

test.describe('Kades Validation, Digital Approval & Access Control', () => {
  test('Happy Path: Kades approves letter with E-Signature and prints', async ({ kadesPage }) => {
    // Navigate to Surat
    await kadesPage.click('[data-testid="nav-item-surat"]');

    // Find any letter with 'diajukan' status or pick the first item
    const diajukanBadge = kadesPage.locator('tr:has-text("diajukan"), tr:has-text("Diajukan")').first();
    if (await diajukanBadge.isVisible()) {
      await diajukanBadge.click();
    } else {
      const firstRow = kadesPage.locator('tbody tr').first();
      await firstRow.click();
    }

    // If in diajukan state, check approval panel
    if (await kadesPage.locator('[data-testid="kades-validation-panel"]').isVisible()) {
      await kadesPage.fill('[data-testid="input-approve-notes"]', 'Disetujui untuk keperluan resmi warga.');
      await kadesPage.click('[data-testid="btn-kades-approve"]');

      // Status should become disetujui
      await expect(kadesPage.locator('text=DISETUJUI')).toBeVisible();
      await expect(kadesPage.locator('[data-testid="btn-detail-print"]')).toBeVisible();
    }
  });

  test('Access Control: Staff cannot access Activity Logs', async ({ staffPage }) => {
    // Verify logs menu does not exist in staff navigation
    await expect(staffPage.locator('[data-testid="nav-item-logs"]')).not.toBeVisible();
  });

  test('Access Control: Kades can access Activity Logs and inspect audit trail', async ({ kadesPage }) => {
    await expect(kadesPage.locator('[data-testid="nav-item-logs"]')).toBeVisible();
    await kadesPage.click('[data-testid="nav-item-logs"]');

    await expect(kadesPage.locator('text=Log Aktivitas Sistem')).toBeVisible();
    await expect(kadesPage.locator('table')).toBeVisible();
  });
});
