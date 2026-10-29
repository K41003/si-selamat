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
      <div className="flex flex-col items-center justify-center rounded-2xl bg-white p-12 text-center shadow-xs ring-1 ring-slate-200">
        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-red-100 text-red-600 mb-4">
          <ShieldAlert className="h-7 w-7" />
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
    <div className="space-y-5">
      <div className="flex flex-wrap items-center justify-between gap-3">
        <div>
          <div className="flex items-center gap-2">
            <h1 className="text-xl font-bold text-slate-900">Log Aktivitas Sistem</h1>
            <span className="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
              Khusus Kades
            </span>
          </div>
          <p className="text-sm text-slate-500">
            Rekam jejak audit digital seluruh operasi data warga dan surat, lengkap dengan nama petugas shift.
          </p>
        </div>
        <button
          onClick={handleRefresh}
          className="flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-xs"
        >
          <RefreshCw className="h-3.5 w-3.5" />
          Segarkan
        </button>
      </div>

      {/* Filter & Search Controls */}
      <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between rounded-xl bg-white p-4 shadow-xs ring-1 ring-slate-200">
        <div className="relative flex-1 max-w-md">
          <input
            type="text"
            value={searchQuery}
            onChange={e => setSearchQuery(e.target.value)}
            placeholder="Cari aktivitas atau nama petugas (cth: Amanda, Budi)..."
            className="w-full rounded-lg border border-slate-300 py-2 pl-9 pr-4 text-xs focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800"
          />
          <Search className="absolute left-3 top-2.5 h-4 w-4 text-slate-400" />
        </div>

        <div className="flex items-center gap-2">
          <Filter className="h-3.5 w-3.5 text-slate-400" />
          <span className="text-xs font-medium text-slate-600">Filter Peran:</span>
          <select
            value={roleFilter}
            onChange={e => setRoleFilter(e.target.value as any)}
            className="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-800"
          >
            <option value="all">Semua Pengguna</option>
            <option value="staff">Staff Pelayanan Saja</option>
            <option value="kades">Kepala Desa Saja</option>
          </select>
        </div>
      </div>

      {/* Log Feed */}
      <div className="rounded-xl bg-white shadow-xs ring-1 ring-slate-200 overflow-hidden">
        <div className="divide-y divide-slate-100">
          {filteredLogs.length > 0 ? (
            filteredLogs.map(log => {
              const isStaff = log.user_role.toLowerCase().includes('staff');
              return (
                <div key={log.id} className="p-4 hover:bg-slate-50 transition flex items-start gap-3 text-sm">
                  <div className={`rounded-lg p-2 shrink-0 mt-0.5 ${
                    isStaff ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700'
                  }`}>
                    {isStaff ? <User className="h-4 w-4" /> : <Shield className="h-4 w-4" />}
                  </div>
                  <div className="flex-1 min-w-0">
                    <div className="flex flex-wrap items-center gap-2">
                      <span className="font-bold text-slate-900">{log.user_name}</span>
                      <span className={`rounded px-2 py-0.5 text-[11px] font-semibold uppercase ${
                        isStaff ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800'
                      }`}>
                        {log.user_role}
                      </span>
                      <span className="rounded bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-700">
                        {log.aksi}
                      </span>
                    </div>
                    <p className="mt-1 text-slate-700 text-sm leading-relaxed">{log.deskripsi}</p>
                    <p className="mt-1 font-mono text-[11px] text-slate-400">{log.created_at}</p>
                  </div>
                </div>
              );
            })
          ) : (
            <div className="p-12 text-center text-sm text-slate-400">
              Tidak ada riwayat aktivitas yang sesuai filter.
            </div>
          )}
        </div>
      </div>
    </div>
  );
};
