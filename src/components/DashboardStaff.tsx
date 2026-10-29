import React, { useState, useEffect } from 'react';
import { StorageService } from '../services/storage';
import { ApiService } from '../services/api';
import { Users, FileEdit, Clock, CheckCircle, Plus, FilePlus, ChevronRight, ArrowUpRight } from 'lucide-react';
import { SelectJenisSuratModal } from './SelectJenisSuratModal';

interface DashboardStaffProps {
  onNavigate: (view: string) => void;
}

export const DashboardStaff: React.FC<DashboardStaffProps> = ({ onNavigate }) => {
  const [selectJenisModalOpen, setSelectJenisModalOpen] = useState(false);
  const wargaList = StorageService.getWargaList();
  const suratList = StorageService.getSuratList();
  const jenisList = StorageService.getJenisSurat();

  const [dbTotalWarga, setDbTotalWarga] = useState<number | null>(null);

  useEffect(() => {
    ApiService.getStats().then(stats => {
      if (stats) setDbTotalWarga(stats.total_warga);
    });
  }, []);

  const totalWarga = dbTotalWarga ?? wargaList.length;
  const totalSuratDraft = suratList.filter(s => s.status === 'draft').length;
  const totalSuratDiajukan = suratList.filter(s => s.status === 'diajukan').length;
  const totalSuratDisetujui = suratList.filter(s => s.status === 'disetujui').length;

  const recentSurat = suratList.slice(0, 5);

  return (
    <div className="space-y-6 sm:space-y-8">
      {/* Page Title & Action Controls */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
            Pelayanan Administrasi Warga
          </h1>
        </div>

        {/* Action Controls */}
        <div className="flex items-center gap-2 sm:gap-3 shrink-0">
          <button
            id="btn-quick-add-warga"
            onClick={() => onNavigate('warga_create')}
            className="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-800 hover:bg-slate-50 transition active:scale-95 shadow-2xs"
          >
            + Tambah Warga
          </button>
          <button
            id="btn-quick-add-surat"
            onClick={() => setSelectJenisModalOpen(true)}
            className="inline-flex items-center gap-2 rounded-2xl bg-[#1E3A8A] hover:bg-[#172554] px-5 py-2.5 text-xs sm:text-sm font-semibold text-white transition active:scale-95 shadow-sm"
          >
            <Plus className="h-4 w-4 stroke-[2]" />
            <span>Buat Surat Baru</span>
          </button>
        </div>
      </div>

      {/* 4 KPI Metrics - Clean & uncluttered */}
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {/* Metric 1 */}
        <div
          onClick={() => onNavigate('warga')}
          className="group cursor-pointer rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 border border-slate-200 shadow-2xs hover:border-[#1E3A8A] transition-all duration-200"
        >
          <div className="flex items-center justify-between">
            <p className="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Warga</p>
            <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 group-hover:text-[#1E3A8A] transition">
              <Users className="h-5 w-5 stroke-[1.8]" />
            </div>
          </div>
          <p className="mt-4 text-3xl font-bold text-slate-900 tracking-tight tabular-nums">
            {totalWarga.toLocaleString('id-ID')}
          </p>
        </div>

        {/* Metric 2 */}
        <div
          onClick={() => onNavigate('surat_drafts')}
          className="group cursor-pointer rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 border border-slate-200 shadow-2xs hover:border-[#1E3A8A] transition-all duration-200"
        >
          <div className="flex items-center justify-between">
            <p className="text-xs font-semibold uppercase tracking-wider text-slate-500">Draft Surat</p>
            <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 group-hover:text-[#1E3A8A] transition">
              <FileEdit className="h-5 w-5 stroke-[1.8]" />
            </div>
          </div>
          <p className="mt-4 text-3xl font-bold text-slate-900 tracking-tight tabular-nums">
            {totalSuratDraft}
          </p>
        </div>

        {/* Metric 3 */}
        <div
          onClick={() => onNavigate('surat')}
          className="group cursor-pointer rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 border border-slate-200 shadow-2xs hover:border-[#1E3A8A] transition-all duration-200"
        >
          <div className="flex items-center justify-between">
            <p className="text-xs font-semibold uppercase tracking-wider text-slate-500">Menunggu Validasi</p>
            <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 group-hover:text-[#1E3A8A] transition">
              <Clock className="h-5 w-5 stroke-[1.8]" />
            </div>
          </div>
          <p className="mt-4 text-3xl font-bold text-slate-900 tracking-tight tabular-nums">
            {totalSuratDiajukan}
          </p>
        </div>

        {/* Metric 4 */}
        <div
          onClick={() => onNavigate('arsip')}
          className="group cursor-pointer rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 border border-slate-200 shadow-2xs hover:border-[#1E3A8A] transition-all duration-200"
        >
          <div className="flex items-center justify-between">
            <p className="text-xs font-semibold uppercase tracking-wider text-slate-500">Surat Disetujui</p>
            <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 group-hover:text-[#1E3A8A] transition">
              <CheckCircle className="h-5 w-5 stroke-[1.8]" />
            </div>
          </div>
          <p className="mt-4 text-3xl font-bold text-slate-900 tracking-tight tabular-nums">
            {totalSuratDisetujui}
          </p>
        </div>
      </div>

      {/* Recent Surat Section inside Clean White Card */}
      <div className="rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-8 border border-slate-200 shadow-2xs space-y-5">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-2 border-b border-slate-100">
          <div>
            <h2 className="text-lg font-bold text-slate-900">Permohonan Surat Terbaru</h2>
          </div>
          <button
            onClick={() => onNavigate('surat')}
            className="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-800 hover:border-[#1E3A8A] hover:text-[#1E3A8A] transition"
          >
            <span>Buka Semua Surat</span>
            <ChevronRight className="h-4 w-4 stroke-[1.8] text-slate-400" />
          </button>
        </div>

        <div className="overflow-hidden rounded-2xl border border-slate-200">
          <div className="overflow-x-auto">
            <table className="min-w-full text-left text-sm divide-y divide-slate-200">
              <thead className="bg-slate-50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                <tr>
                  <th className="px-5 py-3.5">No. Surat</th>
                  <th className="px-5 py-3.5">Jenis Surat</th>
                  <th className="px-5 py-3.5">Nama Pemohon</th>
                  <th className="px-5 py-3.5">Status</th>
                  <th className="px-5 py-3.5 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {recentSurat.length > 0 ? (
                  recentSurat.map(surat => {
                    const warga = wargaList.find(w => w.id === surat.warga_id);
                    const jenis = jenisList.find(j => j.id === surat.jenis_surat_id);

                    return (
                      <tr key={surat.id} className="hover:bg-slate-50/70 transition-colors">
                        <td className="px-5 py-4 font-mono text-xs font-semibold tabular-nums text-slate-900">
                          {surat.nomor_surat}
                        </td>
                        <td className="px-5 py-4 text-slate-900 font-semibold">
                          {jenis?.nama_jenis || 'Surat Keterangan'}
                        </td>
                        <td className="px-5 py-4 text-slate-800 font-medium">
                          {warga?.nama || 'Warga'}
                        </td>
                        <td className="px-5 py-4">
                          {surat.status === 'draft' && (
                            <span className="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 border border-slate-200">
                              <span className="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                              Draft
                            </span>
                          )}
                          {surat.status === 'diajukan' && (
                            <span className="inline-flex items-center gap-1.5 rounded-full bg-[#1E3A8A]/10 px-2.5 py-1 text-xs font-semibold text-[#1E3A8A] border border-[#1E3A8A]/20">
                              <span className="h-1.5 w-1.5 rounded-full bg-[#1E3A8A]"></span>
                              Menunggu Validasi
                            </span>
                          )}
                          {surat.status === 'disetujui' && (
                            <span className="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800 border border-emerald-200">
                              <span className="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                              Disetujui
                            </span>
                          )}
                          {surat.status === 'ditolak' && (
                            <span className="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-800 border border-rose-200">
                              <span className="h-1.5 w-1.5 rounded-full bg-rose-600"></span>
                              Ditolak
                            </span>
                          )}
                        </td>
                        <td className="px-5 py-4 text-right">
                          <button
                            onClick={() => onNavigate(`surat_detail_${surat.id}`)}
                            className="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-800 hover:border-[#1E3A8A] hover:text-[#1E3A8A] transition"
                          >
                            <span>Buka Berkas</span>
                            <ArrowUpRight className="h-3.5 w-3.5 stroke-[1.8] text-slate-400" />
                          </button>
                        </td>
                      </tr>
                    );
                  })
                ) : (
                  <tr>
                    <td colSpan={5} className="px-5 py-10 text-center text-sm text-slate-400">
                      Belum ada riwayat permohonan surat hari ini.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {/* Modal: Select Jenis Surat Dropdown Card */}
      <SelectJenisSuratModal
        isOpen={selectJenisModalOpen}
        onClose={() => setSelectJenisModalOpen(false)}
        onSelect={(jenisId) => onNavigate(`surat_create_jenis_${jenisId}`)}
      />
    </div>
  );
};
