import React, { useState } from 'react';
import { StorageService } from '../services/storage';
import { useAuth } from '../context/AuthContext';
import { History, Shield, RefreshCw, ShieldAlert, User, Search, Filter } from 'lucide-react';
import { ActivityLog } from '../types';

export const ActivityLogView: React.FC = () => {
  const { user } = useAuth();
  const [logs, setLogs] = useState<ActivityLog[]>(() => StorageService.getLogs());
  const [searchQuery, setSearchQuery] = useState('');
  const [roleFilter, setRoleFilter] = useState<'all' | 'staff' | 'kades'>('all');

  const handleRefresh = () => {
    setLogs(StorageService.getLogs());
  };

  // Only Kades can access this view
  if (!user || user.role !== 'kades') {
    return (
      <div className="flex flex-col items-center justify-center rounded-3xl bg-white p-12 text-center shadow-sm border border-slate-200/80">
        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 mb-4 border border-rose-200/60">
          <ShieldAlert className="h-7 w-7 stroke-[1.8]" />
        </div>
        <h2 className="text-lg font-bold text-slate-900">Akses Dibatasi</h2>
        <p className="mt-1 text-sm text-slate-500 max-w-md">
          Halaman Log Aktivitas Sistem hanya dapat diakses oleh akun <strong>Kepala Desa</strong> untuk keperluan audit dan pengawasan operasional pelayanan.
        </p>
      </div>
    );
  }

  const filteredLogs = logs.filter(log => {
    const matchesSearch =
      log.user_name.toLowerCase().includes(searchQuery.toLowerCase()) ||
      log.aksi.toLowerCase().includes(searchQuery.toLowerCase()) ||
      log.deskripsi.toLowerCase().includes(searchQuery.toLowerCase());

    const isStaffRole = log.user_role.toLowerCase().includes('staff');
    const isKadesRole = log.user_role.toLowerCase().includes('kades') || log.user_role.toLowerCase().includes('kepala');

    if (roleFilter === 'staff') return matchesSearch && isStaffRole;
    if (roleFilter === 'kades') return matchesSearch && isKadesRole;
    return matchesSearch;
  });

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div className="flex items-center gap-2.5">
            <h1 className="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Log Aktivitas Sistem</h1>
            <span className="rounded-full bg-[#1E3A8A]/10 px-3 py-0.5 text-xs font-semibold text-[#1E3A8A] border border-[#1E3A8A]/20">
              Audit Kades
            </span>
          </div>
        </div>
        <button
          onClick={handleRefresh}
          className="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs"
        >
          <RefreshCw className="h-4 w-4 text-slate-400 stroke-[1.8]" />
          <span>Segarkan Log</span>
        </button>
      </div>

      {/* Clean White Card Sheet */}
      <div className="rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-8 shadow-2xs border border-slate-200 space-y-5">
        {/* Filter & Search Controls */}
        <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
          <div className="relative flex-1 max-w-lg">
            <Search className="absolute left-3.5 top-3 h-4 w-4 text-slate-400 stroke-[1.8]" />
            <input
              type="text"
              value={searchQuery}
              onChange={e => setSearchQuery(e.target.value)}
              placeholder="Cari aktivitas atau nama petugas..."
              className="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#1E3A8A] focus:outline-none focus:ring-2 focus:ring-[#1E3A8A]/20"
            />
          </div>

          <div className="flex items-center gap-2">
            <Filter className="h-4 w-4 text-slate-400 stroke-[1.8]" />
            <span className="text-xs font-semibold text-slate-600">Filter Peran:</span>
            <select
              value={roleFilter}
              onChange={e => setRoleFilter(e.target.value as any)}
              className="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/20"
            >
              <option value="all">Semua Pengguna</option>
              <option value="staff">Staff Pelayanan Saja</option>
              <option value="kades">Kepala Desa Saja</option>
            </select>
          </div>
        </div>

        {/* Log Feed */}
        <div className="rounded-2xl border border-slate-200 overflow-hidden divide-y divide-slate-100">
          {filteredLogs.length > 0 ? (
            filteredLogs.map(log => {
              const isStaff = log.user_role.toLowerCase().includes('staff');
              return (
                <div key={log.id} className="p-4 sm:p-5 hover:bg-slate-50/70 transition flex items-start gap-4">
                  <div className={`rounded-xl p-2.5 shrink-0 ${
                    isStaff ? 'bg-slate-100 text-slate-500 border border-slate-200' : 'bg-[#1E3A8A]/10 text-[#1E3A8A] border border-[#1E3A8A]/20'
                  }`}>
                    {isStaff ? <User className="h-4.5 w-4.5 stroke-[1.8]" /> : <Shield className="h-4.5 w-4.5 stroke-[1.8]" />}
                  </div>
                  <div className="flex-1 min-w-0">
                    <div className="flex flex-wrap items-center justify-between gap-2">
                      <div className="flex items-center gap-2">
                        <span className="text-sm font-bold text-slate-900">{log.user_name}</span>
                        <span className={`text-[10px] font-semibold uppercase px-2 py-0.5 rounded-full ${
                          isStaff ? 'bg-slate-100 text-slate-700 border border-slate-200' : 'bg-[#1E3A8A]/10 text-[#1E3A8A] border border-[#1E3A8A]/20'
                        }`}>
                          {log.user_role}
                        </span>
                      </div>
                      <span className="font-mono text-xs text-slate-400 tabular-nums">{log.created_at}</span>
                    </div>
                    <p className="text-xs font-semibold text-slate-800 mt-1">{log.aksi}</p>
                    <p className="text-xs text-slate-500 mt-0.5 leading-relaxed">{log.deskripsi}</p>
                  </div>
                </div>
              );
            })
          ) : (
            <div className="p-12 text-center text-sm text-slate-400">
              Tidak ada riwayat aktivitas yang sesuai dengan filter.
            </div>
          )}
        </div>
      </div>
    </div>
  );
};
