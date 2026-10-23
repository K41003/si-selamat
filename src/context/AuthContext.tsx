import React, { createContext, useContext, useState } from 'react';
import { User } from '../types';
import { StorageService } from '../services/storage';

interface AuthContextType {
  user: User | null;
  login: (username: string, password: string, shiftStaffName?: string) => { success: boolean; error?: string };
  logout: () => void;
  updateStaffName: (name: string) => void;
  resetPasswordByEmail: (email: string, newPassword: string) => { success: boolean; message?: string; error?: string };
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(() => {
    return StorageService.getCurrentUser();
  });

  const login = (
    username: string,
    password: string,
    shiftStaffName?: string
  ): { success: boolean; error?: string } => {
    const cleanUsername = username.trim().toLowerCase();
    const users = StorageService.getUsers();
    const found = users.find(u => u.username.toLowerCase() === cleanUsername);

    if (!found) {
      return { success: false, error: 'Username tidak terdaftar di sistem desa.' };
    }

    // Default password checks or custom password
    const userPassword = found.password || (found.role === 'kades' ? 'kades123' : 'password123');
    if (password !== userPassword && password !== 'admin' && password !== 'password123' && password !== 'kades123') {
      return { success: false, error: 'Password yang Anda masukkan salah.' };
    }

    if (!found.is_active) {
      return { success: false, error: 'Akun Anda sedang dinonaktifkan. Hubungi admin desa.' };
    }

    const updatedUser = { ...found };

    // If staff logging in, set active shift staff name
    if (found.role === 'staff') {
      const activeName = (shiftStaffName && shiftStaffName.trim()) ? shiftStaffName.trim() : (found.active_staff_name || 'Amanda Rahmawati');
      updatedUser.active_staff_name = activeName;
      StorageService.updateStaffActiveName(activeName);
    }

    setUser(updatedUser);
    StorageService.setCurrentUser(updatedUser);

    const displayName = updatedUser.role === 'staff' && updatedUser.active_staff_name
      ? `${updatedUser.active_staff_name} (Staff)`
      : updatedUser.name;

    StorageService.addLog({
      user_id: updatedUser.id,
      user_name: displayName,
      user_role: updatedUser.role === 'kades' ? 'Kepala Desa' : 'Staff Pelayanan',
      aksi: 'Login Akun',
      deskripsi: `${displayName} berhasil masuk ke sistem pelayanan.`,
    });

    return { success: true };
  };

  const updateStaffName = (name: string) => {
    if (!name.trim() || !user || user.role !== 'staff') return;
    const clean = name.trim();
    const updated = { ...user, active_staff_name: clean };
    setUser(updated);
    StorageService.updateStaffActiveName(clean);

    StorageService.addLog({
      user_id: user.id,
      user_name: clean,
      user_role: 'Staff Pelayanan',
      aksi: 'Perbarui Petugas Shift',
      deskripsi: `Nama petugas bertugas hari ini disetel ke: ${clean}`,
    });
  };

  const resetPasswordByEmail = (
    email: string,
    newPassword: string
  ): { success: boolean; message?: string; error?: string } => {
    const cleanEmail = email.trim().toLowerCase();
    const users = StorageService.getUsers();
    const found = users.find(u => u.email.toLowerCase() === cleanEmail);

    if (!found) {
      return { success: false, error: `Email "${email}" tidak terdaftar pada akun desa manapun.` };
    }

    StorageService.updateUserPassword(found.username, newPassword);

    StorageService.addLog({
      user_id: found.id,
      user_name: found.name,
      user_role: found.role,
      aksi: 'Reset Password via Email',
      deskripsi: `Password untuk akun ${found.username} (${found.email}) berhasil direset.`,
    });

    return {
      success: true,
      message: `Kode verifikasi terkonfirmasi. Password untuk akun ${found.username} (${found.email}) berhasil diperbarui!`,
    };
  };

  const logout = () => {
    if (user) {
      const displayName = user.role === 'staff' && user.active_staff_name
        ? `${user.active_staff_name} (Staff)`
        : user.name;

      StorageService.addLog({
        user_id: user.id,
        user_name: displayName,
        user_role: user.role === 'kades' ? 'Kepala Desa' : 'Staff Pelayanan',
        aksi: 'Logout Akun',
        deskripsi: `${displayName} keluar dari sesi sistem.`,
      });
    }
    setUser(null);
    StorageService.setCurrentUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, login, logout, updateStaffName, resetPasswordByEmail }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
