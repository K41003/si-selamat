import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import {
  LogOut,
  UserCheck,
  Edit3,
  Check,
  PanelLeft,
  PanelLeftClose,
  Menu,
  ShieldCheck,
  Bell,
  Search
} from 'lucide-react';

interface HeaderProps {
  onToggleSidebar: () => void;
  isSidebarCollapsed?: boolean;
}

export const Header: React.FC<HeaderProps> = ({ onToggleSidebar, isSidebarCollapsed }) => {
  const { user, logout, updateStaffName } = useAuth();
  const [editingStaffName, setEditingStaffName] = useState(false);
  const [tempStaffName, setTempStaffName] = useState(user?.active_staff_name || 'Amanda Rahmawati');

  if (!user) return null;

  const isStaff = user.role === 'staff';
  const isKades = user.role === 'kades';

  const handleSaveStaffName = (e: React.FormEvent) => {
    e.preventDefault();
    if (tempStaffName.trim()) {
      updateStaffName(tempStaffName.trim());
      setEditingStaffName(false);
    }
  };

  return (
    <header className="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-slate-200 bg-white/95 px-4 sm:px-6 lg:px-8 backdrop-blur-md">
      {/* Left: Sidebar Toggle & Page Identity */}
      <div className="flex items-center gap-3 sm:gap-4">
        <button
          id="btn-toggle-sidebar"
          data-testid="btn-toggle-sidebar"
          onClick={onToggleSidebar}
          aria-label={isSidebarCollapsed ? "Tampilkan Menu Sidebar" : "Sembunyikan Menu Sidebar"}
          title={isSidebarCollapsed ? "Tampilkan Menu Sidebar" : "Sembunyikan Menu Sidebar"}
          className="flex items-center justify-center rounded-xl p-2.5 text-slate-500 hover:bg-slate-100 hover:text-slate-900 border border-slate-200 bg-white transition active:scale-95 shadow-2xs"
        >
          {isSidebarCollapsed ? (
            <PanelLeft className="h-5 w-5 stroke-[1.8] text-slate-500" />
          ) : (
            <PanelLeftClose className="h-5 w-5 stroke-[1.8] text-slate-500 hidden lg:block" />
          )}
          <Menu className="h-5 w-5 stroke-[1.8] text-slate-500 lg:hidden" />
        </button>

        <div className="hidden sm:block">
          <p className="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
            Pemerintah Desa Tanjung Selamat
          </p>
          <h2 className="text-sm sm:text-base font-bold text-slate-900 leading-tight">
            Sistem Informasi Layanan Administrasi &amp; Surat
          </h2>
        </div>
      </div>

      {/* Right: Actions, User Identity Pill & Logout Button */}
      <div className="flex items-center gap-2 sm:gap-3">
        {isStaff ? (
          <div
            data-testid="header-user-info"
            className="flex items-center gap-2.5 rounded-2xl bg-slate-50 px-3 py-1.5 sm:px-4 sm:py-2 border border-slate-200 shadow-2xs"
          >
            <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#1E3A8A]/10 text-[#1E3A8A] border border-[#1E3A8A]/20">
              <UserCheck className="h-4.5 w-4.5 stroke-[1.8]" />
            </div>

            {editingStaffName ? (
              <form onSubmit={handleSaveStaffName} className="flex items-center gap-1.5">
                <input
                  type="text"
                  data-testid="input-header-staff-name"
                  value={tempStaffName}
                  onChange={e => setTempStaffName(e.target.value)}
                  placeholder="Nama petugas shift..."
                  className="rounded-lg border border-[#1E3A8A] px-2.5 py-1 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] bg-white"
                  autoFocus
                />
                <button
                  type="submit"
                  data-testid="btn-save-header-staff-name"
                  className="rounded-lg bg-[#1E3A8A] px-2.5 py-1 text-xs font-semibold text-white hover:bg-[#172554] transition"
                  title="Simpan Nama Petugas"
                >
                  <Check className="h-3.5 w-3.5 stroke-[2]" />
                </button>
              </form>
            ) : (
              <div className="flex items-center gap-1.5">
                <div className="text-left">
                  <div className="flex items-center gap-1.5">
                    <span
                      data-testid="text-header-staff-name"
                      className="text-xs sm:text-sm font-semibold text-slate-900 block max-w-[120px] sm:max-w-[160px] truncate"
                    >
                      {user.active_staff_name || 'Petugas Staff'}
                    </span>
                    <button
                      type="button"
                      data-testid="btn-edit-header-staff-name"
                      onClick={() => {
                        setTempStaffName(user.active_staff_name || '');
                        setEditingStaffName(true);
                      }}
                      className="rounded p-1 text-slate-400 hover:text-[#1E3A8A] hover:bg-slate-200/60 transition"
                      title="Ganti nama petugas shift aktif"
                    >
                      <Edit3 className="h-3.5 w-3.5 stroke-[1.8]" />
                    </button>
                  </div>
                  <span className="text-[10px] text-slate-500 font-medium block">
                    Petugas Shift Pelayanan
                  </span>
                </div>
              </div>
            )}
          </div>
        ) : (
          /* Kades Profile Pill - Clean White/Light Theme */
          <div
            data-testid="header-user-info"
            className="flex items-center gap-2.5 rounded-2xl bg-slate-50 px-3.5 py-2 border border-slate-200 shadow-2xs"
          >
            <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#1E3A8A]/10 text-[#1E3A8A] border border-[#1E3A8A]/20">
              <ShieldCheck className="h-4.5 w-4.5 stroke-[1.8]" />
            </div>
            <div className="text-left">
              <p
                data-testid="text-header-kades-name"
                className="text-xs sm:text-sm font-semibold leading-tight text-slate-900 max-w-[130px] sm:max-w-[180px] truncate"
              >
                {user.name}
              </p>
              <p className="text-[10px] text-slate-500 font-medium leading-tight mt-0.5">
                Kepala Desa Tanjung Selamat
              </p>
            </div>
          </div>
        )}

        {/* Clean Logout Button */}
        <button
          id="btn-logout"
          data-testid="btn-logout"
          onClick={logout}
          className="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition active:scale-95 shadow-2xs"
          title="Keluar dari sistem aplikasi"
        >
          <LogOut className="h-4 w-4 stroke-[1.8] text-slate-400" />
          <span className="hidden sm:inline">Keluar</span>
        </button>
      </div>
    </header>
  );
};
