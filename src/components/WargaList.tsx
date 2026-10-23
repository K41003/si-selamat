import React, { useState, useEffect, useCallback } from 'react';
import { ApiService } from '../services/api';
import { StorageService } from '../services/storage';
import { useAuth } from '../context/AuthContext';
import { maskIdentityNumber } from '../utils';
import { Warga } from '../types';
import { Search, Plus, Eye, EyeOff, Edit2, Trash2, AlertTriangle, X, Loader2, Database } from 'lucide-react';

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
    
    // Call API delete and local storage delete
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
    <div className="space-y-4">
      {/* Toast Alert */}
      {toastMessage && (
        <div
          className={`flex items-center justify-between rounded-lg px-4 py-3 text-sm font-medium ${
            toastMessage.type === 'success'
              ? 'bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200'
              : 'bg-red-50 text-red-800 ring-1 ring-red-200'
          }`}
        >
          <span>{toastMessage.text}</span>
          <button onClick={() => setToastMessage(null)} className="text-slate-400 hover:text-slate-600">
            <X className="h-4 w-4" />
          </button>
        </div>
      )}

      {/* Header */}
      <div className="flex flex-wrap items-center justify-between gap-3">
        <div>
          <div className="flex items-center gap-2">
            <h1 className="text-xl font-bold text-slate-900">Kelola Data Warga</h1>
            {isFromDatabase && (
              <span className="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700 ring-1 ring-emerald-600/20">
                <Database className="h-3 w-3" />
                Live MySQL Database
              </span>
            )}
          </div>
          <p className="text-sm text-slate-500">
            Total {totalCount.toLocaleString('id-ID')} warga terdaftar di sistem.
          </p>
        </div>
        {user?.role === 'staff' && (
          <button
            id="btn-add-warga"
            onClick={() => onNavigate('warga_create')}
            className="flex items-center gap-1.5 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition shadow-xs"
          >
            <Plus className="h-4 w-4" />
            Tambah Data Warga
          </button>
        )}
      </div>

      {/* Search Bar */}
      <div className="rounded-xl bg-white p-4 shadow-xs ring-1 ring-slate-200">
        <div className="flex flex-wrap gap-2">
          <div className="relative flex-1 max-w-md">
            <Search className="absolute left-3 top-2.5 h-4 w-4 text-slate-400" />
            <input
              type="text"
              value={search}
              onChange={e => {
                setSearch(e.target.value);
                setCurrentPage(1);
              }}
              placeholder="Cari nama, NIK, No. KK, atau Dusun..."
              className="w-full rounded-lg border border-slate-300 pl-9 pr-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
            />
          </div>
          {search && (
            <button
              onClick={() => {
                setSearch('');
                setCurrentPage(1);
              }}
              className="rounded-lg px-3 py-2 text-sm font-medium text-slate-500 hover:text-slate-800"
            >
              Reset
            </button>
          )}
        </div>
      </div>

      {/* Data Table */}
      <div className="overflow-hidden rounded-xl bg-white shadow-xs ring-1 ring-slate-200">
        <div className="overflow-x-auto relative">
          {loading && (
            <div className="absolute inset-0 z-10 flex items-center justify-center bg-white/60 backdrop-blur-[1px]">
              <div className="flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-md ring-1 ring-slate-200">
                <Loader2 className="h-4 w-4 animate-spin text-emerald-600" />
                Memuat data warga...
              </div>
            </div>
          )}

          <table className="min-w-full divide-y divide-slate-200 text-sm">
            <thead className="bg-slate-50 text-xs font-semibold text-slate-600 uppercase">
              <tr>
                <th className="px-4 py-3 text-left">
                  <div className="flex items-center gap-1.5">
                    <span>NIK</span>
                    <button
                      type="button"
                      onClick={() => setShowFullNik(!showFullNik)}
                      className="inline-flex items-center text-[10px] lowercase px-1.5 py-0.5 rounded bg-slate-200/70 text-slate-700 hover:bg-slate-300 transition"
                      title={showFullNik ? "Samarkan NIK (Privasi)" : "Tampilkan NIK Lengkap"}
                    >
                      {showFullNik ? <EyeOff className="h-3 w-3 mr-0.5" /> : <Eye className="h-3 w-3 mr-0.5" />}
                      {showFullNik ? 'hide' : 'show'}
                    </button>
                  </div>
                </th>
                <th className="px-4 py-3 text-left">Nama</th>
                <th className="px-4 py-3 text-left">Jenis Kelamin</th>
                <th className="px-4 py-3 text-left">Pekerjaan</th>
                <th className="px-4 py-3 text-left">Alamat / Dusun</th>
                <th className="px-4 py-3 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {wargaList.length > 0 ? (
                wargaList.map(w => (
                  <tr key={w.id} className="hover:bg-slate-50 transition-colors">
                    <td className="px-4 py-3 font-mono text-xs font-medium text-slate-700">
                      {showFullNik ? w.nik : maskIdentityNumber(w.nik)}
                    </td>
                    <td className="px-4 py-3 font-semibold text-slate-900">{w.nama}</td>
                    <td className="px-4 py-3 text-slate-600">{w.jenis_kelamin || '-'}</td>
                    <td className="px-4 py-3 text-slate-600 text-xs">{w.pekerjaan || '-'}</td>
                    <td className="px-4 py-3 text-slate-600 truncate max-w-xs text-xs">
                      {w.alamat || '-'} {w.rt ? `(RT ${w.rt}/RW ${w.rw})` : ''}
                    </td>
                    <td className="px-4 py-3 text-right">
                      <div className="flex justify-end gap-1.5">
                        <button
                          onClick={() => onNavigate(`warga_show_${w.id}`)}
                          className="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium text-slate-700 hover:bg-slate-100"
                          title="Detail"
                        >
                          <Eye className="h-3.5 w-3.5" />
                          Detail
                        </button>
                        {user?.role === 'staff' && (
                          <>
                            <button
                              onClick={() => onNavigate(`warga_edit_${w.id}`)}
                              className="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium text-blue-600 hover:bg-blue-50"
                              title="Update"
                            >
                              <Edit2 className="h-3.5 w-3.5" />
                              Update
                            </button>
                            <button
                              onClick={() => setDeleteTarget(w)}
                              className="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50"
                              title="Hapus"
                            >
                              <Trash2 className="h-3.5 w-3.5" />
                              Hapus
                            </button>
                          </>
                        )}
                      </div>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={6} className="px-4 py-12 text-center text-sm text-slate-400">
                    {loading
                      ? 'Sedang memuat data dari database...'
                      : search
                      ? `Tidak ada data warga yang cocok dengan pencarian "${search}".`
                      : 'Belum ada data warga terdaftar.'}
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>

        {/* Smart Pagination */}
        {totalCount > 0 && (
          <div className="flex flex-wrap items-center justify-between border-t border-slate-200 px-4 py-3 text-xs text-slate-500 gap-2">
            <span>
              Menampilkan {((currentPage - 1) * itemsPerPage) + 1} -{' '}
              {Math.min(currentPage * itemsPerPage, totalCount)} dari {totalCount.toLocaleString('id-ID')} warga
            </span>
            <div className="flex items-center gap-1">
              <button
                disabled={currentPage <= 1 || loading}
                onClick={() => setCurrentPage(p => Math.max(1, p - 1))}
                className="rounded border border-slate-200 px-2.5 py-1 disabled:opacity-40 hover:bg-slate-50 font-medium"
              >
                Sebelumnya
              </button>
              
              <span className="px-2 py-1 font-semibold text-slate-700">
                Halaman {currentPage} dari {totalPages}
              </span>

              <button
                disabled={currentPage >= totalPages || loading}
                onClick={() => setCurrentPage(p => Math.min(totalPages, p + 1))}
                className="rounded border border-slate-200 px-2.5 py-1 disabled:opacity-40 hover:bg-slate-50 font-medium"
              >
                Berikutnya
              </button>
            </div>
          </div>
        )}
      </div>

      {/* Delete Confirmation Modal */}
      {deleteTarget && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs px-4">
          <div className="w-full max-w-sm rounded-xl bg-white p-6 shadow-xl ring-1 ring-slate-200">
            <div className="flex items-center gap-3 text-red-600">
              <div className="flex h-10 w-10 items-center justify-center rounded-full bg-red-50">
                <AlertTriangle className="h-5 w-5" />
              </div>
              <h3 className="text-base font-semibold text-slate-900">Hapus Data Warga?</h3>
            </div>
            <p className="mt-3 text-sm text-slate-600 leading-relaxed">
              Data <strong className="text-slate-900">{deleteTarget.nama}</strong> (NIK: <span className="font-mono">{deleteTarget.nik}</span>) akan dihapus permanen dari database. Tindakan ini tidak dapat dibatalkan.
            </p>
            <div className="mt-6 flex justify-end gap-2">
              <button
                type="button"
                onClick={() => setDeleteTarget(null)}
                className="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
              >
                Batal
              </button>
              <button
                id="btn-confirm-delete-warga"
                type="button"
                onClick={handleDelete}
                className="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition"
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
