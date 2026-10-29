import React from 'react';
import { StorageService } from '../services/storage';
import { useAuth } from '../context/AuthContext';
import { ArrowLeft, Edit2, FileText, UserCheck, Plus } from 'lucide-react';

import { ApiService } from '../services/api';
import { formatTempatTanggalLahir } from '../utils';
import { Warga } from '../types';

interface WargaDetailProps {
  wargaId: number;
  onNavigate: (view: string) => void;
}

export const WargaDetail: React.FC<WargaDetailProps> = ({ wargaId, onNavigate }) => {
  const { user } = useAuth();
  const wargaList = StorageService.getWargaList();
  const suratList = StorageService.getSuratList();
  const jenisList = StorageService.getJenisSurat();

  const [warga, setWarga] = React.useState<Warga | null>(() => {
    return wargaList.find(w => w.id === wargaId) || null;
  });

  React.useEffect(() => {
    if (!warga) {
      ApiService.getWargaById(wargaId).then(res => {
        if (res) setWarga(res);
      });
    }
  }, [wargaId, warga]);

  const riwayatSurat = suratList.filter(s => s.warga_id === wargaId);

  if (!warga) {
    return (
      <div className="rounded-xl bg-white p-8 text-center shadow-xs ring-1 ring-slate-200">
        <p className="text-slate-500">Data warga tidak ditemukan.</p>
        <button
          onClick={() => onNavigate('warga')}
          className="mt-4 rounded-xl bg-[#1E3A8A] px-4 py-2 text-sm font-semibold text-white hover:bg-[#172554] transition"
        >
          Kembali ke Daftar
        </button>
      </div>
    );
  }

  return (
    <div className="mx-auto max-w-4xl space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div className="flex items-center gap-3.5">
          <button
            onClick={() => onNavigate('warga')}
            className="rounded-xl border border-slate-200 bg-white p-2.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition shadow-2xs"
            title="Kembali ke Daftar Warga"
          >
            <ArrowLeft className="h-5 w-5" />
          </button>
          <div>
            <h1 className="text-2xl font-bold text-slate-900">Detail Data Kependudukan</h1>
          </div>
        </div>

        {user?.role === 'staff' && (
          <div className="flex items-center gap-2">
            <button
              onClick={() => onNavigate(`surat_create_for_${warga.id}`)}
              className="inline-flex items-center gap-1.5 rounded-xl bg-[#1E3A8A] px-4 py-2 text-xs font-semibold text-white hover:bg-[#172554] transition active:scale-95 shadow-sm"
            >
              <Plus className="h-4 w-4 stroke-[2]" />
              <span>Buat Surat Warga Ini</span>
            </button>
            <button
              onClick={() => onNavigate(`warga_edit_${warga.id}`)}
              className="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs"
            >
              <Edit2 className="h-3.5 w-3.5 text-slate-400" />
              <span>Edit Data</span>
            </button>
          </div>
        )}
      </div>

      {/* Main Info Card */}
      <div className="rounded-2xl sm:rounded-3xl bg-white p-6 sm:p-8 shadow-2xs border border-slate-200">
        <div className="mb-6 flex items-center gap-4 border-b border-slate-100 pb-5">
          <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#1E3A8A]/10 text-[#1E3A8A] border border-[#1E3A8A]/20 font-bold text-lg">
            {warga.nama.charAt(0)}
          </div>
          <div>
            <h2 className="text-xl font-bold text-slate-900">{warga.nama}</h2>
            <p className="font-mono text-xs font-semibold text-slate-800 bg-slate-100 px-2.5 py-0.5 rounded-md inline-block mt-1 border border-slate-200 tabular-nums">
              NIK: {warga.nik}
            </p>
          </div>
        </div>

        <dl className="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2 text-sm">
          <div className="bg-slate-50/70 p-3.5 rounded-lg border border-slate-200">
            <dt className="text-[11px] font-semibold uppercase tracking-wider text-slate-500">NIK (Nomor Induk Kependudukan)</dt>
            <dd className="mt-1 font-mono text-sm font-bold text-slate-900 tabular-nums">{warga.nik}</dd>
          </div>
          <div className="bg-slate-50/70 p-3.5 rounded-lg border border-slate-200">
            <dt className="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Nomor Kartu Keluarga (KK)</dt>
            <dd className="mt-1 font-mono text-sm font-bold text-slate-900 tabular-nums">{warga.no_kk || '-'}</dd>
          </div>
          <div className="bg-slate-50/70 p-3.5 rounded-lg border border-slate-200">
            <dt className="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Tempat, Tanggal Lahir</dt>
            <dd className="mt-1 text-sm font-bold text-slate-900">
              {formatTempatTanggalLahir(warga.tempat_lahir, warga.tanggal_lahir)}
            </dd>
          </div>
          <div>
            <dt className="text-xs font-medium uppercase tracking-wide text-slate-400">Jenis Kelamin</dt>
            <dd className="mt-1 font-medium text-slate-900">{warga.jenis_kelamin || '-'}</dd>
          </div>
          <div>
            <dt className="text-xs font-medium uppercase tracking-wide text-slate-400">Agama</dt>
            <dd className="mt-1 font-medium text-slate-900">{warga.agama || '-'}</dd>
          </div>
          <div>
            <dt className="text-xs font-medium uppercase tracking-wide text-slate-400">Pekerjaan</dt>
            <dd className="mt-1 font-medium text-slate-900">{warga.pekerjaan || '-'}</dd>
          </div>
          <div>
            <dt className="text-xs font-medium uppercase tracking-wide text-slate-400">Status Perkawinan</dt>
            <dd className="mt-1 font-medium text-slate-900">{warga.status_perkawinan || '-'}</dd>
          </div>
          <div>
            <dt className="text-xs font-medium uppercase tracking-wide text-slate-400">Nomor HP</dt>
            <dd className="mt-1 font-medium text-slate-900">{warga.no_hp || '-'}</dd>
          </div>
          <div className="sm:col-span-2">
            <dt className="text-xs font-medium uppercase tracking-wide text-slate-400">Alamat Lengkap</dt>
            <dd className="mt-1 font-medium text-slate-900">
              {warga.alamat || '-'}
              {(warga.rt || warga.rw) && ` (RT ${warga.rt || '-'} / RW ${warga.rw || '-'})`}
            </dd>
          </div>
        </dl>
      </div>

      {/* Riwayat Permohonan Surat */}
      <div className="rounded-xl bg-white p-5 border border-slate-200">
        <div className="mb-4 flex items-center justify-between">
          <div className="flex items-center gap-2">
            <FileText className="h-4 w-4 text-slate-500" />
            <h3 className="text-sm font-semibold text-slate-900">Riwayat Permohonan Surat</h3>
          </div>
          <span className="rounded bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
            {riwayatSurat.length} Berkas
          </span>
        </div>

        {riwayatSurat.length === 0 ? (
          <p className="py-4 text-center text-xs text-slate-400">
            Warga ini belum memiliki riwayat permohonan surat administrasi.
          </p>
        ) : (
          <div className="divide-y divide-slate-100">
            {riwayatSurat.map(surat => {
              const jenis = jenisList.find(j => j.id === surat.jenis_surat_id);
              return (
                <div key={surat.id} className="flex items-center justify-between py-3">
                  <div>
                    <p className="font-semibold text-slate-900 text-sm">{jenis?.nama_jenis || 'Surat'}</p>
                    <p className="font-mono text-xs text-slate-500 tabular-nums">{surat.nomor_surat} &bull; {surat.created_at}</p>
                  </div>
                  <div className="flex items-center gap-2">
                    <span className={`rounded-md px-2 py-0.5 text-[11px] font-semibold uppercase ${
                      surat.status === 'disetujui' ? 'bg-[#1E3A8A]/10 text-[#1E3A8A] border border-[#1E3A8A]/20' :
                      surat.status === 'diajukan' ? 'bg-sky-50 text-sky-800 border border-sky-200' :
                      surat.status === 'ditolak' ? 'bg-rose-50 text-rose-800 border border-rose-200' :
                      'bg-amber-50 text-amber-800 border border-amber-200'
                    }`}>
                      {surat.status}
                    </span>
                    <button
                      onClick={() => onNavigate(`surat_detail_${surat.id}`)}
                      className="text-xs font-semibold text-slate-700 hover:text-[#1E3A8A]"
                    >
                      Buka &rarr;
                    </button>
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </div>

      <div>
        <button
          onClick={() => onNavigate('warga')}
          className="text-sm font-medium text-slate-600 hover:text-slate-900"
        >
          &larr; Kembali ke daftar warga
        </button>
      </div>
    </div>
  );
};
