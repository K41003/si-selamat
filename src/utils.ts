/**
 * Security & Data Privacy Utilities for SI SELAMAT
 */

// 1. Cryptographically Secure OTP Generator
export function generateSecureOTP(): string {
  if (typeof window !== 'undefined' && window.crypto && window.crypto.getRandomValues) {
    const array = new Uint32Array(1);
    window.crypto.getRandomValues(array);
    // Return 6-digit string between 100000 and 999999
    return String(100000 + (array[0] % 900000));
  }
  // Fallback if crypto is unavailable
  return String(Math.floor(100000 + Math.random() * 900000));
}

// 2. Rate Limiting Tracker for Login & Password Recovery
interface RateLimitRecord {
  attempts: number;
  lockUntil: number | null;
}

export function checkRateLimit(
  key: string,
  maxAttempts = 5,
  lockDurationMs = 15 * 60 * 1000
): { allowed: boolean; remainingAttempts?: number; lockedMinutes?: number } {
  try {
    const storageKey = `desa_ratelimit_${key}`;
    const raw = sessionStorage.getItem(storageKey);
    const now = Date.now();
    let record: RateLimitRecord = raw ? JSON.parse(raw) : { attempts: 0, lockUntil: null };

    // If currently locked
    if (record.lockUntil && now < record.lockUntil) {
      const remainingMin = Math.ceil((record.lockUntil - now) / 60000);
      return { allowed: false, lockedMinutes: remainingMin };
    }

    // Lock expired, reset
    if (record.lockUntil && now >= record.lockUntil) {
      record = { attempts: 0, lockUntil: null };
      sessionStorage.setItem(storageKey, JSON.stringify(record));
    }

    return { allowed: true, remainingAttempts: Math.max(0, maxAttempts - record.attempts) };
  } catch {
    return { allowed: true };
  }
}

export function recordFailedAttempt(
  key: string,
  maxAttempts = 5,
  lockDurationMs = 15 * 60 * 1000
): { isLocked: boolean; remainingAttempts: number; lockedMinutes?: number } {
  try {
    const storageKey = `desa_ratelimit_${key}`;
    const raw = sessionStorage.getItem(storageKey);
    const now = Date.now();
    let record: RateLimitRecord = raw ? JSON.parse(raw) : { attempts: 0, lockUntil: null };

    record.attempts += 1;

    if (record.attempts >= maxAttempts) {
      record.lockUntil = now + lockDurationMs;
      sessionStorage.setItem(storageKey, JSON.stringify(record));
      const remainingMin = Math.ceil(lockDurationMs / 60000);
      return { isLocked: true, remainingAttempts: 0, lockedMinutes: remainingMin };
    }

    sessionStorage.setItem(storageKey, JSON.stringify(record));
    return { isLocked: false, remainingAttempts: maxAttempts - record.attempts };
  } catch {
    return { isLocked: false, remainingAttempts: 1 };
  }
}

export function resetRateLimit(key: string): void {
  try {
    sessionStorage.removeItem(`desa_ratelimit_${key}`);
  } catch {
    // Ignore storage issues
  }
}

// 3. PII Masking for NIK & No. KK (e.g. 120701******0008)
export function maskIdentityNumber(val?: string): string {
  if (!val) return '-';
  const clean = val.trim();
  if (clean.length < 10) return clean;
  const prefix = clean.substring(0, 6);
  const suffix = clean.substring(clean.length - 4);
  const maskedLength = Math.max(2, clean.length - 10);
  return `${prefix}${'*'.repeat(maskedLength)}${suffix}`;
}

// 4. Format Tanggal Lahir (Hanya tanggal, tanpa waktu/jam)
export function formatTanggalLahir(val?: string | null): string {
  if (!val) return '-';
  const trimmed = val.trim();
  if (!trimmed || trimmed === '-') return '-';

  // Hilangkan bagian waktu jika ada (T00:00:00 atau jam 00:00:00)
  const dateOnly = trimmed.includes('T') 
    ? trimmed.split('T')[0] 
    : trimmed.split(' ')[0];

  // Jika format YYYY-MM-DD, ubah ke DD-MM-YYYY
  const parts = dateOnly.split('-');
  if (parts.length === 3 && parts[0].length === 4) {
    const [year, month, day] = parts;
    return `${day}-${month}-${year}`;
  }

  return dateOnly;
}

// 5. Format Tempat & Tanggal Lahir (Hanya Tempat dan Tanggal)
export function formatTempatTanggalLahir(tempat?: string | null, tanggal?: string | null): string {
  const t = tempat?.trim() || '-';
  const d = formatTanggalLahir(tanggal);

  if (t === '-' && d === '-') return '-';
  if (t === '-') return d;
  if (d === '-') return t;

  return `${t}, ${d}`;
}
