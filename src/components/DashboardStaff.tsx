import React, { useState, useEffect } from 'react';
import { StorageService } from '../services/storage';
import { ApiService } from '../services/api';
import { Users, FileEdit, Clock, CheckCircle, Plus, FilePlus } from 'lucide-react';
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
    <div className="space-y-6">
      <div className="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-xl font-bold text-slate-900">Dashboard Staff</h1>
          <p className="text-sm text-slate-500">Ringkasan data warga dan permohonan surat administrasi.</p>
        </div>
        <div className="flex items-center gap-2 pt-2 sm:pt-0">
          <button
            id="btn-quick-add-warga"
            onClick={() => onNavigate('warga_create')}
            className="flex items-center gap-1.5 rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white hover:bg-slate-800 transition shadow-xs"
          >
            <Plus className="h-4 w-4" />
            Tambah Warga
          </button>
          <button
            id="btn-quick-add-surat"
            onClick={() => setSelectJenisModalOpen(true)}
            className="flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-xs"
          >
            <FilePlus className="h-4 w-4" />
            Buat Surat
          </button>
        </div>
      </div>

      {/* 4 Stat Cards */}
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div
          onClick={() => onNavigate('warga')}
          className="cursor-pointer rounded-xl bg-white p-5 shadow-xs ring-1 ring-slate-200 hover:ring-slate-300 transition"
        >
          <div className="flex items-center justify-between">
            <p className="text-sm font-medium text-slate-500">Total Data Warga</p>
            <div className="rounded-lg bg-slate-100 p-2 text-slate-700">
              <Users className="h-5 w-5" />
            </div>
          </div>
          <p className="mt-2 text-3xl font-bold text-slate-900">{totalWarga}</p>
          <p className="mt-1 text-xs text-slate-400">Terdaftar di database kependudukan</p>
        </div>

        <div
          onClick={() => onNavigate('surat_drafts')}
          className="cursor-pointer rounded-xl bg-white p-5 shadow-xs ring-1 ring-slate-200 hover:ring-slate-300 transition"
        >
          <div className="flex items-center justify-between">
            <p className="text-sm font-medium text-slate-500">Draft Surat</p>
            <div className="rounded-lg bg-amber-50 p-2 text-amber-600">
              <FileEdit className="h-5 w-5" />
            </div>
          </div>
          <p className="mt-2 text-3xl font-bold text-amber-600">{totalSuratDraft}</p>
          <p className="mt-1 text-xs text-slate-400">Belum diajukan ke Kepala Desa</p>
        </div>

        <div
          onClick={() => onNavigate('surat')}
          className="cursor-pointer rounded-xl bg-white p-5 shadow-xs ring-1 ring-slate-200 hover:ring-slate-300 transition"
        >
          <div className="flex items-center justify-between">
            <p className="text-sm font-medium text-slate-500">Menunggu Validasi</p>
            <div className="rounded-lg bg-blue-50 p-2 text-blue-600">
              <Clock className="h-5 w-5" />
            </div>
          </div>
          <p className="mt-2 text-3xl font-bold text-blue-600">{totalSuratDiajukan}</p>
          <p className="mt-1 text-xs text-slate-400">Sedang diproses Kades</p>
        </div>

        <div
          onClick={() => onNavigate('arsip')}
          className="cursor-pointer rounded-xl bg-white p-5 shadow-xs ring-1 ring-slate-200 hover:ring-slate-300 transition"
        >
          <div className="flex items-center justify-between">
            <p className="text-sm font-medium text-slate-500">Surat Disetujui</p>
            <div className="rounded-lg bg-emerald-50 p-2 text-emerald-600">
              <CheckCircle className="h-5 w-5" />
            </div>
          </div>
          <p className="mt-2 text-3xl font-bold text-emerald-600">{totalSuratDisetujui}</p>
          <p className="mt-1 text-xs text-slate-400">Siap dicetak &amp; diserahkan</p>
        </div>
      </div>

      {/* Recent Surat Section */}
      <div className="rounded-xl bg-white p-5 shadow-xs ring-1 ring-slate-200">
        <div className="mb-4 flex items-center justify-between">
          <div>
            <h2 className="text-base font-semibold text-slate-900">Permohonan Surat Terkini</h2>
            <p className="text-xs text-slate-500">Daftar berkas surat yang baru dibuat atau diperbarui.</p>
          </div>
          <button
            onClick={() => onNavigate('surat')}
            className="text-xs font-semibold text-slate-700 hover:text-slate-900"
          >
            Lihat Semua &rarr;
          </button>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm">
            <thead className="border-b border-slate-100 bg-slate-50 text-xs font-semibold text-slate-500 uppercase">
              <tr>
                <th className="px-4 py-3">No. Surat</th>
                <th className="px-4 py-3">Jenis Surat</th>
                <th className="px-4 py-3">Nama Pemohon</th>
                <th className="px-4 py-3">Status</th>
                <th className="px-4 py-3 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {recentSurat.map(surat => {
                const warga = wargaList.find(w => w.id === surat.warga_id);
                const jenis = jenisList.find(j => j.id === surat.jenis_surat_id);

                return (
                  <tr key={surat.id} className="hover:bg-slate-50">
                    <td className="px-4 py-3 font-mono text-xs font-medium text-slate-700">
                      {surat.nomor_surat}
                    </td>
                    <td className="px-4 py-3 text-slate-900 font-medium">
                      {jenis?.nama_jenis || 'Surat Keterangan'}
                    </td>
                    <td className="px-4 py-3 text-slate-600">
                      {warga?.nama || 'Warga'}
                    </td>
                    <td className="px-4 py-3">
                      {surat.status === 'draft' && (
                        <span className="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-amber-600/20">
                          Draft
                        </span>
                      )}
                      {surat.status === 'diajukan' && (
                        <span className="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-blue-600/20">
                          Menunggu Validasi
                        </span>
                      )}
                      {surat.status === 'disetujui' && (
                        <span className="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-emerald-600/20">
                          Disetujui
                        </span>
                      )}
                      {surat.status === 'ditolak' && (
                        <span className="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 ring-1 ring-red-600/20">
                          Ditolak
                        </span>
                      )}
                    </td>
                    <td className="px-4 py-3 text-right">
                      <button
                        onClick={() => onNavigate(`surat_detail_${surat.id}`)}
                        className="text-xs font-semibold text-slate-700 hover:text-slate-900"
                      >
                        Detail &rarr;
                      </button>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
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
