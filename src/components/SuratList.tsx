import React, { useState } from 'react';
import { StorageService } from '../services/storage';
import { useAuth } from '../context/AuthContext';
import { Surat, SuratStatus } from '../types';
import { SelectJenisSuratModal } from './SelectJenisSuratModal';
import {
  Plus,
  Search,
  Filter,
  Eye,
  Printer,
  Send,
  FileCheck,
  Edit,
  Trash2,
  FileEdit,
  CheckCircle2,
  Clock,
  XCircle,
  AlertTriangle
} from 'lucide-react';

interface SuratListProps {
  mode?: 'all' | 'drafts' | 'validasi' | 'arsip';
  onNavigate: (view: string) => void;
}

export const SuratList: React.FC<SuratListProps> = ({ mode = 'all', onNavigate }) => {
  const { user } = useAuth();
  const [suratList, setSuratList] = useState<Surat[]>(() => StorageService.getSuratList());
  const [search, setSearch] = useState('');
  const [selectJenisModalOpen, setSelectJenisModalOpen] = useState(false);
  
  // Set initial status filter based on mode
  const [statusFilter, setStatusFilter] = useState<string>(() => {
    if (mode === 'drafts') return 'draft';
    if (mode === 'validasi') return 'diajukan';
    if (mode === 'arsip') return 'disetujui';
    return 'all';
  });

  const [deleteModalOpen, setDeleteModalOpen] = useState(false);
  const [targetDeleteSurat, setTargetDeleteSurat] = useState<Surat | null>(null);

  const wargaList = StorageService.getWargaList();
  const jenisList = StorageService.getJenisSurat();

  const handleAjukan = (suratId: number) => {
    if (!user) return;
    StorageService.updateSuratStatus(suratId, 'diajukan', user);
    setSuratList(StorageService.getSuratList());
  };

  const confirmDelete = () => {
    if (!targetDeleteSurat || !user) return;
    StorageService.deleteSurat(targetDeleteSurat.id, user);
    setSuratList(StorageService.getSuratList());
    setDeleteModalOpen(false);
    setTargetDeleteSurat(null);
  };

  const draftCount = suratList.filter(s => s.status === 'draft').length;
  const diajukanCount = suratList.filter(s => s.status === 'diajukan').length;
  const disetujuiCount = suratList.filter(s => s.status === 'disetujui').length;

  const filtered = suratList.filter(s => {
    const warga = wargaList.find(w => w.id === s.warga_id);
    const jenis = jenisList.find(j => j.id === s.jenis_surat_id);

    // Filter by mode
    if (mode === 'drafts' && s.status !== 'draft') return false;
    if (mode === 'validasi' && s.status !== 'diajukan') return false;
    if (mode === 'arsip' && s.status !== 'disetujui') return false;

    // Filter by tab when mode is 'all'
    if (mode === 'all' && statusFilter !== 'all' && s.status !== statusFilter) {
      return false;
    }

    if (!search.trim()) return true;
    const q = search.toLowerCase();
    const noSurat = s.nomor_surat.toLowerCase();
    const namaWarga = warga?.nama.toLowerCase() || '';
    const nikWarga = warga?.nik || '';
    const namaJenis = jenis?.nama_jenis.toLowerCase() || '';

    return noSurat.includes(q) || namaWarga.includes(q) || nikWarga.includes(q) || namaJenis.includes(q);
  });

  const getTitle = () => {
    if (mode === 'drafts') return 'Daftar Draft Surat';
    if (mode === 'validasi') return 'Validasi Surat (Kepala Desa)';
    if (mode === 'arsip') return 'Arsip & Cetak Surat';
    return 'Permohonan & Kelola Surat';
  };

  const getSubtitle = () => {
    if (mode === 'drafts') return 'Daftar surat yang disimpan sebagai draft dan siap diedit atau diajukan ke Kepala Desa.';
    if (mode === 'validasi') return 'Daftar permohonan surat warga yang menunggu persetujuan dan tanda tangan QR E-Signature.';
    if (mode === 'arsip') return 'Arsip seluruh surat yang telah disetujui, siap diunduh atau dicetak.';
    return 'Kelola pembuatan draft surat, pengajuan ke Kepala Desa, dan riwayat arsip.';
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-wrap items-center justify-between gap-3">
        <div>
          <h1 className="text-xl font-bold text-slate-900">{getTitle()}</h1>
          <p className="text-sm text-slate-500">{getSubtitle()}</p>
        </div>
        {user?.role === 'staff' && mode !== 'arsip' && (
          <button
            id="btn-create-surat"
            onClick={() => setSelectJenisModalOpen(true)}
            className="flex items-center gap-1.5 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition shadow-xs"
          >
            <Plus className="h-4 w-4 text-emerald-400" />
            Buat Surat Baru
          </button>
        )}
      </div>

      {/* Filter and Search Bar */}
      <div className="rounded-xl bg-white p-4 shadow-xs ring-1 ring-slate-200 space-y-3">
        <div className="flex flex-wrap items-center justify-between gap-3">
          <div className="relative flex-1 min-w-[240px] max-w-md">
            <Search className="absolute left-3 top-2.5 h-4 w-4 text-slate-400" />
            <input
              type="text"
              value={search}
              onChange={e => setSearch(e.target.value)}
              placeholder="Cari nomor surat, nama pemohon, NIK..."
              className="w-full rounded-lg border border-slate-300 pl-9 pr-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
            />
          </div>

          {mode === 'all' && (
            <div className="flex flex-wrap items-center gap-1.5">
              <span className="text-xs font-medium text-slate-400 mr-1 flex items-center gap-1">
                <Filter className="h-3.5 w-3.5" /> Status:
              </span>
              <button
                onClick={() => setStatusFilter('all')}
                className={`rounded-lg px-3 py-1.5 text-xs font-medium transition ${
                  statusFilter === 'all'
                    ? 'bg-slate-900 text-white shadow-xs font-semibold'
                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                }`}
              >
                Semua ({suratList.length})
              </button>

              <button
                onClick={() => setStatusFilter('draft')}
                className={`flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium transition ${
                  statusFilter === 'draft'
                    ? 'bg-amber-600 text-white shadow-xs font-semibold'
                    : 'bg-amber-50 text-amber-700 hover:bg-amber-100 ring-1 ring-amber-200/50'
                }`}
              >
                <FileEdit className="h-3.5 w-3.5" />
                Draft ({draftCount})
              </button>

              <button
                onClick={() => setStatusFilter('diajukan')}
                className={`flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium transition ${
                  statusFilter === 'diajukan'
                    ? 'bg-blue-600 text-white shadow-xs font-semibold'
                    : 'bg-blue-50 text-blue-700 hover:bg-blue-100 ring-1 ring-blue-200/50'
                }`}
              >
                <Clock className="h-3.5 w-3.5" />
                Diajukan ({diajukanCount})
              </button>

              <button
                onClick={() => setStatusFilter('disetujui')}
                className={`flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium transition ${
                  statusFilter === 'disetujui'
                    ? 'bg-emerald-600 text-white shadow-xs font-semibold'
                    : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 ring-1 ring-emerald-200/50'
                }`}
              >
                <CheckCircle2 className="h-3.5 w-3.5" />
                Disetujui ({disetujuiCount})
              </button>
            </div>
          )}
        </div>
      </div>

      {/* Surat List Table */}
      <div className="overflow-hidden rounded-xl bg-white shadow-xs ring-1 ring-slate-200">
        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-slate-200 text-sm">
            <thead className="bg-slate-50 text-xs font-semibold text-slate-600 uppercase">
              <tr>
                <th className="px-4 py-3.5 text-left">No. Surat</th>
                <th className="px-4 py-3.5 text-left">Jenis Surat</th>
                <th className="px-4 py-3.5 text-left">Warga Pemohon</th>
                <th className="px-4 py-3.5 text-left">Tanggal</th>
                <th className="px-4 py-3.5 text-left">Status</th>
                <th className="px-4 py-3.5 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {filtered.length > 0 ? (
                filtered.map(surat => {
                  const warga = wargaList.find(w => w.id === surat.warga_id);
                  const jenis = jenisList.find(j => j.id === surat.jenis_surat_id);

                  return (
                    <tr key={surat.id} className="hover:bg-slate-50/80 transition">
                      <td className="px-4 py-3.5 font-mono text-xs font-semibold text-slate-800">
                        {surat.nomor_surat}
                      </td>
                      <td className="px-4 py-3.5">
                        <div className="font-semibold text-slate-900">{jenis?.nama_jenis || 'Surat Keterangan'}</div>
                        <div className="text-[11px] font-mono text-slate-400">Kode: {jenis?.kode}</div>
                      </td>
                      <td className="px-4 py-3.5">
                        <div className="font-semibold text-slate-900">{warga?.nama || 'Warga'}</div>
                        <div className="font-mono text-xs text-slate-500">NIK: {warga?.nik}</div>
                      </td>
                      <td className="px-4 py-3.5 text-xs text-slate-500">
                        {surat.created_at.split(' ')[0]}
                      </td>
                      <td className="px-4 py-3.5">
                        {surat.status === 'draft' && (
                          <span className="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700 ring-1 ring-amber-600/20">
                            <FileEdit className="h-3 w-3" /> Draft
                          </span>
                        )}
                        {surat.status === 'diajukan' && (
                          <span className="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700 ring-1 ring-blue-600/20">
                            <Clock className="h-3 w-3" /> Menunggu Validasi
                          </span>
                        )}
                        {surat.status === 'disetujui' && (
                          <span className="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-600/20">
                            <CheckCircle2 className="h-3 w-3" /> Disetujui
                          </span>
                        )}
                        {surat.status === 'ditolak' && (
                          <span className="inline-flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-red-600/20">
                            <XCircle className="h-3 w-3" /> Ditolak
                          </span>
                        )}
                      </td>
                      <td className="px-4 py-3.5 text-right">
                        <div className="flex justify-end items-center gap-1.5">
                          {/* DETAIL BUTTON */}
                          <button
                            onClick={() => onNavigate(`surat_detail_${surat.id}`)}
                            className="inline-flex items-center gap-1 rounded-md px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 transition"
                            title="Lihat Detail"
                          >
                            <Eye className="h-3.5 w-3.5" />
                            Detail
                          </button>

                          {/* DRAFT SPECIFIC ACTIONS */}
                          {user?.role === 'staff' && surat.status === 'draft' && (
                            <>
                              <button
                                onClick={() => onNavigate(`surat_edit_${surat.id}`)}
                                className="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition"
                                title="Edit Draft"
                              >
                                <Edit className="h-3.5 w-3.5" />
                                Edit
                              </button>
                              <button
                                onClick={() => handleAjukan(surat.id)}
                                className="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2.5 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100 ring-1 ring-blue-200/50 transition"
                                title="Ajukan ke Kades"
                              >
                                <Send className="h-3.5 w-3.5" />
                                Ajukan
                              </button>
                              <button
                                onClick={() => {
                                  setTargetDeleteSurat(surat);
                                  setDeleteModalOpen(true);
                                }}
                                className="inline-flex items-center p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-md transition"
                                title="Hapus Draft"
                              >
                                <Trash2 className="h-3.5 w-3.5" />
                              </button>
                            </>
                          )}

                          {/* KADES VALIDATION ACTION */}
                          {user?.role === 'kades' && surat.status === 'diajukan' && (
                            <button
                              onClick={() => onNavigate(`surat_detail_${surat.id}`)}
                              className="inline-flex items-center gap-1 rounded-md bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800 transition"
                            >
                              <FileCheck className="h-3.5 w-3.5 text-emerald-400" />
                              Validasi
                            </button>
                          )}

                          {/* APPROVED PRINT ACTION */}
                          {surat.status === 'disetujui' && (
                            <button
                              onClick={() => onNavigate(`surat_print_${surat.id}`)}
                              className="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 ring-1 ring-emerald-200/60 transition"
                            >
                              <Printer className="h-3.5 w-3.5" />
                              Cetak
                            </button>
                          )}
                        </div>
                      </td>
                    </tr>
                  );
                })
              ) : (
                <tr>
                  <td colSpan={6} className="px-4 py-12 text-center text-sm text-slate-400">
                    Tidak ada data surat yang sesuai dengan filter atau pencarian.
                  </td>
                </tr>
              )}
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

      {/* Delete Confirmation Modal */}
      {deleteModalOpen && targetDeleteSurat && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
          <div className="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl space-y-4">
            <div className="flex items-center gap-3 text-red-600">
              <div className="rounded-full bg-red-100 p-2.5">
                <AlertTriangle className="h-6 w-6" />
              </div>
              <h3 className="text-lg font-bold text-slate-900">Hapus Draft Surat?</h3>
            </div>
            <p className="text-sm text-slate-600 leading-relaxed">
              Apakah Anda yakin ingin menghapus draft surat nomor{' '}
              <strong className="font-mono text-slate-900">{targetDeleteSurat.nomor_surat}</strong>? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div className="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
              <button
                type="button"
                onClick={() => setDeleteModalOpen(false)}
                className="rounded-lg border border-slate-200 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50"
              >
                Batal
              </button>
              <button
                type="button"
                onClick={confirmDelete}
                className="rounded-lg bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700 transition"
              >
                Ya, Hapus Draft
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
