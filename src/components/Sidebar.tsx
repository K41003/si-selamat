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
  UserCheck
} from 'lucide-react';

interface SidebarProps {
  currentView: string;
  onNavigate: (view: string) => void;
  isOpen: boolean;
  onClose: () => void;
}

export const Sidebar: React.FC<SidebarProps> = ({ currentView, onNavigate, isOpen, onClose }) => {
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
      badgeColor: 'bg-amber-500 text-white',
    },
    {
      id: 'validasi',
      label: 'Validasi Surat (Kades)',
      icon: CheckCircle2,
      roles: ['kades'],
      badge: pendingCount > 0 ? pendingCount : undefined,
      badgeColor: 'bg-blue-500 text-white',
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
      roles: ['kades'], // Restricted to Kades only
    },
  ];

  const filteredItems = navItems.filter(item => item.roles.includes(user.role));

  return (
    <>
      {/* Mobile Backdrop */}
      {isOpen && (
        <div
          className="fixed inset-0 z-30 bg-slate-900/60 backdrop-blur-xs lg:hidden"
          onClick={onClose}
        />
      )}

      <aside
        id="app-sidebar"
        className={`fixed inset-y-0 left-0 z-40 flex w-64 flex-col bg-slate-900 text-white transition-transform duration-200 ease-in-out lg:static lg:translate-x-0 ${
          isOpen ? 'translate-x-0' : '-translate-x-full'
        }`}
      >
        <div className="flex h-16 items-center justify-between border-b border-slate-800 px-6">
          <div className="flex items-center gap-2">
            <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600 text-white font-black text-sm">
              S
            </div>
            <div>
              <span className="text-base font-bold tracking-tight text-white">SI SELAMAT</span>
              <span className="block text-[10px] text-slate-400 font-medium">Tanjung Selamat</span>
            </div>
          </div>
          <button
            onClick={onClose}
            className="rounded-lg p-1 text-slate-400 hover:bg-slate-800 hover:text-white lg:hidden"
          >
            <X className="h-5 w-5" />
          </button>
        </div>

        {/* User Role Tag */}
        <div className="mx-4 mt-4 rounded-xl bg-slate-800/80 p-3 border border-slate-700/50">
          <div className="flex items-center gap-2.5">
            <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600/20 text-emerald-400">
              {isKades ? <ShieldCheck className="h-4 w-4" /> : <UserCheck className="h-4 w-4" />}
            </div>
            <div className="min-w-0">
              <p className="text-xs font-bold text-slate-100 truncate">
                {isStaff && user.active_staff_name ? user.active_staff_name : user.name}
              </p>
              <p className="text-[10px] font-semibold text-emerald-400 uppercase tracking-wider">
                {isKades ? 'Kepala Desa' : 'Staff Pelayanan'}
              </p>
            </div>
          </div>
        </div>

        <nav className="mt-4 flex-1 space-y-1 px-3">
          {filteredItems.map(item => {
            const Icon = item.icon;
            const isActive =
              currentView === item.id ||
              (item.id === 'warga' && currentView.startsWith('warga')) ||
              (item.id === 'surat' && (currentView === 'surat' || currentView === 'surat_create' || currentView.startsWith('surat_create_for_'))) ||
              (item.id === 'surat_drafts' && (currentView === 'surat_drafts' || currentView.startsWith('surat_edit_')));

            return (
              <button
                key={item.id}
                id={`nav-${item.id}`}
                data-testid={`nav-item-${item.id}`}
                onClick={() => {
                  onNavigate(item.id);
                  onClose();
                }}
                className={`flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition ${
                  isActive
                    ? 'bg-slate-800 text-white shadow-xs font-semibold'
                    : 'text-slate-300 hover:bg-slate-800/60 hover:text-white'
                }`}
              >
                <div className="flex items-center gap-3">
                  <Icon className={`h-4 w-4 ${isActive ? 'text-emerald-400' : 'text-slate-400'}`} />
                  <span>{item.label}</span>
                </div>
                {item.badge !== undefined && (
                  <span className={`text-[10px] font-bold px-2 py-0.5 rounded-full ${item.badgeColor || 'bg-slate-700 text-white'}`}>
                    {item.badge}
                  </span>
                )}
              </button>
            );
          })}
        </nav>

        {/* Sidebar Footer */}
        <div className="border-t border-slate-800 p-4">
          <p className="text-[11px] text-slate-400 text-center font-medium">
            Desa Tanjung Selamat &copy; {new Date().getFullYear()}
          </p>
        </div>
      </aside>
    </>
  );
};
