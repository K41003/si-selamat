import React, { useState, useEffect, useCallback } from 'react';
import { ApiService } from '../services/api';
import { StorageService } from '../services/storage';
import { useAuth } from '../context/AuthContext';
import { maskIdentityNumber } from '../utils';
import { Warga } from '../types';
import {
  Search,
  Plus,
  Eye,
  EyeOff,
  Edit2,
  Trash2,
  AlertTriangle,
  X,
  Loader2,
  ChevronLeft,
  ChevronRight,
  FilePlus,
  Database
} from 'lucide-react';

interface WargaListProps {
  onNavigate: (view: string) => void;
}

export const WargaList: React.FC<WargaListProps> = ({ onNavigate }) => {
  const { user } = useAuth();
  const [wargaList, setWargaList] = useState<Warga[]>([]);
  const [totalCount, setTotalCount] = useState<number>(0);
  const [totalPages, setTotalPages] = useState<number>(1);
  const [loading, setLoading] = useState<boolean>(true);
  const [isFromDatabase, setIsFromDatabase] = useState<boolean>(false);

  const [search, setSearch] = useState('');
  const [deleteTarget, setDeleteTarget] = useState<Warga | null>(null);
  const [toastMessage, setToastMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [showFullNik, setShowFullNik] = useState(false);
  const itemsPerPage = 10;

  const loadData = useCallback(async (page: number, searchQuery: string) => {
    setLoading(true);
    const apiRes = await ApiService.getWarga(page, itemsPerPage, searchQuery);

    if (apiRes && apiRes.status === 'success') {
      setWargaList(apiRes.data);
      setTotalCount(apiRes.total);
      setTotalPages(apiRes.last_page);
      setIsFromDatabase(true);
    } else {
      // Fallback to local storage if API is offline
      const local = StorageService.getWargaList();
      const q = searchQuery.toLowerCase().trim();
      const filtered = q
        ? local.filter(w => w.nama.toLowerCase().includes(q) || w.nik.toLowerCase().includes(q))
        : local;
      const pages = Math.ceil(filtered.length / itemsPerPage) || 1;
      const paginated = filtered.slice((page - 1) * itemsPerPage, page * itemsPerPage);

      setWargaList(paginated);
      setTotalCount(filtered.length);
      setTotalPages(pages);
      setIsFromDatabase(false);
    }
    setLoading(false);
  }, [itemsPerPage]);

  useEffect(() => {
    loadData(currentPage, search);
  }, [currentPage, search, loadData]);

  const handleDelete = async () => {
    if (!deleteTarget || !user) return;

    const successApi = await ApiService.deleteWarga(deleteTarget.id);
    StorageService.deleteWarga(deleteTarget.id, user.name, user.role);

    if (successApi) {
      setToastMessage({ type: 'success', text: `Data warga ${deleteTarget.nama} berhasil dihapus dari database.` });
    } else {
      setToastMessage({ type: 'success', text: `Data warga ${deleteTarget.nama} berhasil dihapus.` });
    }

    setDeleteTarget(null);
    loadData(currentPage, search);
  };

  return (
    <div className="space-y-6">
      {/* Toast Alert */}
      {toastMessage && (
        <div
          className={`flex items-center justify-between rounded-2xl px-5 py-3 text-xs sm:text-sm font-semibold shadow-xs ${
            toastMessage.type === 'success'
              ? 'bg-slate-900 text-white border border-slate-800'
              : 'bg-rose-50 text-rose-900 border border-rose-200'
          }`}
        >
          <span>{toastMessage.text}</span>
          <button onClick={() => setToastMessage(null)} className="text-slate-400 hover:text-white p-1">
            <X className="h-4 w-4 stroke-[1.8]" />
          </button>
        </div>
      )}

      {/* Title & Primary Action */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div className="flex items-center gap-3">
            <h1 className="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
              Data Kependudukan
            </h1>
            {isFromDatabase && (
              <span className="inline-flex items-center gap-1.5 text-xs font-mono font-medium text-[#1E3A8A] bg-[#1E3A8A]/10 border border-[#1E3A8A]/20 px-2.5 py-0.5 rounded-full">
                <span className="h-1.5 w-1.5 rounded-full bg-[#1E3A8A]"></span>
                MySQL Live
              </span>
            )}
          </div>
          <p className="text-sm text-slate-500 mt-1">
            Total {totalCount.toLocaleString('id-ID')} warga terdaftar di Desa Tanjung Selamat
          </p>
        </div>

        {user?.role === 'staff' && (
          <button
            id="btn-add-warga"
            onClick={() => onNavigate('warga_create')}
            className="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#1E3A8A] px-5 py-2.5 text-xs sm:text-sm font-semibold text-white hover:bg-[#172554] transition shadow-sm active:scale-95 shrink-0"
          >
            <Plus className="h-4.5 w-4.5 stroke-[2]" />
            <span>Tambah Data Warga</span>
          </button>
        )}
      </div>

      {/* Clean White Card Sheet */}
      <div className="rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-8 shadow-2xs border border-slate-200 space-y-5">
        {/* Search & Actions Bar */}
        <div className="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
          <div className="relative flex-1 max-w-lg">
            <Search className="absolute left-3.5 top-3 h-4.5 w-4.5 text-slate-400 stroke-[1.8]" />
            <input
              type="text"
              value={search}
              onChange={e => {
                setSearch(e.target.value);
                setCurrentPage(1);
              }}
              placeholder="Cari berdasarkan Nama, NIK, atau Dusun..."
              className="w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 py-2.5 text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#1E3A8A] focus:outline-none focus:ring-2 focus:ring-[#1E3A8A]/20 transition"
            />
          </div>

          <div className="flex items-center gap-2 self-end sm:self-auto">
            <button
              type="button"
              onClick={() => setShowFullNik(!showFullNik)}
              className="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
              title={showFullNik ? "Samarkan NIK (Privasi)" : "Tampilkan NIK Lengkap"}
            >
              {showFullNik ? <EyeOff className="h-4 w-4 stroke-[1.8] text-slate-400" /> : <Eye className="h-4 w-4 stroke-[1.8] text-slate-400" />}
              <span>{showFullNik ? 'Samarkan NIK' : 'Lihat NIK Lengkap'}</span>
            </button>

            {search && (
              <button
                onClick={() => {
                  setSearch('');
                  setCurrentPage(1);
                }}
                className="rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition"
              >
                Reset
              </button>
            )}
          </div>
        </div>

        {/* Responsive Data Table */}
        <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white">
          <div className="overflow-x-auto relative min-h-[320px]">
            {loading && (
              <div className="absolute inset-0 z-10 flex items-center justify-center bg-white/75 backdrop-blur-xs">
                <div className="flex items-center gap-3 rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-slate-800 shadow-lg border border-slate-200">
                  <Loader2 className="h-5 w-5 animate-spin text-[#1E3A8A] stroke-[2]" />
                  <span>Memuat data kependudukan...</span>
                </div>
              </div>
            )}

            <table className="min-w-full divide-y divide-slate-200 text-left text-sm">
              <thead className="bg-slate-50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                <tr>
                  <th className="px-5 py-3.5">NIK Warga</th>
                  <th className="px-5 py-3.5">Nama Lengkap</th>
                  <th className="px-5 py-3.5">Tempat &amp; Tgl Lahir</th>
                  <th className="px-5 py-3.5">Jenis Kelamin</th>
                  <th className="px-5 py-3.5">Alamat / Dusun</th>
                  <th className="px-5 py-3.5 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {wargaList.length > 0 ? (
                  wargaList.map(w => {
                    const cleanTgl = w.tanggal_lahir ? w.tanggal_lahir.split(' ')[0] : '-';
                    return (
                      <tr key={w.id} className="hover:bg-slate-50/70 transition-colors">
                        <td className="px-5 py-4 font-mono text-xs font-semibold tabular-nums text-slate-900">
                          {showFullNik ? w.nik : maskIdentityNumber(w.nik)}
                        </td>
                        <td className="px-5 py-4 text-sm font-semibold text-slate-900">
                          {w.nama}
                        </td>
                        <td className="px-5 py-4 text-xs text-slate-600 font-medium">
                          {w.tempat_lahir ? `${w.tempat_lahir}, ` : ''}{cleanTgl}
                        </td>
                        <td className="px-5 py-4 text-xs text-slate-600 font-medium">
                          {w.jenis_kelamin || '-'}
                        </td>
                        <td className="px-5 py-4 text-slate-600 text-xs truncate max-w-xs">
                          {w.alamat || '-'} {w.rt ? `(RT ${w.rt}/RW ${w.rw})` : ''}
                        </td>
                        <td className="px-5 py-4 text-right">
                          <div className="flex justify-end items-center gap-1.5">
                            {/* Detail Button */}
                            <button
                              onClick={() => onNavigate(`warga_show_${w.id}`)}
                              className="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-800 hover:border-[#1E3A8A] hover:text-[#1E3A8A] transition"
                              title="Lihat Detail Data Warga"
                            >
                              <Eye className="h-3.5 w-3.5 stroke-[1.8] text-slate-400" />
                              <span>Detail</span>
                            </button>

                            {/* Buat Surat Button for this citizen */}
                            {user?.role === 'staff' && (
                              <button
                                onClick={() => onNavigate(`surat_create_for_${w.id}`)}
                                className="inline-flex items-center gap-1 rounded-lg border border-[#1E3A8A]/20 bg-[#1E3A8A]/10 px-3 py-1.5 text-xs font-semibold text-[#1E3A8A] hover:bg-[#1E3A8A] hover:text-white transition"
                                title="Buat Permohonan Surat untuk Warga ini"
                              >
                                <FilePlus className="h-3.5 w-3.5 stroke-[1.8] text-slate-400" />
                                <span className="hidden sm:inline">Surat</span>
                              </button>
                            )}

                            {/* Edit Button */}
                            {user?.role === 'staff' && (
                              <button
                                onClick={() => onNavigate(`warga_edit_${w.id}`)}
                                className="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
                                title="Ubah Data Warga"
                              >
                                <Edit2 className="h-3.5 w-3.5 stroke-[1.8] text-slate-400" />
                                <span>Edit</span>
                              </button>
                            )}

                            {/* Delete Button */}
                            {user?.role === 'staff' && (
                              <button
                                onClick={() => setDeleteTarget(w)}
                                className="inline-flex items-center p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition"
                                title="Hapus Data Warga"
                              >
                                <Trash2 className="h-4 w-4 stroke-[1.8]" />
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
                      {loading
                        ? 'Sedang mencari data di database...'
                        : search
                        ? `Tidak ada data warga dengan kata kunci "${search}".`
                        : 'Belum ada data warga terdaftar.'}
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>

        {/* Numbered Pagination with clean Navy Blue styling */}
        {totalCount > 0 && (
          <div className="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
            <span className="text-xs text-slate-500 font-medium">
              Menampilkan {((currentPage - 1) * itemsPerPage) + 1} &ndash;{' '}
              {Math.min(currentPage * itemsPerPage, totalCount)} dari{' '}
              {totalCount.toLocaleString('id-ID')} warga
            </span>

            <div className="flex items-center gap-1.5">
              <button
                disabled={currentPage <= 1 || loading}
                onClick={() => setCurrentPage(p => Math.max(1, p - 1))}
                className="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 disabled:opacity-40 transition"
                aria-label="Halaman Sebelumnya"
              >
                <ChevronLeft className="h-4 w-4 stroke-[1.8]" />
              </button>

              {Array.from({ length: Math.min(totalPages, 5) }, (_, i) => {
                let pageNumber = i + 1;
                if (totalPages > 5 && currentPage > 3) {
                  pageNumber = currentPage - 2 + i;
                  if (pageNumber > totalPages) pageNumber = totalPages - (4 - i);
                }
                return (
                  <button
                    key={pageNumber}
                    onClick={() => setCurrentPage(pageNumber)}
                    className={`flex h-8 w-8 items-center justify-center rounded-lg text-xs font-semibold transition ${
                      currentPage === pageNumber
                        ? 'border border-[#1E3A8A] bg-[#1E3A8A] text-white font-bold'
                        : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                    }`}
                  >
                    {pageNumber}
                  </button>
                );
              })}

              <button
                disabled={currentPage >= totalPages || loading}
                onClick={() => setCurrentPage(p => Math.min(totalPages, p + 1))}
                className="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 disabled:opacity-40 transition"
                aria-label="Halaman Berikutnya"
              >
                <ChevronRight className="h-4 w-4 stroke-[1.8]" />
              </button>
            </div>
          </div>
        )}
      </div>

      {/* Delete Confirmation Modal */}
      {deleteTarget && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-xs px-4">
          <div className="w-full max-w-md rounded-3xl bg-white p-6 sm:p-8 shadow-2xl space-y-4">
            <div className="flex items-center gap-3 text-rose-600">
              <div className="rounded-2xl bg-rose-50 p-3">
                <AlertTriangle className="h-6 w-6 stroke-[1.8]" />
              </div>
              <h3 className="text-lg font-bold text-slate-900">Hapus Data Warga?</h3>
            </div>
            <p className="text-sm text-slate-600 leading-relaxed">
              Data <strong className="text-slate-900">{deleteTarget.nama}</strong> (NIK: <span className="font-mono text-slate-900">{deleteTarget.nik}</span>) akan dihapus permanen dari basis data. Tindakan ini tidak dapat dibatalkan.
            </p>
            <div className="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
              <button
                type="button"
                onClick={() => setDeleteTarget(null)}
                className="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
              >
                Batal
              </button>
              <button
                id="btn-confirm-delete-warga"
                type="button"
                onClick={handleDelete}
                className="rounded-xl bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-700 transition shadow-xs"
              >
                Ya, Hapus
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
