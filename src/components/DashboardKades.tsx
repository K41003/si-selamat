import React from 'react';
import { StorageService } from '../services/storage';
import { Clock, CheckCircle2, XCircle, ArrowRight, ShieldCheck, ChevronRight } from 'lucide-react';

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
    <div className="space-y-6 sm:space-y-8">
      {/* Title & Primary Action */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
            Pengesahan &amp; Validasi Surat
          </h1>
        </div>

        {totalMenungguValidasi > 0 && (
          <button
            onClick={() => onNavigate('validasi')}
            className="inline-flex items-center gap-2 rounded-2xl bg-[#1E3A8A] hover:bg-[#172554] px-5 py-2.5 text-xs sm:text-sm font-semibold text-white transition active:scale-95 shadow-sm shrink-0"
          >
            <ShieldCheck className="h-4.5 w-4.5 stroke-[1.8]" />
            <span>Mulai Validasi ({totalMenungguValidasi})</span>
          </button>
        )}
      </div>

      {/* 3 Main Stat Cards */}
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
        {/* Metric 1 */}
        <div
          onClick={() => onNavigate('validasi')}
          className="group cursor-pointer rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 border border-slate-200 shadow-2xs hover:border-[#1E3A8A] transition-all duration-200"
        >
          <div className="flex items-center justify-between">
            <p className="text-xs font-semibold uppercase tracking-wider text-slate-500">Menunggu Tanda Tangan</p>
            <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 group-hover:text-[#1E3A8A] transition">
              <Clock className="h-5 w-5 stroke-[1.8]" />
            </div>
          </div>
          <p className="mt-4 text-3xl font-bold text-slate-900 tracking-tight tabular-nums">
            {totalMenungguValidasi}
          </p>
        </div>

        {/* Metric 2 */}
        <div
          onClick={() => onNavigate('arsip')}
          className="group cursor-pointer rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 border border-slate-200 shadow-2xs hover:border-[#1E3A8A] transition-all duration-200"
        >
          <div className="flex items-center justify-between">
            <p className="text-xs font-semibold uppercase tracking-wider text-slate-500">Disetujui Bulan Ini</p>
            <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 group-hover:text-[#1E3A8A] transition">
              <CheckCircle2 className="h-5 w-5 stroke-[1.8]" />
            </div>
          </div>
          <p className="mt-4 text-3xl font-bold text-slate-900 tracking-tight tabular-nums">
            {totalDisetujuiBulanIni}
          </p>
        </div>

        {/* Metric 3 */}
        <div
          onClick={() => onNavigate('arsip')}
          className="group cursor-pointer rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 border border-slate-200 shadow-2xs hover:border-[#1E3A8A] transition-all duration-200"
        >
          <div className="flex items-center justify-between">
            <p className="text-xs font-semibold uppercase tracking-wider text-slate-500">Ditolak Bulan Ini</p>
            <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 group-hover:text-[#1E3A8A] transition">
              <XCircle className="h-5 w-5 stroke-[1.8]" />
            </div>
          </div>
          <p className="mt-4 text-3xl font-bold text-slate-900 tracking-tight tabular-nums">
            {totalDitolakBulanIni}
          </p>
        </div>
      </div>

      {/* Pending Validation Action Box inside Clean White Card */}
      <div className="rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-8 shadow-2xs border border-slate-200 space-y-5">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-2 border-b border-slate-100">
          <div>
            <h2 className="text-lg font-bold text-slate-900">Antrean Surat Masuk</h2>
          </div>
          <button
            onClick={() => onNavigate('validasi')}
            className="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-800 hover:border-[#1E3A8A] hover:text-[#1E3A8A] transition"
          >
            <span>Buka Panel Validasi</span>
            <ChevronRight className="h-4 w-4 stroke-[1.8] text-slate-400" />
          </button>
        </div>

        {pendingSurat.length === 0 ? (
          <div className="rounded-2xl border border-dashed border-slate-200 py-12 text-center bg-slate-50/50">
            <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 mb-2.5">
              <CheckCircle2 className="h-6 w-6 stroke-[1.8]" />
            </div>
            <h3 className="text-sm font-bold text-slate-900">Semua Surat Telah Selesai Divalidasi</h3>
            <p className="text-xs text-slate-500 mt-1">Tidak ada permohonan surat tertunda saat ini.</p>
          </div>
        ) : (
          <div className="space-y-3">
            {pendingSurat.map(surat => {
              const warga = wargaList.find(w => w.id === surat.warga_id);
              const jenis = jenisList.find(j => j.id === surat.jenis_surat_id);

              return (
                <div
                  key={surat.id}
                  className="flex flex-col sm:flex-row sm:items-center justify-between p-4 sm:p-5 rounded-2xl bg-slate-50/80 border border-slate-200 hover:bg-slate-100/70 transition gap-4"
                >
                  <div className="space-y-1">
                    <div className="flex flex-wrap items-center gap-2">
                      <span className="font-mono text-xs font-semibold tabular-nums text-slate-900 bg-white px-2.5 py-0.5 rounded-lg border border-slate-200">
                        {surat.nomor_surat}
                      </span>
                      <span className="rounded-lg bg-[#1E3A8A]/10 border border-[#1E3A8A]/20 px-2.5 py-0.5 text-xs font-semibold text-[#1E3A8A]">
                        {jenis?.nama_jenis}
                      </span>
                    </div>
                    <p className="text-sm font-bold text-slate-900">
                      Pemohon: {warga?.nama || 'Warga'}
                    </p>
                    <p className="text-xs text-slate-500">
                      NIK: <span className="font-mono tabular-nums text-slate-700">{warga?.nik}</span> &bull; Diajukan: {surat.created_at}
                    </p>
                  </div>
                  <div className="flex items-center gap-2 shrink-0">
                    <button
                      onClick={() => onNavigate(`surat_detail_${surat.id}`)}
                      className="inline-flex items-center gap-1.5 rounded-xl bg-[#1E3A8A] px-4 py-2.5 text-xs font-semibold text-white hover:bg-[#172554] transition active:scale-95 shadow-sm"
                    >
                      <ShieldCheck className="h-4 w-4 stroke-[1.8]" />
                      <span>Periksa &amp; Tanda Tangan</span>
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
