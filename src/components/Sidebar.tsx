import React from 'react';
import { useAuth } from '../context/AuthContext';
import { StorageService } from '../services/storage';
import {
  LayoutDashboard,
  Users,
  FileText,
  FileEdit,
  CheckCircle2,
  History,
  X,
  ShieldCheck,
  UserCheck,
  PanelLeftClose,
  ChevronRight
} from 'lucide-react';

interface SidebarProps {
  currentView: string;
  onNavigate: (view: string) => void;
  isOpenMobile: boolean;
  onCloseMobile: () => void;
  isCollapsedDesktop: boolean;
  onToggleDesktop: () => void;
}

export const Sidebar: React.FC<SidebarProps> = ({
  currentView,
  onNavigate,
  isOpenMobile,
  onCloseMobile,
  isCollapsedDesktop,
  onToggleDesktop
}) => {
  const { user } = useAuth();
  const suratList = StorageService.getSuratList();
  const draftCount = suratList.filter(s => s.status === 'draft').length;
  const pendingCount = suratList.filter(s => s.status === 'diajukan').length;

  if (!user) return null;

  const isStaff = user.role === 'staff';
  const isKades = user.role === 'kades';

  const navItems = [
    {
      id: 'dashboard',
      label: 'Dashboard',
      icon: LayoutDashboard,
      roles: ['staff', 'kades'],
    },
    {
      id: 'warga',
      label: 'Kelola Data Warga',
      icon: Users,
      roles: ['staff'],
    },
    {
      id: 'surat',
      label: 'Permohonan Surat',
      icon: FileText,
      roles: ['staff'],
    },
    {
      id: 'surat_drafts',
      label: 'Daftar Draft Surat',
      icon: FileEdit,
      roles: ['staff'],
      badge: draftCount > 0 ? draftCount : undefined,
    },
    {
      id: 'validasi',
      label: 'Validasi Surat (Kades)',
      icon: CheckCircle2,
      roles: ['kades'],
      badge: pendingCount > 0 ? pendingCount : undefined,
    },
    {
      id: 'arsip',
      label: 'Arsip & Cetak Surat',
      icon: FileText,
      roles: ['staff', 'kades'],
    },
    {
      id: 'logs',
      label: 'Log Aktivitas Sistem',
      icon: History,
      roles: ['kades'],
    },
  ];

  const filteredItems = navItems.filter(item => item.roles.includes(user.role));

  return (
    <>
      {/* Mobile Backdrop Overlay */}
      {isOpenMobile && (
        <div
          className="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-xs transition-opacity duration-300 lg:hidden"
          onClick={onCloseMobile}
          aria-hidden="true"
        />
      )}

      {/* Main Sidebar Container */}
      <aside
        id="app-sidebar"
        className={`fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-white border-r border-slate-200 text-slate-900 transition-all duration-300 ease-in-out lg:static ${
          // Mobile visibility
          isOpenMobile ? 'translate-x-0 shadow-2xl' : '-translate-x-full lg:translate-x-0'
        } ${
          // Desktop collapse visibility
          isCollapsedDesktop ? 'lg:hidden' : 'lg:flex lg:w-72'
        }`}
      >
        {/* Brand Header */}
        <div className="flex h-20 items-center justify-between px-6 border-b border-slate-200">
          <div className="flex items-center gap-3">
            <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#1E3A8A] text-white font-extrabold text-lg shadow-sm tracking-tight">
              TS
            </div>
            <div>
              <span className="text-base font-bold tracking-tight text-slate-900 block leading-tight">
                SI-SELAMAT
              </span>
              <span className="text-xs text-slate-500 font-medium">
                Desa Tanjung Selamat
              </span>
            </div>
          </div>

          {/* Close on Mobile */}
          <button
            onClick={onCloseMobile}
            aria-label="Tutup Menu"
            className="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition lg:hidden"
          >
            <X className="h-5 w-5 stroke-[1.8]" />
          </button>

          {/* Collapse on Desktop */}
          <button
            onClick={onToggleDesktop}
            aria-label="Sembunyikan Sidebar"
            title="Sembunyikan Menu Sidebar"
            className="hidden lg:flex rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition"
          >
            <PanelLeftClose className="h-5 w-5 stroke-[1.8]" />
          </button>
        </div>

        {/* User Role Card inside Sidebar */}
        <div className="p-4 pb-2">
          <div className="rounded-2xl bg-slate-50 p-3.5 border border-slate-200 flex items-center gap-3">
            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#1E3A8A]/10 text-[#1E3A8A] border border-[#1E3A8A]/20">
              {isKades ? (
                <ShieldCheck className="h-5 w-5 stroke-[1.8]" />
              ) : (
                <UserCheck className="h-5 w-5 stroke-[1.8]" />
              )}
            </div>
            <div className="min-w-0 flex-1">
              <p className="text-xs font-semibold text-slate-900 truncate">
                {isStaff && user.active_staff_name ? user.active_staff_name : user.name}
              </p>
              <div className="flex items-center gap-1.5 mt-0.5">
                <span className="inline-block h-2 w-2 rounded-full bg-emerald-500"></span>
                <span className="text-[11px] font-medium text-slate-500 tracking-wide">
                  {isKades ? 'Kepala Desa' : 'Staff Pelayanan'}
                </span>
              </div>
            </div>
          </div>
        </div>

        {/* Section Heading */}
        <div className="px-6 pt-3 pb-1">
          <p className="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
            Menu Utama
          </p>
        </div>

        {/* Navigation Items */}
        <nav className="flex-1 space-y-1.5 px-4 overflow-y-auto py-2">
          {filteredItems.map(item => {
            const Icon = item.icon;
            const isActive =
              currentView === item.id ||
              (item.id === 'warga' && currentView.startsWith('warga')) ||
              (item.id === 'surat' &&
                (currentView === 'surat' ||
                  currentView === 'surat_create' ||
                  currentView.startsWith('surat_create_for_'))) ||
              (item.id === 'surat_drafts' &&
                (currentView === 'surat_drafts' || currentView.startsWith('surat_edit_')));

            return (
              <button
                key={item.id}
                id={`nav-${item.id}`}
                data-testid={`nav-item-${item.id}`}
                onClick={() => {
                  onNavigate(item.id);
                  onCloseMobile();
                }}
                className={`group flex w-full items-center justify-between rounded-xl px-4 py-2.5 sm:py-3 text-sm font-medium transition-all duration-150 ${
                  isActive
                    ? 'bg-[#1E3A8A] text-white font-semibold shadow-sm'
                    : 'text-slate-800 hover:bg-slate-100 hover:text-slate-900'
                }`}
              >
                <div className="flex items-center gap-3">
                  <Icon
                    className={`h-5 w-5 shrink-0 stroke-[1.8] ${
                      isActive ? 'text-white' : 'text-slate-400 group-hover:text-slate-600'
                    }`}
                  />
                  <span className="tracking-normal">{item.label}</span>
                </div>

                {item.badge !== undefined ? (
                  <span
                    className={`text-xs font-bold px-2 py-0.5 rounded-full ${
                      isActive
                        ? 'bg-white/25 text-white'
                        : 'bg-slate-100 text-slate-700 border border-slate-200'
                    }`}
                  >
                    {item.badge}
                  </span>
                ) : isActive ? (
                  <ChevronRight className="h-4 w-4 text-white/80 shrink-0 stroke-[1.8]" />
                ) : null}
              </button>
            );
          })}
        </nav>

        {/* Sleek Minimalist Footer */}
        <div className="border-t border-slate-200 p-3.5 bg-slate-50/60 text-center">
          <p className="text-[11px] font-medium text-slate-500">
            Desa Tanjung Selamat &bull; Deli Serdang
          </p>
        </div>
      </aside>
    </>
  );
};
