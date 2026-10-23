import React from 'react';
import { StorageService } from '../services/storage';
import { useAuth } from '../context/AuthContext';
import { ArrowLeft, Edit2, FileText, UserCheck, Plus } from 'lucide-react';

interface WargaDetailProps {
  wargaId: number;
  onNavigate: (view: string) => void;
}

export const WargaDetail: React.FC<WargaDetailProps> = ({ wargaId, onNavigate }) => {
  const { user } = useAuth();
  const wargaList = StorageService.getWargaList();
  const suratList = StorageService.getSuratList();
  const jenisList = StorageService.getJenisSurat();

  const warga = wargaList.find(w => w.id === wargaId);
  const riwayatSurat = suratList.filter(s => s.warga_id === wargaId);

  if (!warga) {
    return (
      <div className="rounded-xl bg-white p-8 text-center shadow-xs ring-1 ring-slate-200">
        <p className="text-slate-500">Data warga tidak ditemukan.</p>
        <button
          onClick={() => onNavigate('warga')}
          className="mt-4 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
        >
          Kembali ke Daftar
        </button>
      </div>
    );
  }

  return (
    <div className="mx-auto max-w-4xl space-y-6">
      <div className="flex flex-wrap items-center justify-between gap-3">
        <div className="flex items-center gap-3">
          <button
            onClick={() => onNavigate('warga')}
            className="rounded-lg border border-slate-200 bg-white p-2 text-slate-600 hover:bg-slate-50 transition"
          >
            <ArrowLeft className="h-4 w-4" />
          </button>
          <div>
            <h1 className="text-xl font-bold text-slate-900">Detail Data Warga</h1>
            <p className="text-sm text-slate-500">Informasi lengkap data kependudukan dan histori permohonan surat.</p>
          </div>
        </div>

        {user?.role === 'staff' && (
          <div className="flex items-center gap-2">
            <button
              onClick={() => onNavigate(`surat_create_for_${warga.id}`)}
              className="flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50"
            >
              <Plus className="h-3.5 w-3.5" />
              Buat Surat
            </button>
            <button
              onClick={() => onNavigate(`warga_edit_${warga.id}`)}
              className="flex items-center gap-1.5 rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white hover:bg-slate-800"
            >
              <Edit2 className="h-3.5 w-3.5" />
              Update Data
            </button>
          </div>
        )}
      </div>

      {/* Main Info Card */}
      <div className="rounded-xl bg-white p-6 shadow-xs ring-1 ring-slate-200">
        <div className="mb-4 flex items-center gap-3 border-b border-slate-100 pb-4">
          <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-700 font-bold text-lg">
            {warga.nama.charAt(0)}
          </div>
          <div>
            <h2 className="text-lg font-bold text-slate-900">{warga.nama}</h2>
            <p className="font-mono text-xs text-slate-500">NIK: {warga.nik}</p>
          </div>
        </div>

        <dl className="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 text-sm">
          <div>
            <dt className="text-xs font-medium uppercase tracking-wide text-slate-400">NIK</dt>
            <dd className="mt-1 font-mono font-medium text-slate-900">{warga.nik}</dd>
          </div>
          <div>
            <dt className="text-xs font-medium uppercase tracking-wide text-slate-400">Nomor Kartu Keluarga (KK)</dt>
            <dd className="mt-1 font-mono font-medium text-slate-900">{warga.no_kk || '-'}</dd>
          </div>
          <div>
            <dt className="text-xs font-medium uppercase tracking-wide text-slate-400">Tempat, Tanggal Lahir</dt>
            <dd className="mt-1 font-medium text-slate-900">
              {warga.tempat_lahir || '-'}, {warga.tanggal_lahir || '-'}
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
      <div className="rounded-xl bg-white p-6 shadow-xs ring-1 ring-slate-200">
        <div className="mb-4 flex items-center justify-between">
          <div className="flex items-center gap-2">
            <FileText className="h-5 w-5 text-slate-500" />
            <h3 className="text-base font-semibold text-slate-900">Riwayat Permohonan Surat</h3>
          </div>
          <span className="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">
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
                    <p className="font-mono text-xs text-slate-500">{surat.nomor_surat} &bull; {surat.created_at}</p>
                  </div>
                  <div className="flex items-center gap-3">
                    <span className={`rounded-full px-2.5 py-0.5 text-xs font-medium capitalize ${
                      surat.status === 'disetujui' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' :
                      surat.status === 'diajukan' ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-600/20' :
                      surat.status === 'ditolak' ? 'bg-red-50 text-red-700 ring-1 ring-red-600/20' :
                      'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20'
                    }`}>
                      {surat.status}
                    </span>
                    <button
                      onClick={() => onNavigate(`surat_detail_${surat.id}`)}
                      className="text-xs font-semibold text-slate-700 hover:text-slate-900"
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
