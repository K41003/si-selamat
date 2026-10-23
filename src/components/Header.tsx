import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import {
  LogOut,
  UserCheck,
  UserCheck2,
  Edit3,
  Check,
  Menu,
  ShieldAlert
} from 'lucide-react';

interface HeaderProps {
  onToggleSidebar: () => void;
}

export const Header: React.FC<HeaderProps> = ({ onToggleSidebar }) => {
  const { user, logout, updateStaffName } = useAuth();
  const [editingStaffName, setEditingStaffName] = useState(false);
  const [tempStaffName, setTempStaffName] = useState(user?.active_staff_name || 'Amanda Rahmawati');

  if (!user) return null;

  const handleSaveStaffName = (e: React.FormEvent) => {
    e.preventDefault();
    if (tempStaffName.trim()) {
      updateStaffName(tempStaffName.trim());
      setEditingStaffName(false);
    }
  };

  return (
    <header className="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 lg:px-6">
      <div className="flex items-center gap-3">
        <button
          id="btn-toggle-sidebar"
          onClick={onToggleSidebar}
          className="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden"
          aria-label="Menu"
        >
          <Menu className="h-5 w-5" />
        </button>
        <div className="hidden sm:block">
          <p className="text-xs font-semibold uppercase tracking-wider text-slate-400">Desa Tanjung Selamat</p>
          <p className="text-sm font-bold text-slate-800">Sistem Pelayanan &amp; Surat Elektronik</p>
        </div>
      </div>

      <div className="flex items-center gap-3">
        {/* If Staff: Show Shift Staff Identity with Quick Change Option */}
        {user.role === 'staff' ? (
          <div data-testid="header-user-info" className="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-1.5 ring-1 ring-slate-200">
            <div className="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
              <UserCheck2 className="h-4 w-4" />
            </div>

            {editingStaffName ? (
              <form onSubmit={handleSaveStaffName} className="flex items-center gap-1">
                <input
                  type="text"
                  data-testid="input-header-staff-name"
                  value={tempStaffName}
                  onChange={e => setTempStaffName(e.target.value)}
                  placeholder="Nama petugas shift..."
                  className="rounded border border-slate-300 px-2 py-0.5 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-700"
                  autoFocus
                />
                <button
                  type="submit"
                  data-testid="btn-save-header-staff-name"
                  className="rounded bg-slate-900 p-1 text-white hover:bg-slate-800"
                  title="Simpan Nama Petugas"
                >
                  <Check className="h-3 w-3" />
                </button>
              </form>
            ) : (
              <div className="flex items-center gap-2">
                <div>
                  <div className="flex items-center gap-1.5">
                    <span data-testid="text-header-staff-name" className="text-xs font-bold text-slate-900">{user.active_staff_name || 'Petugas Staff'}</span>
                    <button
                      type="button"
                      data-testid="btn-edit-header-staff-name"
                      onClick={() => {
                        setTempStaffName(user.active_staff_name || '');
                        setEditingStaffName(true);
                      }}
                      className="text-slate-400 hover:text-slate-700"
                      title="Ganti nama petugas shift hari ini"
                    >
                      <Edit3 className="h-3 w-3" />
                    </button>
                  </div>
                  <span className="text-[10px] text-emerald-700 font-medium block">Petugas Shift Hari Ini</span>
                </div>
              </div>
            )}
          </div>
        ) : (
          /* If Kades: Show Official Identity */
          <div data-testid="header-user-info" className="flex items-center gap-2 rounded-xl bg-slate-900 px-3 py-1.5 text-white">
            <div className="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-500/20 text-emerald-400">
              <ShieldAlert className="h-4 w-4" />
            </div>
            <div>
              <p data-testid="text-header-kades-name" className="text-xs font-bold leading-tight text-white">{user.name}</p>
              <p className="text-[10px] font-medium text-emerald-400 leading-tight">Kepala Desa</p>
            </div>
          </div>
        )}

        {/* Secure Logout Button */}
        <button
          id="btn-logout"
          data-testid="btn-logout"
          onClick={logout}
          className="flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-red-50 hover:text-red-700 hover:border-red-200 transition shadow-xs"
          title="Keluar dari akun"
        >
          <LogOut className="h-3.5 w-3.5" />
          <span className="hidden sm:inline">Keluar</span>
        </button>
      </div>
    </header>
  );
};
