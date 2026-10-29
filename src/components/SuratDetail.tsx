import React, { useState } from 'react';
import { StorageService } from '../services/storage';
import { useAuth } from '../context/AuthContext';
import { formatTempatTanggalLahir } from '../utils';
import { Surat } from '../types';
import { ArrowLeft, CheckCircle, XCircle, Printer, Send, QrCode, ShieldCheck, FileText, User, Edit, Trash2 } from 'lucide-react';

interface SuratDetailProps {
  suratId: number;
  onNavigate: (view: string) => void;
}

export const SuratDetail: React.FC<SuratDetailProps> = ({ suratId, onNavigate }) => {
  const { user } = useAuth();
  const [surat, setSurat] = useState<Surat | undefined>(() => {
    return StorageService.getSuratList().find(s => s.id === suratId);
  });
  const [rejectModalOpen, setRejectModalOpen] = useState(false);
  const [rejectReason, setRejectReason] = useState('');
  const [approveNotes, setApproveNotes] = useState('Disetujui. Berkas administrasi lengkap & valid.');

  const wargaList = StorageService.getWargaList();
  const jenisList = StorageService.getJenisSurat();

  if (!surat) {
    return (
      <div className="rounded-xl bg-white p-8 text-center shadow-xs ring-1 ring-slate-200">
        <p className="text-slate-500">Berkas surat tidak ditemukan.</p>
        <button
          onClick={() => onNavigate('surat')}
          className="mt-4 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
        >
          Kembali ke Daftar Surat
        </button>
      </div>
    );
  }

  const warga = wargaList.find(w => w.id === surat.warga_id);
  const jenis = jenisList.find(j => j.id === surat.jenis_surat_id);

  const handleAjukan = () => {
    if (!user) return;
    const updated = StorageService.updateSuratStatus(surat.id, 'diajukan', user);
    if (updated) setSurat(updated);
  };

  const handleDeleteDraft = () => {
    if (!user) return;
    if (window.confirm(`Hapus draft surat nomor ${surat.nomor_surat}?`)) {
      StorageService.deleteSurat(surat.id, user);
      onNavigate('surat_drafts');
    }
  };

  const handleApprove = () => {
    if (!user) return;
    const updated = StorageService.updateSuratStatus(surat.id, 'disetujui', user, approveNotes);
    if (updated) setSurat(updated);
  };

  const handleReject = () => {
    if (!user || !rejectReason.trim()) return;
    const updated = StorageService.updateSuratStatus(surat.id, 'ditolak', user, rejectReason);
    if (updated) setSurat(updated);
    setRejectModalOpen(false);
  };

  return (
    <div className="mx-auto max-w-4xl space-y-6">
      {/* Header */}
      <div className="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-200">
        <div className="flex items-center gap-3">
          <button
            onClick={() => onNavigate('surat')}
            className="rounded-lg border border-slate-200 bg-white p-2 text-slate-600 hover:bg-slate-50 transition"
          >
            <ArrowLeft className="h-4 w-4" />
          </button>
          <div>
            <div className="flex items-center gap-2.5">
              <h1 className="text-xl font-bold font-mono tracking-tight text-slate-900">{surat.nomor_surat}</h1>
              <span className={`inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-semibold rounded-md border ${
                surat.status === 'disetujui' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' :
                surat.status === 'diajukan' ? 'bg-[#1E3A8A]/10 text-[#1E3A8A] border-[#1E3A8A]/20' :
                surat.status === 'ditolak' ? 'bg-rose-50 text-rose-800 border-rose-200' :
                'bg-slate-100 text-slate-700 border-slate-200'
              }`}>
                <span className={`h-1.5 w-1.5 rounded-full ${
                  surat.status === 'disetujui' ? 'bg-emerald-600' :
                  surat.status === 'diajukan' ? 'bg-[#1E3A8A]' :
                  surat.status === 'ditolak' ? 'bg-rose-600' :
                  'bg-amber-500'
                }`}></span>
                {surat.status.toUpperCase()}
              </span>
            </div>
            <p className="text-xs text-slate-500 mt-0.5">{jenis?.nama_jenis}</p>
          </div>
        </div>

        <div className="flex flex-wrap items-center gap-2">
          {surat.status === 'draft' && user?.role === 'staff' && (
            <>
              <button
                onClick={() => onNavigate(`surat_edit_${surat.id}`)}
                data-testid="btn-detail-edit-draft"
                className="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
              >
                <Edit className="h-3.5 w-3.5 text-slate-400 stroke-[1.8]" />
                <span>Edit Draft</span>
              </button>
              <button
                onClick={handleAjukan}
                data-testid="btn-detail-ajukan"
                className="inline-flex items-center gap-1.5 rounded-xl bg-[#1E3A8A] px-4 py-2 text-xs font-semibold text-white hover:bg-[#172554] transition shadow-xs"
              >
                <Send className="h-3.5 w-3.5 stroke-[1.8]" />
                <span>Ajukan ke Kades</span>
              </button>
              <button
                onClick={handleDeleteDraft}
                data-testid="btn-detail-hapus"
                className="inline-flex items-center gap-1 rounded-xl border border-transparent px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition"
              >
                <Trash2 className="h-3.5 w-3.5 text-slate-400 hover:text-rose-600 stroke-[1.8]" />
                <span>Hapus</span>
              </button>
            </>
          )}

          {surat.status === 'disetujui' && (
            <button
              onClick={() => onNavigate(`surat_print_${surat.id}`)}
              data-testid="btn-detail-print"
              className="inline-flex items-center gap-2 rounded-xl bg-[#1E3A8A] px-4 py-2 text-xs font-semibold text-white hover:bg-[#172554] transition active:scale-95 shadow-sm"
            >
              <Printer className="h-4 w-4 stroke-[1.8]" />
              <span>Cetak / Unduh Berkas Resmi</span>
            </button>
          )}
        </div>
      </div>

      {/* Kades Action Panel - Clean light styling */}
      {user?.role === 'kades' && surat.status === 'diajukan' && (
        <div data-testid="kades-validation-panel" className="rounded-2xl bg-white p-5 text-slate-900 border border-slate-200 shadow-2xs space-y-4">
          <div className="flex items-center gap-2 text-[#1E3A8A]">
            <ShieldCheck className="h-5 w-5" />
            <h3 className="text-sm font-bold text-slate-900">Panel Pengesahan Kepala Desa</h3>
          </div>

          <div className="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
            <label className="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
              Catatan Persetujuan:
            </label>
            <input
              type="text"
              data-testid="input-approve-notes"
              value={approveNotes}
              onChange={e => setApproveNotes(e.target.value)}
              className="w-full rounded-lg bg-white text-slate-900 border border-slate-200 px-3 py-2 text-xs font-normal focus:outline-none focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/20"
            />
          </div>

          <div className="flex flex-wrap items-center justify-end gap-2.5">
            <button
              onClick={() => setRejectModalOpen(true)}
              data-testid="btn-kades-reject"
              className="inline-flex items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition"
            >
              <XCircle className="h-4 w-4 stroke-[1.8]" />
              <span>Tolak Permohonan</span>
            </button>
            <button
              onClick={handleApprove}
              data-testid="btn-kades-approve"
              className="inline-flex items-center gap-2 rounded-xl bg-[#1E3A8A] hover:bg-[#172554] text-white px-5 py-2 text-xs font-semibold transition active:scale-95 shadow-sm"
            >
              <CheckCircle className="h-4 w-4 stroke-[2]" />
              <span>Sahkan &amp; Tanda Tangani (QR E-Signature)</span>
            </button>
          </div>
        </div>
      )}

      {/* Validation Status Summary Card */}
      {surat.validasi && (
        <div className={`rounded-xl p-4 border ${
          surat.validasi.status === 'disetujui'
            ? 'bg-slate-50 border-slate-200 text-slate-950'
            : 'bg-rose-50/50 border-rose-200 text-rose-950'
        }`}>
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div className="flex items-start gap-3">
              <div className="rounded-md bg-white p-2 border border-slate-200 shrink-0">
                <QrCode className="h-6 w-6 text-slate-800" />
              </div>
              <div>
                <h3 className="font-semibold text-xs text-slate-900">
                  {surat.validasi.status === 'disetujui' ? 'Tervalidasi Resmi (E-Signature)' : 'Permohonan Ditolak'}
                </h3>
                <p className="text-xs text-slate-600 mt-0.5">
                  Divalidasi oleh: <strong className="text-slate-800">{surat.validasi.validator_name}</strong> pada {surat.validasi.validated_at}
                </p>
                {surat.validasi.catatan && (
                  <p className="text-xs text-slate-700 mt-1 italic">
                    "{surat.validasi.catatan}"
                  </p>
                )}
              </div>
            </div>
            <div className="text-right font-mono text-[11px] text-slate-500 tabular-nums">
              <span>Token: {surat.validasi.qr_code_token}</span>
            </div>
          </div>
        </div>
      )}

      {/* Rejection Notice if rejected without full validasi */}
      {surat.status === 'ditolak' && surat.catatan_penolakan && !surat.validasi && (
        <div className="rounded-xl bg-rose-50 p-4 border border-rose-200 text-rose-800 text-xs">
          <p className="font-semibold">Catatan Penolakan:</p>
          <p className="mt-1">{surat.catatan_penolakan}</p>
        </div>
      )}

      {/* Warga & Surat Content Details */}
      <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
        {/* Warga Info */}
        <div className="rounded-xl bg-white p-5 border border-slate-200">
          <div className="flex items-center gap-2 border-b border-slate-100 pb-3 mb-4">
            <User className="h-4 w-4 text-slate-500" />
            <h2 className="text-base font-semibold text-slate-900">Identitas Pemohon</h2>
          </div>
          {warga ? (
            <dl className="space-y-3 text-sm">
              <div>
                <dt className="text-xs font-medium text-slate-400 uppercase">Nama Lengkap</dt>
                <dd className="font-semibold text-slate-900 mt-0.5">{warga.nama}</dd>
              </div>
              <div>
                <dt className="text-xs font-medium text-slate-400 uppercase">NIK / KK</dt>
                <dd className="font-mono text-slate-800 mt-0.5">{warga.nik} / {warga.no_kk || '-'}</dd>
              </div>
              <div>
                <dt className="text-xs font-medium text-slate-400 uppercase">Tempat, Tanggal Lahir</dt>
                <dd className="text-slate-800 mt-0.5">{formatTempatTanggalLahir(warga.tempat_lahir, warga.tanggal_lahir)}</dd>
              </div>
              <div>
                <dt className="text-xs font-medium text-slate-400 uppercase">Jenis Kelamin &amp; Agama</dt>
                <dd className="text-slate-800 mt-0.5">{warga.jenis_kelamin || '-'} &bull; {warga.agama || '-'}</dd>
              </div>
              <div>
                <dt className="text-xs font-medium text-slate-400 uppercase">Pekerjaan</dt>
                <dd className="text-slate-800 mt-0.5">{warga.pekerjaan || '-'}</dd>
              </div>
              <div>
                <dt className="text-xs font-medium text-slate-400 uppercase">Alamat</dt>
                <dd className="text-slate-800 mt-0.5">{warga.alamat || '-'} (RT {warga.rt || '-'}/RW {warga.rw || '-'})</dd>
              </div>
            </dl>
          ) : (
            <p className="text-xs text-slate-400">Data warga tidak ditemukan.</p>
          )}
        </div>

        {/* Data Isian Surat */}
        <div className="rounded-xl bg-white p-5 border border-slate-200">
          <div className="flex items-center gap-2 border-b border-slate-100 pb-3 mb-4">
            <FileText className="h-4 w-4 text-slate-500" />
            <h2 className="text-base font-semibold text-slate-900">Isian Surat: {jenis?.nama_jenis}</h2>
          </div>
          <dl className="space-y-4 text-sm">
            <div>
              <dt className="text-xs font-medium text-slate-400 uppercase">Nomor Register Surat</dt>
              <dd className="font-mono font-medium text-slate-900 mt-0.5">{surat.nomor_surat}</dd>
            </div>
            <div>
              <dt className="text-xs font-medium text-slate-400 uppercase">Tanggal Permohonan</dt>
              <dd className="text-slate-800 mt-0.5">{surat.created_at}</dd>
            </div>

            {currentJenisFields(jenis, surat.data_isian)}
          </dl>
        </div>
      </div>

      {/* Reject Modal */}
      {rejectModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs px-4">
          <div className="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl border border-slate-200">
            <h3 className="text-base font-bold text-slate-900">Tolak Permohonan Surat</h3>
            <div className="mt-4">
              <label className="block text-xs font-semibold text-slate-700 mb-1.5">Alasan Penolakan:</label>
              <textarea
                rows={3}
                value={rejectReason}
                onChange={e => setRejectReason(e.target.value)}
                placeholder="Contoh: Dokumen KK belum diperbarui..."
                className="w-full rounded-xl border border-slate-200 p-3 text-xs sm:text-sm text-slate-900 focus:border-[#1E3A8A] focus:outline-none focus:ring-2 focus:ring-[#1E3A8A]/20"
              />
            </div>
            <div className="mt-5 flex justify-end gap-2">
              <button
                type="button"
                onClick={() => setRejectModalOpen(false)}
                className="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
              >
                Batal
              </button>
              <button
                type="button"
                onClick={handleReject}
                disabled={!rejectReason.trim()}
                className="rounded-xl bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-700 transition disabled:opacity-50"
              >
                Tolak Permohonan
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

function currentJenisFields(jenis: any, dataIsian: Record<string, string>) {
  if (!jenis || !jenis.template_field) {
    return Object.entries(dataIsian || {}).map(([k, v]) => (
      <div key={k}>
        <dt className="text-xs font-medium text-slate-400 uppercase">{k}</dt>
        <dd className="text-slate-900 mt-0.5">{v || '-'}</dd>
      </div>
    ));
  }

  return jenis.template_field.map((f: any) => (
    <div key={f.key}>
      <dt className="text-xs font-medium text-slate-400 uppercase">{f.label}</dt>
      <dd className="text-slate-900 mt-0.5 whitespace-pre-wrap">{dataIsian[f.key] || '-'}</dd>
    </div>
  ));
}
