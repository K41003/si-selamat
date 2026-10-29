import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import {
  generateSecureOTP,
  checkRateLimit,
  recordFailedAttempt,
  resetRateLimit
} from '../utils';
import {
  Shield,
  AlertCircle,
  Mail,
  UserCheck,
  CheckCircle2,
  ArrowLeft,
  Eye,
  EyeOff
} from 'lucide-react';

export const LoginView: React.FC = () => {
  const { login, resetPasswordByEmail } = useAuth();

  // Login form state
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [staffName, setStaffName] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [error, setError] = useState<string | null>(null);

  // Forgot password flow state
  const [showForgotPassword, setShowForgotPassword] = useState(false);
  const [forgotStep, setForgotStep] = useState<1 | 2 | 3>(1); // 1: Input Email, 2: Input OTP, 3: Set New Password
  const [resetEmail, setResetEmail] = useState('');
  const [verificationCode, setVerificationCode] = useState('');
  const [simulatedSentCode, setSimulatedSentCode] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [showNewPassword, setShowNewPassword] = useState(false);
  const [forgotError, setForgotError] = useState<string | null>(null);
  const [forgotSuccess, setForgotSuccess] = useState<string | null>(null);

  const isStaffAccount = username.trim().toLowerCase() === 'staff';

  const handleLoginSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);

    const cleanUsername = username.trim().toLowerCase();
    if (!cleanUsername) {
      setError('Harap masukkan username Anda.');
      return;
    }
    if (!password) {
      setError('Harap masukkan password Anda.');
      return;
    }

    if (isStaffAccount && !staffName.trim()) {
      setError('Harap masukkan nama petugas staff yang bertugas/shift hari ini.');
      return;
    }

    // Rate Limit Check
    const rateCheck = checkRateLimit(`login_${cleanUsername}`, 5, 15 * 60 * 1000);
    if (!rateCheck.allowed) {
      setError(`Terlalu banyak percobaan login gagal. Akun dikunci sementara. Coba lagi dalam ${rateCheck.lockedMinutes || 15} menit.`);
      return;
    }

    const result = login(username, password, isStaffAccount ? staffName.trim() : undefined);
    if (!result.success) {
      const failInfo = recordFailedAttempt(`login_${cleanUsername}`, 5, 15 * 60 * 1000);
      if (failInfo.isLocked) {
        setError(`Terlalu banyak percobaan login salah. Akun Anda dikunci selama ${failInfo.lockedMinutes} menit.`);
      } else {
        const warning = failInfo.remainingAttempts <= 2 ? ` (Sisa ${failInfo.remainingAttempts} kesempatan)` : '';
        setError((result.error || 'Password atau username salah.') + warning);
      }
    } else {
      resetRateLimit(`login_${cleanUsername}`);
    }
  };

  // Step 1: Send Verification Code to Email
  const handleSendVerificationCode = (e: React.FormEvent) => {
    e.preventDefault();
    setForgotError(null);

    const email = resetEmail.trim().toLowerCase();
    if (!email) {
      setForgotError('Harap masukkan alamat email.');
      return;
    }

    // Rate limit check for OTP request
    const rateCheck = checkRateLimit(`otp_req_${email}`, 3, 10 * 60 * 1000);
    if (!rateCheck.allowed) {
      setForgotError(`Terlalu banyak permintaan kode verifikasi. Harap tunggu ${rateCheck.lockedMinutes || 10} menit.`);
      return;
    }
    recordFailedAttempt(`otp_req_${email}`, 3, 10 * 60 * 1000);

    // Cryptographically secure 6-digit verification code
    const generatedCode = generateSecureOTP();
    setSimulatedSentCode(generatedCode);
    setForgotStep(2);
    setForgotSuccess(`Kode verifikasi 6-digit berhasil dikirimkan ke email ${email}.`);
  };

  // Step 2: Verify the 6-digit code
  const handleVerifyCode = (e: React.FormEvent) => {
    e.preventDefault();
    setForgotError(null);

    const email = resetEmail.trim().toLowerCase();
    const rateCheck = checkRateLimit(`otp_verify_${email}`, 5, 15 * 60 * 1000);
    if (!rateCheck.allowed) {
      setForgotError(`Terlalu banyak percobaan kode verifikasi salah. Diblokir sementara selama ${rateCheck.lockedMinutes || 15} menit.`);
      return;
    }

    // Strictly compare against the generated code only (Backdoor removed)
    if (verificationCode.trim() !== simulatedSentCode) {
      const failInfo = recordFailedAttempt(`otp_verify_${email}`, 5, 15 * 60 * 1000);
      if (failInfo.isLocked) {
        setForgotError(`Terlalu banyak percobaan kode salah. Percobaan diblokir selama ${failInfo.lockedMinutes} menit.`);
      } else {
        const warning = failInfo.remainingAttempts <= 2 ? ` (Sisa ${failInfo.remainingAttempts} kesempatan)` : '';
        setForgotError(`Kode verifikasi yang Anda masukkan tidak cocok atau kedaluwarsa.${warning}`);
      }
      return;
    }

    resetRateLimit(`otp_verify_${email}`);
    resetRateLimit(`otp_req_${email}`);
    setForgotSuccess(null);
    setForgotStep(3);
  };

  // Step 3: Change and save the new password
  const handleResetPasswordSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setForgotError(null);

    if (!newPassword || newPassword.length < 5) {
      setForgotError('Password baru minimal harus 5 karakter.');
      return;
    }

    if (newPassword !== confirmPassword) {
      setForgotError('Konfirmasi password tidak cocok dengan password baru.');
      return;
    }

    const result = resetPasswordByEmail(resetEmail, newPassword);
    if (result.success) {
      // Clear any login rate limits for this user
      const users = resetEmail.trim().toLowerCase();
      resetRateLimit(`login_${users}`);
      setForgotSuccess(result.message || 'Password berhasil diperbarui.');
      setTimeout(() => {
        setShowForgotPassword(false);
        setForgotStep(1);
        setPassword(newPassword);
        setError(null);
      }, 1500);
    } else {
      setForgotError(result.error || 'Gagal mereset password.');
    }
  };

  return (
    <div className="flex min-h-screen items-center justify-center bg-slate-100 px-4 py-8">
      <div className="w-full max-w-md">
        <div className="mb-6 text-center">
          <div className="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-md">
            <Shield className="h-7 w-7 text-emerald-400" />
          </div>
          <h1 className="text-2xl font-bold tracking-tight text-slate-900">SI SELAMAT</h1>
          <p className="mt-1 text-xs text-slate-500 max-w-xs mx-auto leading-relaxed">
            Sistem Informasi Surat Elektronik &amp; Administrasi Desa Tanjung Selamat
          </p>
        </div>

        <div className="rounded-2xl bg-white p-7 shadow-sm ring-1 ring-slate-200">
          {!showForgotPassword ? (
            <>
              <div className="mb-5 border-b border-slate-100 pb-3">
                <h2 className="text-base font-bold text-slate-900">Masuk Akun Pengguna</h2>
                <p className="text-xs text-slate-500 mt-0.5">
                  Masukkan username dan password resmi Anda untuk mengakses sistem.
                </p>
              </div>

              {error && (
                <div
                  id="login-error-alert"
                  data-testid="login-error-alert"
                  className="mb-4 flex items-center gap-2 rounded-lg bg-red-50 p-3 text-xs font-medium text-red-700 ring-1 ring-red-200"
                >
                  <AlertCircle className="h-4 w-4 shrink-0" />
                  <span>{error}</span>
                </div>
              )}

              <form onSubmit={handleLoginSubmit} className="space-y-4" data-testid="form-login">
                <div>
                  <label htmlFor="username" className="mb-1 block text-xs font-semibold text-slate-700">
                    Username
                  </label>
                  <input
                    type="text"
                    id="username"
                    data-testid="input-username"
                    value={username}
                    onChange={e => {
                      setUsername(e.target.value);
                      setError(null);
                    }}
                    required
                    placeholder="Masukkan username (contoh: staff / kades)"
                    className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800 transition"
                  />
                </div>

                {/* Inisialisasi Nama Petugas Khusus Akun Staff */}
                {isStaffAccount && (
                  <div className="rounded-xl bg-emerald-50/80 p-3.5 ring-1 ring-emerald-200" data-testid="section-staff-shift">
                    <label htmlFor="staff-name" className="mb-1 flex items-center gap-1.5 text-xs font-bold text-emerald-950">
                      <UserCheck className="h-4 w-4 text-emerald-700" />
                      Inisialisasi Petugas Shift Hari Ini
                    </label>
                    <p className="text-[11px] text-emerald-800 mb-2 leading-relaxed">
                      Sebutkan nama staff yang sedang bertugas (misal: <strong>Amanda</strong>) agar tercatat rapi pada Log Aktivitas Kades.
                    </p>
                    <input
                      type="text"
                      id="staff-name"
                      data-testid="input-staff-name"
                      value={staffName}
                      onChange={e => setStaffName(e.target.value)}
                      required
                      placeholder="Contoh: Amanda Rahmawati / Budi / Siti"
                      className="w-full rounded-lg border border-emerald-300 bg-white px-3 py-2 text-sm font-medium text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                    />
                  </div>
                )}

                <div>
                  <div className="flex items-center justify-between mb-1">
                    <label htmlFor="password" className="text-xs font-semibold text-slate-700">
                      Password
                    </label>
                    <button
                      type="button"
                      data-testid="btn-forgot-password"
                      onClick={() => {
                        setShowForgotPassword(true);
                        setForgotStep(1);
                        setForgotError(null);
                        setForgotSuccess(null);
                        setResetEmail(username === 'kades' ? 'kades@tanjungselamat.desa.id' : 'staff@tanjungselamat.desa.id');
                      }}
                      className="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline"
                    >
                      Lupa Password?
                    </button>
                  </div>
                  <div className="relative">
                    <input
                      type={showPassword ? 'text' : 'password'}
                      id="password"
                      data-testid="input-password"
                      value={password}
                      onChange={e => {
                        setPassword(e.target.value);
                        setError(null);
                      }}
                      required
                      placeholder="Masukkan password"
                      className="w-full rounded-lg border border-slate-300 px-3 py-2 pr-10 text-sm focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800 transition"
                    />
                    <button
                      type="button"
                      data-testid="btn-toggle-password"
                      onClick={() => setShowPassword(!showPassword)}
                      className="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600"
                    >
                      {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                    </button>
                  </div>
                </div>

                <button
                  id="btn-submit-login"
                  data-testid="btn-login-submit"
                  type="submit"
                  className="w-full rounded-lg bg-slate-900 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 shadow-xs mt-2"
                >
                  Masuk ke Aplikasi
                </button>
              </form>
            </>
          ) : (
            /* Forgot Password Flow with Email Verification */
            <div>
              <div className="mb-4 flex items-center gap-2">
                <button
                  type="button"
                  onClick={() => setShowForgotPassword(false)}
                  className="rounded-lg border border-slate-200 p-1.5 text-slate-600 hover:bg-slate-50"
                  title="Kembali ke form login"
                >
                  <ArrowLeft className="h-4 w-4" />
                </button>
                <div>
                  <h2 className="text-base font-bold text-slate-900">Pemulihan Password</h2>
                  <p className="text-xs text-slate-500">Verifikasi via email resmi desa</p>
                </div>
              </div>

              {forgotError && (
                <div className="mb-4 flex items-center gap-2 rounded-lg bg-red-50 p-3 text-xs font-medium text-red-700 ring-1 ring-red-200">
                  <AlertCircle className="h-4 w-4 shrink-0" />
                  <span>{forgotError}</span>
                </div>
              )}

              {forgotSuccess && (
                <div className="mb-4 flex items-start gap-2 rounded-lg bg-emerald-50 p-3 text-xs font-medium text-emerald-800 ring-1 ring-emerald-200">
                  <CheckCircle2 className="h-4 w-4 shrink-0 mt-0.5" />
                  <span>{forgotSuccess}</span>
                </div>
              )}

              {/* STEP 1: Input Email */}
              {forgotStep === 1 && (
                <form onSubmit={handleSendVerificationCode} className="space-y-4" data-testid="form-forgot-step-1">
                  <div>
                    <label htmlFor="reset-email" className="mb-1 block text-xs font-semibold text-slate-700">
                      Alamat Email Terdaftar
                    </label>
                    <div className="relative">
                      <input
                        type="email"
                        id="reset-email"
                        data-testid="input-reset-email"
                        value={resetEmail}
                        onChange={e => setResetEmail(e.target.value)}
                        required
                        placeholder="contoh: staff@tanjungselamat.desa.id"
                        className="w-full rounded-lg border border-slate-300 px-3 py-2 pl-9 text-sm focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800"
                      />
                      <Mail className="absolute left-3 top-2.5 h-4 w-4 text-slate-400" />
                    </div>
                    <p className="mt-1.5 text-[11px] text-slate-500">
                      Sistem akan mengirimkan 6 digit kode OTP verifikasi ke inbox email Anda.
                    </p>
                  </div>

                  <button
                    type="submit"
                    data-testid="btn-send-otp"
                    className="w-full rounded-lg bg-blue-600 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 shadow-xs"
                  >
                    Kirim Kode Verifikasi Email
                  </button>
                </form>
              )}

              {/* STEP 2: Input Verification Code */}
              {forgotStep === 2 && (
                <form onSubmit={handleVerifyCode} className="space-y-4" data-testid="form-forgot-step-2">
                  {/* Simulated Email Notification Banner */}
                  <div className="rounded-xl border border-blue-200 bg-blue-50/70 p-3.5 text-xs text-blue-900">
                    <div className="flex items-center gap-1.5 font-bold mb-1">
                      <Mail className="h-4 w-4 text-blue-600" />
                      <span>Notifikasi Email Simulasi:</span>
                    </div>
                    <p className="text-[11px] text-blue-800">
                      Subjek: <strong>Kode Verifikasi Reset Password Desa</strong>
                    </p>
                    <p className="mt-1 text-[11px] text-blue-800">
                      Kode OTP Anda adalah: <strong data-testid="text-simulated-otp" className="font-mono text-sm tracking-widest text-blue-950 bg-white px-2 py-0.5 rounded border border-blue-200">{simulatedSentCode}</strong>
                    </p>
                  </div>

                  <div>
                    <label htmlFor="otp-code" className="mb-1 block text-xs font-semibold text-slate-700">
                      Masukkan 6 Digit Kode Verifikasi
                    </label>
                    <input
                      type="text"
                      id="otp-code"
                      data-testid="input-otp-code"
                      maxLength={6}
                      value={verificationCode}
                      onChange={e => setVerificationCode(e.target.value.replace(/\D/g, ''))}
                      required
                      placeholder="Contoh: 123456"
                      className="w-full text-center tracking-widest font-mono font-bold text-lg rounded-lg border border-slate-300 px-3 py-2 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600"
                    />
                  </div>

                  <button
                    type="submit"
                    data-testid="btn-verify-otp"
                    className="w-full rounded-lg bg-blue-600 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 shadow-xs"
                  >
                    Verifikasi Kode
                  </button>

                  <div className="text-center">
                    <button
                      type="button"
                      onClick={() => setForgotStep(1)}
                      className="text-xs text-slate-500 hover:text-slate-800"
                    >
                      Ubah alamat email
                    </button>
                  </div>
                </form>
              )}

              {/* STEP 3: Create New Password */}
              {forgotStep === 3 && (
                <form onSubmit={handleResetPasswordSubmit} className="space-y-4" data-testid="form-forgot-step-3">
                  <div>
                    <label htmlFor="new-pass" className="mb-1 block text-xs font-semibold text-slate-700">
                      Password Baru
                    </label>
                    <div className="relative">
                      <input
                        type={showNewPassword ? 'text' : 'password'}
                        id="new-pass"
                        data-testid="input-new-password"
                        value={newPassword}
                        onChange={e => setNewPassword(e.target.value)}
                        required
                        placeholder="Minimal 5 karakter"
                        className="w-full rounded-lg border border-slate-300 px-3 py-2 pr-10 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                      />
                      <button
                        type="button"
                        onClick={() => setShowNewPassword(!showNewPassword)}
                        className="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600"
                      >
                        {showNewPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                      </button>
                    </div>
                  </div>

                  <div>
                    <label htmlFor="confirm-pass" className="mb-1 block text-xs font-semibold text-slate-700">
                      Ulangi Password Baru
                    </label>
                    <input
                      type={showNewPassword ? 'text' : 'password'}
                      id="confirm-pass"
                      data-testid="input-confirm-password"
                      value={confirmPassword}
                      onChange={e => setConfirmPassword(e.target.value)}
                      required
                      placeholder="Ketik ulang password baru"
                      className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                    />
                  </div>

                  <button
                    type="submit"
                    data-testid="btn-save-new-password"
                    className="w-full rounded-lg bg-emerald-600 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 shadow-xs"
                  >
                    Simpan Password Baru &amp; Masuk
                  </button>
                </form>
              )}
            </div>
          )}
        </div>

        <p className="mt-6 text-center text-xs text-slate-400">
          &copy; {new Date().getFullYear()} Pemerintah Desa Tanjung Selamat
        </p>
      </div>
    </div>
  );
};
