import { test, expect } from './fixtures/auth.fixture';

test.describe('Core Actions (Resident Management & Letter Creation)', () => {
  test('Happy Path: Add new resident and create letter with auto-fill', async ({ staffPage }) => {
    // 1. Navigate to Warga Management
    await staffPage.click('[data-testid="nav-item-warga"]');

    // Click tambah warga
    const tambahWargaBtn = staffPage.locator('button:has-text("Tambah Data Warga"), button:has-text("Tambah Warga")').first();
    await tambahWargaBtn.click();

    const timestamp = Date.now().toString().slice(-6);
    const testNik = `1207012501${timestamp}`;

    // Fill form
    await staffPage.fill('[data-testid="input-warga-nik"]', testNik);
    await staffPage.fill('[data-testid="input-warga-kk"]', `1207019901${timestamp}`);
    await staffPage.fill('[data-testid="input-warga-nama"]', 'Hendro Wicaksono E2E');
    await staffPage.click('[data-testid="btn-save-warga"]');

    // 2. Navigate to Buat Surat
    await staffPage.click('[data-testid="nav-item-surat"]');

    // Click Buat Surat Baru
    const buatSuratBtn = staffPage.locator('button:has-text("Buat Permohonan Baru"), button:has-text("Buat Surat")').first();
    await buatSuratBtn.click();

    // Type the NIK and observe auto-fill
    await staffPage.fill('[data-testid="input-surat-nik"]', testNik);
    await expect(staffPage.locator('[data-testid="badge-autofill-success"]')).toBeVisible();

    // Save as Draft
    await staffPage.click('[data-testid="btn-surat-draft"]');

    // Should redirect to detail view
    await expect(staffPage.locator('[data-testid="btn-detail-ajukan"]')).toBeVisible();
    await expect(staffPage.locator('text=DRAFT')).toBeVisible();

    // Submit to Kades
    await staffPage.click('[data-testid="btn-detail-ajukan"]');
    await expect(staffPage.locator('text=DIAJUKAN')).toBeVisible();
  });

  test('Failure State: Validation fails when NIK is invalid (less than 16 digits)', async ({ staffPage }) => {
    await staffPage.click('[data-testid="nav-item-warga"]');
    const tambahWargaBtn = staffPage.locator('button:has-text("Tambah Data Warga"), button:has-text("Tambah Warga")').first();
    await tambahWargaBtn.click();

    await staffPage.fill('[data-testid="input-warga-nik"]', '12070123'); // only 8 digits
    await staffPage.fill('[data-testid="input-warga-nama"]', 'Testing Invalid NIK');
    await staffPage.click('[data-testid="btn-save-warga"]');

    await expect(staffPage.locator('[data-testid="error-warga-nik"]')).toBeVisible();
    await expect(staffPage.locator('[data-testid="error-warga-nik"]')).toContainText('16 digit');
  });
});
