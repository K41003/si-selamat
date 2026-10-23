import React, { useState } from 'react';
import { StorageService } from '../services/storage';
import { useAuth } from '../context/AuthContext';
import { maskIdentityNumber } from '../utils';
import { Warga } from '../types';
import { Search, Plus, Eye, EyeOff, Edit2, Trash2, AlertTriangle, X, ShieldCheck } from 'lucide-react';

interface WargaListProps {
  onNavigate: (view: string) => void;
}

export const WargaList: React.FC<WargaListProps> = ({ onNavigate }) => {
  const { user } = useAuth();
  const [wargaList, setWargaList] = useState<Warga[]>(() => StorageService.getWargaList());
  const [search, setSearch] = useState('');
  const [deleteTarget, setDeleteTarget] = useState<Warga | null>(null);
  const [toastMessage, setToastMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [showFullNik, setShowFullNik] = useState(false);
  const itemsPerPage = 8;

  const filtered = wargaList.filter(w => {
    if (!search.trim()) return true;
    const q = search.toLowerCase();
    return w.nama.toLowerCase().includes(q) || w.nik.toLowerCase().includes(q);
  });

  const totalPages = Math.ceil(filtered.length / itemsPerPage) || 1;
  const paginated = filtered.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage);

  const handleDelete = () => {
    if (!deleteTarget || !user) return;
    const result = StorageService.deleteWarga(deleteTarget.id, user.name, user.role);
    if (!result.success) {
      setToastMessage({ type: 'error', text: result.message || 'Gagal menghapus data warga.' });
    } else {
      setToastMessage({ type: 'success', text: 'Data warga berhasil dihapus.' });
      setWargaList(StorageService.getWargaList());
    }
    setDeleteTarget(null);
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
          <h1 className="text-xl font-bold text-slate-900">Kelola Data Warga</h1>
          <p className="text-sm text-slate-500">Total {filtered.length} warga terdaftar di sistem.</p>
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
              placeholder="Cari nama atau NIK..."
              className="w-full rounded-lg border border-slate-300 pl-9 pr-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
            />
          </div>
          {search && (
            <button
              onClick={() => setSearch('')}
              className="rounded-lg px-3 py-2 text-sm font-medium text-slate-500 hover:text-slate-800"
            >
              Reset
            </button>
          )}
        </div>
      </div>

      {/* Data Table */}
      <div className="overflow-hidden rounded-xl bg-white shadow-xs ring-1 ring-slate-200">
        <div className="overflow-x-auto">
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
                <th className="px-4 py-3 text-left">Alamat</th>
                <th className="px-4 py-3 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {paginated.length > 0 ? (
                paginated.map(w => (
                  <tr key={w.id} className="hover:bg-slate-50">
                    <td className="px-4 py-3 font-mono text-xs font-medium text-slate-700">
                      {showFullNik ? w.nik : maskIdentityNumber(w.nik)}
                    </td>
                    <td className="px-4 py-3 font-semibold text-slate-900">{w.nama}</td>
                    <td className="px-4 py-3 text-slate-600">{w.jenis_kelamin || '-'}</td>
                    <td className="px-4 py-3 text-slate-600 truncate max-w-xs">
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
                  <td colSpan={5} className="px-4 py-12 text-center text-sm text-slate-400">
                    {search
                      ? `Tidak ada data warga yang cocok dengan pencarian "${search}".`
                      : 'Belum ada data warga. Klik "Tambah Data Warga" untuk mulai.'}
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>

        {/* Pagination */}
        {totalPages > 1 && (
          <div className="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-xs text-slate-500">
            <span>
              Menampilkan {Math.min((currentPage - 1) * itemsPerPage + 1, filtered.length)} -{' '}
              {Math.min(currentPage * itemsPerPage, filtered.length)} dari {filtered.length} warga
            </span>
            <div className="flex gap-1">
              <button
                disabled={currentPage === 1}
                onClick={() => setCurrentPage(p => Math.max(1, p - 1))}
                className="rounded border border-slate-200 px-2.5 py-1 disabled:opacity-40 hover:bg-slate-50"
              >
                Sebelumnya
              </button>
              {Array.from({ length: totalPages }, (_, i) => i + 1).map(p => (
                <button
                  key={p}
                  onClick={() => setCurrentPage(p)}
                  className={`rounded px-2.5 py-1 ${
                    p === currentPage ? 'bg-slate-900 text-white' : 'border border-slate-200 hover:bg-slate-50'
                  }`}
                >
                  {p}
                </button>
              ))}
              <button
                disabled={currentPage === totalPages}
                onClick={() => setCurrentPage(p => Math.min(totalPages, p + 1))}
                className="rounded border border-slate-200 px-2.5 py-1 disabled:opacity-40 hover:bg-slate-50"
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
          <div className="w-full max-w-sm rounded-xl bg-white p-6 shadow-xl ring-1 ring-slate-200 animate-in fade-in zoom-in-95 duration-150">
            <div className="flex items-center gap-3 text-red-600">
              <div className="flex h-10 w-10 items-center justify-center rounded-full bg-red-50">
                <AlertTriangle className="h-5 w-5" />
              </div>
              <h3 className="text-base font-semibold text-slate-900">Hapus Data Warga?</h3>
            </div>
            <p className="mt-3 text-sm text-slate-600 leading-relaxed">
              Data <strong className="text-slate-900">{deleteTarget.nama}</strong> (NIK: <span className="font-mono">{deleteTarget.nik}</span>) akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
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
