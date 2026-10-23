import React, { useState } from 'react';
import { StorageService } from '../services/storage';
import { useAuth } from '../context/AuthContext';
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
      <div className="flex flex-wrap items-center justify-between gap-3">
        <div className="flex items-center gap-3">
          <button
            onClick={() => onNavigate('surat')}
            className="rounded-lg border border-slate-200 bg-white p-2 text-slate-600 hover:bg-slate-50 transition"
          >
            <ArrowLeft className="h-4 w-4" />
          </button>
          <div>
            <div className="flex items-center gap-2">
              <h1 className="text-xl font-bold text-slate-900">{surat.nomor_surat}</h1>
              <span className={`rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase ${
                surat.status === 'disetujui' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' :
                surat.status === 'diajukan' ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-600/20' :
                surat.status === 'ditolak' ? 'bg-red-50 text-red-700 ring-1 ring-red-600/20' :
                'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20'
              }`}>
                {surat.status}
              </span>
            </div>
            <p className="text-sm text-slate-500">{jenis?.nama_jenis}</p>
          </div>
        </div>

        <div className="flex items-center gap-2">
          {surat.status === 'draft' && user?.role === 'staff' && (
            <>
              <button
                onClick={() => onNavigate(`surat_edit_${surat.id}`)}
                data-testid="btn-detail-edit-draft"
                className="flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-xs"
              >
                <Edit className="h-3.5 w-3.5" />
                Edit Draft
              </button>
              <button
                onClick={handleAjukan}
                data-testid="btn-detail-ajukan"
                className="flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition shadow-xs"
              >
                <Send className="h-3.5 w-3.5" />
                Ajukan ke Kades
              </button>
              <button
                onClick={handleDeleteDraft}
                data-testid="btn-detail-hapus"
                className="flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100 transition"
              >
                <Trash2 className="h-3.5 w-3.5" />
                Hapus
              </button>
            </>
          )}

          {surat.status === 'disetujui' && (
            <button
              onClick={() => onNavigate(`surat_print_${surat.id}`)}
              data-testid="btn-detail-print"
              className="flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition shadow-xs"
            >
              <Printer className="h-3.5 w-3.5" />
              Cetak / Unduh Surat
            </button>
          )}
        </div>
      </div>

      {/* Kades Action Panel (When in 'diajukan' status and user is Kades) */}
      {user?.role === 'kades' && surat.status === 'diajukan' && (
        <div data-testid="kades-validation-panel" className="rounded-xl bg-slate-900 p-6 text-white shadow-md">
          <div className="flex items-center gap-2.5 mb-3 text-emerald-400">
            <ShieldCheck className="h-5 w-5" />
            <h3 className="text-base font-bold">Pengesahan Tanda Tangan Elektronik (E-Signature)</h3>
          </div>
          <p className="text-xs text-slate-300 mb-4 leading-relaxed">
            Sebagai Kepala Desa, Anda dapat menyetujui permohonan ini untuk otomatis membubuhkan QR Code E-Signature resmi, atau menolak jika terdapat data yang kurang lengkap.
          </p>

          <div className="mb-4">
            <label className="block text-xs font-medium text-slate-300 mb-1">Catatan Persetujuan (Opsional):</label>
            <input
              type="text"
              data-testid="input-approve-notes"
              value={approveNotes}
              onChange={e => setApproveNotes(e.target.value)}
              className="w-full rounded-lg bg-slate-800 border border-slate-700 px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-emerald-400"
            />
          </div>

          <div className="flex items-center justify-end gap-3">
            <button
              onClick={() => setRejectModalOpen(true)}
              data-testid="btn-kades-reject"
              className="flex items-center gap-1.5 rounded-lg border border-red-500/50 bg-red-950/40 px-4 py-2 text-xs font-semibold text-red-300 hover:bg-red-900/60 transition"
            >
              <XCircle className="h-4 w-4" />
              Tolak Permohonan
            </button>
            <button
              onClick={handleApprove}
              data-testid="btn-kades-approve"
              className="flex items-center gap-1.5 rounded-lg bg-emerald-500 px-5 py-2 text-xs font-bold text-slate-950 hover:bg-emerald-400 transition shadow-xs"
            >
              <CheckCircle className="h-4 w-4" />
              Setujui &amp; Tanda Tangani (QR E-Signature)
            </button>
          </div>
        </div>
      )}

      {/* Validation Status Summary Card */}
      {surat.validasi && (
        <div className={`rounded-xl p-5 ring-1 ${
          surat.validasi.status === 'disetujui'
            ? 'bg-emerald-50/70 ring-emerald-200 text-emerald-900'
            : 'bg-red-50/70 ring-red-200 text-red-900'
        }`}>
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div className="flex items-start gap-3">
              <div className="rounded-lg bg-white p-2 shadow-xs ring-1 ring-slate-200 shrink-0">
                <QrCode className="h-8 w-8 text-slate-800" />
              </div>
              <div>
                <h3 className="font-bold text-sm">
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
            <div className="text-right font-mono text-[11px] text-slate-500">
              <span>Token: {surat.validasi.qr_code_token}</span>
            </div>
          </div>
        </div>
      )}

      {/* Rejection Notice if rejected without full validasi */}
      {surat.status === 'ditolak' && surat.catatan_penolakan && !surat.validasi && (
        <div className="rounded-xl bg-red-50 p-4 ring-1 ring-red-200 text-red-800 text-sm">
          <p className="font-semibold">Catatan Penolakan:</p>
          <p className="mt-1 text-xs">{surat.catatan_penolakan}</p>
        </div>
      )}

      {/* Warga & Surat Content Details */}
      <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
        {/* Warga Info */}
        <div className="rounded-xl bg-white p-6 shadow-xs ring-1 ring-slate-200">
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
                <dd className="text-slate-800 mt-0.5">{warga.tempat_lahir || '-'}, {warga.tanggal_lahir || '-'}</dd>
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
        <div className="rounded-xl bg-white p-6 shadow-xs ring-1 ring-slate-200">
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
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs px-4">
          <div className="w-full max-w-md rounded-xl bg-white p-6 shadow-xl ring-1 ring-slate-200">
            <h3 className="text-base font-semibold text-slate-900">Tolak Permohonan Surat</h3>
            <p className="mt-1 text-xs text-slate-500">
              Sebutkan alasan penolakan agar staff dapat melengkapi atau memberitahukan kepada warga.
            </p>
            <div className="mt-4">
              <label className="block text-xs font-medium text-slate-700 mb-1">Alasan Penolakan:</label>
              <textarea
                rows={3}
                value={rejectReason}
                onChange={e => setRejectReason(e.target.value)}
                placeholder="Contoh: Dokumen KK belum diperbarui / Alamat usaha tidak sesuai..."
                className="w-full rounded-lg border border-slate-300 p-2.5 text-sm focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500"
              />
            </div>
            <div className="mt-5 flex justify-end gap-2">
              <button
                type="button"
                onClick={() => setRejectModalOpen(false)}
                className="rounded-lg border border-slate-200 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50"
              >
                Batal
              </button>
              <button
                type="button"
                onClick={handleReject}
                disabled={!rejectReason.trim()}
                className="rounded-lg bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700 disabled:opacity-50"
              >
                Konfirmasi Penolakan
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
