import React, { useState } from 'react';
import { StorageService } from '../services/storage';
import { useAuth } from '../context/AuthContext';
import { Surat } from '../types';
import { SelectJenisSuratModal } from './SelectJenisSuratModal';
import {
  Plus,
  Search,
  Eye,
  Printer,
  Send,
  FileCheck,
  Edit,
  Trash2,
  ChevronLeft,
  ChevronRight,
  AlertTriangle,
  FileText,
  Clock,
  CheckCircle2,
  XCircle
} from 'lucide-react';

interface SuratListProps {
  mode?: 'all' | 'drafts' | 'validasi' | 'arsip';
  onNavigate: (view: string) => void;
}

export const SuratList: React.FC<SuratListProps> = ({ mode = 'all', onNavigate }) => {
  const { user } = useAuth();
  const [suratList, setSuratList] = useState<Surat[]>(() => StorageService.getSuratList());
  const [search, setSearch] = useState('');
  const [selectedJenisId, setSelectedJenisId] = useState<string>('all');
  const [selectJenisModalOpen, setSelectJenisModalOpen] = useState(false);
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 8;
  
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
  const ditolakCount = suratList.filter(s => s.status === 'ditolak').length;

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

    // Filter by jenis surat
    if (selectedJenisId !== 'all' && String(s.jenis_surat_id) !== selectedJenisId) {
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

  // Pagination calculation
  const totalPages = Math.ceil(filtered.length / itemsPerPage) || 1;
  const paginatedData = filtered.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage);

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
    return 'Kelola pembuatan draft surat, pengajuan ke Kepala Desa, dan riwayat arsip berkas desa.';
  };

  const tabs = [
    { id: 'all', label: 'Semua Surat', count: suratList.length },
    { id: 'draft', label: 'Draft', count: draftCount },
    { id: 'diajukan', label: 'Menunggu Validasi', count: diajukanCount },
    { id: 'disetujui', label: 'Disetujui', count: disetujuiCount },
    ...(ditolakCount > 0 ? [{ id: 'ditolak', label: 'Ditolak', count: ditolakCount }] : []),
  ];

  return (
    <div className="space-y-6">
      {/* Page Title */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
            {getTitle()}
          </h1>
        </div>

        {user?.role === 'staff' && mode !== 'arsip' && (
          <button
            id="btn-create-surat"
            onClick={() => setSelectJenisModalOpen(true)}
            className="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#1E3A8A] px-5 py-2.5 text-xs sm:text-sm font-semibold text-white hover:bg-[#172554] transition shadow-sm active:scale-95 shrink-0"
          >
            <Plus className="h-4.5 w-4.5 stroke-[2]" />
            <span>Buat Permohonan Surat</span>
          </button>
        )}
      </div>

      {/* Sheet Container with Tabs */}
      <div>
        {mode === 'all' && (
          <div className="flex items-center gap-1.5 overflow-x-auto pb-0 px-2 sm:px-4">
            {tabs.map(tab => {
              const isActive = statusFilter === tab.id;
              return (
                <button
                  key={tab.id}
                  onClick={() => {
                    setStatusFilter(tab.id);
                    setCurrentPage(1);
                  }}
                  className={`relative flex items-center gap-2 px-5 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold rounded-t-2xl transition-all duration-150 shrink-0 ${
                    isActive
                      ? 'bg-white text-slate-900 shadow-2xs border-t-2 border-[#1E3A8A] -mb-px z-10'
                      : 'bg-slate-100 text-slate-600 hover:bg-slate-200/70 hover:text-slate-900'
                  }`}
                >
                  <span>{tab.label}</span>
                  <span
                    className={`text-[11px] font-bold px-2 py-0.5 rounded-full ${
                      isActive
                        ? 'bg-[#1E3A8A]/10 text-[#1E3A8A]'
                        : 'bg-slate-200 text-slate-700'
                    }`}
                  >
                    {tab.count}
                  </span>
                </button>
              );
            })}
          </div>
        )}

        {/* Clean White Card Sheet */}
        <div className={`rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-8 shadow-2xs border border-slate-200 space-y-5 ${mode === 'all' ? 'rounded-tl-none' : ''}`}>
          {/* Search, Filter & Action Row matching reference image */}
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div className="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
              {/* Search Input */}
              <div className="relative flex-1 max-w-md">
                <Search className="absolute left-3.5 top-3 h-4.5 w-4.5 text-slate-400 stroke-[1.8]" />
                <input
                  type="text"
                  value={search}
                  onChange={e => {
                    setSearch(e.target.value);
                    setCurrentPage(1);
                  }}
                  placeholder="Cari No. Surat, Nama Pemohon, NIK..."
                  className="w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 py-2.5 text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#1E3A8A] focus:outline-none focus:ring-2 focus:ring-[#1E3A8A]/20 transition"
                />
              </div>

              {/* Jenis Surat Dropdown Filter */}
              <div className="w-full sm:w-56">
                <select
                  value={selectedJenisId}
                  onChange={e => {
                    setSelectedJenisId(e.target.value);
                    setCurrentPage(1);
                  }}
                  className="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:bg-white focus:border-[#1E3A8A] focus:outline-none focus:ring-2 focus:ring-[#1E3A8A]/20 transition"
                >
                  <option value="all">Semua Jenis Surat</option>
                  {jenisList.map(j => (
                    <option key={j.id} value={j.id}>
                      {j.nama_jenis}
                    </option>
                  ))}
                </select>
              </div>
            </div>

            <div className="text-xs text-slate-500 font-medium">
              Menampilkan <strong>{filtered.length}</strong> berkas surat
            </div>
          </div>

          {/* Clean Data Table */}
          <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead className="bg-slate-50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                  <tr>
                    <th className="px-5 py-3.5">No. Surat</th>
                    <th className="px-5 py-3.5">Jenis Surat</th>
                    <th className="px-5 py-3.5">Warga Pemohon</th>
                    <th className="px-5 py-3.5">Tanggal</th>
                    <th className="px-5 py-3.5">Status</th>
                    <th className="px-5 py-3.5 text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {paginatedData.length > 0 ? (
                    paginatedData.map(surat => {
                      const warga = wargaList.find(w => w.id === surat.warga_id);
                      const jenis = jenisList.find(j => j.id === surat.jenis_surat_id);

                      return (
                        <tr key={surat.id} className="hover:bg-slate-50/70 transition-colors">
                          <td className="px-5 py-4 font-mono text-xs font-semibold tabular-nums text-slate-900">
                            {surat.nomor_surat}
                          </td>
                          <td className="px-5 py-4">
                            <div className="font-semibold text-slate-900 text-sm">
                              {jenis?.nama_jenis || 'Surat Keterangan'}
                            </div>
                            <div className="text-[11px] font-mono text-slate-400">
                              Kode: {jenis?.kode}
                            </div>
                          </td>
                          <td className="px-5 py-4">
                            <div className="font-semibold text-slate-900 text-sm">
                              {warga?.nama || 'Warga'}
                            </div>
                            <div className="font-mono text-[11px] text-slate-400 tabular-nums">
                              NIK: {warga?.nik}
                            </div>
                          </td>
                          <td className="px-5 py-4 text-xs text-slate-600 font-mono tabular-nums">
                            {surat.created_at.split(' ')[0]}
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
                            <div className="flex justify-end items-center gap-1.5">
                              {/* DETAIL BUTTON */}
                              <button
                                onClick={() => onNavigate(`surat_detail_${surat.id}`)}
                                className="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-800 hover:border-[#1E3A8A] hover:text-[#1E3A8A] transition"
                                title="Lihat Berkas Surat"
                              >
                                <Eye className="h-3.5 w-3.5 stroke-[1.8] text-slate-400" />
                                <span>Detail</span>
                              </button>

                              {/* DRAFT ACTIONS */}
                              {user?.role === 'staff' && surat.status === 'draft' && (
                                <>
                                  <button
                                    onClick={() => onNavigate(`surat_edit_${surat.id}`)}
                                    className="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
                                    title="Edit Draft"
                                  >
                                    <Edit className="h-3.5 w-3.5 stroke-[1.8] text-slate-400" />
                                    <span>Edit</span>
                                  </button>
                                  <button
                                    onClick={() => handleAjukan(surat.id)}
                                    className="inline-flex items-center gap-1.5 rounded-lg bg-[#1E3A8A] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#172554] transition shadow-xs"
                                    title="Kirim ke Kades untuk ditandatangani"
                                  >
                                    <Send className="h-3.5 w-3.5 stroke-[1.8]" />
                                    <span>Ajukan</span>
                                  </button>
                                  <button
                                    onClick={() => {
                                      setTargetDeleteSurat(surat);
                                      setDeleteModalOpen(true);
                                    }}
                                    className="inline-flex items-center p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition"
                                    title="Hapus Draft"
                                  >
                                    <Trash2 className="h-4 w-4 stroke-[1.8]" />
                                  </button>
                                </>
                              )}

                              {/* KADES VALIDATION */}
                              {user?.role === 'kades' && surat.status === 'diajukan' && (
                                <button
                                  onClick={() => onNavigate(`surat_detail_${surat.id}`)}
                                  className="inline-flex items-center gap-1.5 rounded-lg bg-[#1E3A8A] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#172554] transition shadow-xs"
                                >
                                  <FileCheck className="h-3.5 w-3.5 stroke-[1.8]" />
                                  <span>Validasi</span>
                                </button>
                              )}

                              {/* APPROVED PRINT */}
                              {surat.status === 'disetujui' && (
                                <button
                                  onClick={() => onNavigate(`surat_print_${surat.id}`)}
                                  className="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-800 hover:border-[#1E3A8A] hover:text-[#1E3A8A] transition"
                                >
                                  <Printer className="h-3.5 w-3.5 stroke-[1.8] text-slate-400" />
                                  <span>Cetak</span>
                                </button>
                              )}
                            </div>
                          </td>
                        </tr>
                      );
                    })
                  ) : (
                    <tr>
                      <td colSpan={6} className="px-5 py-14 text-center text-sm text-slate-400">
                        Tidak ada berkas surat yang sesuai dengan filter atau pencarian.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>

          {/* Numbered Pagination with Navy Blue styling */}
          {totalPages > 1 && (
            <div className="flex items-center justify-between pt-2">
              <span className="text-xs text-slate-500 font-medium">
                Halaman {currentPage} dari {totalPages}
              </span>

              <div className="flex items-center gap-1.5">
                <button
                  onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
                  disabled={currentPage === 1}
                  className="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 disabled:opacity-40 transition"
                  aria-label="Halaman Sebelumnya"
                >
                  <ChevronLeft className="h-4 w-4 stroke-[1.8]" />
                </button>

                {Array.from({ length: totalPages }, (_, i) => i + 1).map(page => (
                  <button
                    key={page}
                    onClick={() => setCurrentPage(page)}
                    className={`flex h-8 w-8 items-center justify-center rounded-lg text-xs font-semibold transition ${
                      currentPage === page
                        ? 'border border-[#1E3A8A] bg-[#1E3A8A] text-white font-bold'
                        : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                    }`}
                  >
                    {page}
                  </button>
                ))}

                <button
                  onClick={() => setCurrentPage(prev => Math.min(prev + 1, totalPages))}
                  disabled={currentPage === totalPages}
                  className="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 disabled:opacity-40 transition"
                  aria-label="Halaman Berikutnya"
                >
                  <ChevronRight className="h-4 w-4 stroke-[1.8]" />
                </button>
              </div>
            </div>
          )}
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
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4">
          <div className="w-full max-w-md rounded-3xl bg-white p-6 sm:p-8 shadow-2xl space-y-4">
            <div className="flex items-center gap-3 text-rose-600">
              <div className="rounded-2xl bg-rose-50 p-3">
                <AlertTriangle className="h-6 w-6 stroke-[1.8]" />
              </div>
              <h3 className="text-lg font-bold text-slate-900">Hapus Draft Surat?</h3>
            </div>
            <p className="text-sm text-slate-600 leading-relaxed">
              Apakah Anda yakin ingin menghapus draft surat nomor{' '}
              <strong className="font-mono text-slate-900">{targetDeleteSurat.nomor_surat}</strong>? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div className="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
              <button
                type="button"
                onClick={() => setDeleteModalOpen(false)}
                className="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
              >
                Batal
              </button>
              <button
                type="button"
                onClick={confirmDelete}
                className="rounded-xl bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-700 transition shadow-xs"
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
