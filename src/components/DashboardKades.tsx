import React from 'react';
import { StorageService } from '../services/storage';
import { Clock, CheckCircle2, XCircle, ArrowRight, ShieldCheck } from 'lucide-react';

interface DashboardKadesProps {
  onNavigate: (view: string) => void;
}

export const DashboardKades: React.FC<DashboardKadesProps> = ({ onNavigate }) => {
  const suratList = StorageService.getSuratList();
  const wargaList = StorageService.getWargaList();
  const jenisList = StorageService.getJenisSurat();

  const currentMonth = new Date().getMonth();
  const currentYear = new Date().getFullYear();

  const totalMenungguValidasi = suratList.filter(s => s.status === 'diajukan').length;
  const totalDisetujuiBulanIni = suratList.filter(s => {
    if (s.status !== 'disetujui') return false;
    const d = new Date(s.updated_at);
    return d.getMonth() === currentMonth && d.getFullYear() === currentYear;
  }).length;

  const totalDitolakBulanIni = suratList.filter(s => {
    if (s.status !== 'ditolak') return false;
    const d = new Date(s.updated_at);
    return d.getMonth() === currentMonth && d.getFullYear() === currentYear;
  }).length;

  const pendingSurat = suratList.filter(s => s.status === 'diajukan');

  return (
    <div className="space-y-6">
      <div className="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-xl font-bold text-slate-900">Dashboard Kepala Desa</h1>
          <p className="text-sm text-slate-500">Ringkasan validasi surat dan pengesahan tanda tangan elektronik (E-Signature).</p>
        </div>
        <button
          onClick={() => onNavigate('validasi')}
          className="flex items-center gap-1.5 rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white hover:bg-slate-800 transition shadow-xs"
        >
          <ShieldCheck className="h-4 w-4 text-emerald-400" />
          Proses Validasi ({totalMenungguValidasi})
        </button>
      </div>

      {/* 3 Main Stat Cards from kades.blade.php */}
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div
          onClick={() => onNavigate('validasi')}
          className="cursor-pointer rounded-xl bg-white p-5 shadow-xs ring-1 ring-slate-200 hover:ring-slate-300 transition"
        >
          <div className="flex items-center justify-between">
            <p className="text-sm font-medium text-slate-500">Menunggu Validasi</p>
            <div className="rounded-lg bg-blue-50 p-2 text-blue-600">
              <Clock className="h-5 w-5" />
            </div>
          </div>
          <p className="mt-2 text-3xl font-bold text-blue-600">{totalMenungguValidasi}</p>
          <p className="mt-1 text-xs text-slate-400">Memerlukan tanda tangan Kepala Desa</p>
        </div>

        <div
          onClick={() => onNavigate('arsip')}
          className="cursor-pointer rounded-xl bg-white p-5 shadow-xs ring-1 ring-slate-200 hover:ring-slate-300 transition"
        >
          <div className="flex items-center justify-between">
            <p className="text-sm font-medium text-slate-500">Disetujui Bulan Ini</p>
            <div className="rounded-lg bg-emerald-50 p-2 text-emerald-600">
              <CheckCircle2 className="h-5 w-5" />
            </div>
          </div>
          <p className="mt-2 text-3xl font-bold text-emerald-600">{totalDisetujuiBulanIni}</p>
          <p className="mt-1 text-xs text-slate-400">Telah ditandatangani E-Signature QR</p>
        </div>

        <div
          onClick={() => onNavigate('arsip')}
          className="cursor-pointer rounded-xl bg-white p-5 shadow-xs ring-1 ring-slate-200 hover:ring-slate-300 transition"
        >
          <div className="flex items-center justify-between">
            <p className="text-sm font-medium text-slate-500">Ditolak Bulan Ini</p>
            <div className="rounded-lg bg-red-50 p-2 text-red-600">
              <XCircle className="h-5 w-5" />
            </div>
          </div>
          <p className="mt-2 text-3xl font-bold text-red-600">{totalDitolakBulanIni}</p>
          <p className="mt-1 text-xs text-slate-400">Surat dengan berkas tidak lengkap</p>
        </div>
      </div>

      {/* Pending Validation Action Box */}
      <div className="rounded-xl bg-white p-5 shadow-xs ring-1 ring-slate-200">
        <div className="mb-4 flex items-center justify-between">
          <div>
            <h2 className="text-base font-semibold text-slate-900">Antrean Validasi Surat Masuk</h2>
            <p className="text-xs text-slate-500">Permohonan surat warga yang telah diperiksa staff dan siap disahkan.</p>
          </div>
          <button
            onClick={() => onNavigate('validasi')}
            className="flex items-center gap-1 text-xs font-semibold text-slate-700 hover:text-slate-900"
          >
            Buka Panel Validasi <ArrowRight className="h-3 w-3" />
          </button>
        </div>

        {pendingSurat.length === 0 ? (
          <div className="rounded-lg border border-dashed border-slate-200 py-8 text-center text-sm text-slate-400">
            Tidak ada permohonan surat yang menunggu validasi saat ini.
          </div>
        ) : (
          <div className="divide-y divide-slate-100">
            {pendingSurat.map(surat => {
              const warga = wargaList.find(w => w.id === surat.warga_id);
              const jenis = jenisList.find(j => j.id === surat.jenis_surat_id);

              return (
                <div key={surat.id} className="flex flex-col sm:flex-row sm:items-center justify-between py-3.5 gap-3">
                  <div>
                    <div className="flex items-center gap-2">
                      <span className="font-mono text-xs font-semibold text-slate-700">{surat.nomor_surat}</span>
                      <span className="rounded bg-blue-50 px-2 py-0.5 text-[11px] font-medium text-blue-700 ring-1 ring-blue-500/20">
                        {jenis?.nama_jenis}
                      </span>
                    </div>
                    <p className="mt-1 text-sm font-medium text-slate-900">
                      Pemohon: {warga?.nama} <span className="text-slate-400 font-mono text-xs">(NIK: {warga?.nik})</span>
                    </p>
                    <p className="text-xs text-slate-500">Diajukan pada: {surat.created_at}</p>
                  </div>
                  <div className="flex items-center gap-2">
                    <button
                      onClick={() => onNavigate(`surat_detail_${surat.id}`)}
                      className="rounded-lg bg-slate-900 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-slate-800 transition"
                    >
                      Periksa &amp; Validasi
                    </button>
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </div>
    </div>
  );
};
