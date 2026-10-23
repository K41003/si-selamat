import React, { useState, useEffect, useMemo } from 'react';
import { StorageService } from '../services/storage';
import { useAuth } from '../context/AuthContext';
import { Warga } from '../types';
import {
  Save,
  Send,
  X,
  AlertCircle,
  CheckCircle2,
  Check
} from 'lucide-react';

interface SuratFormProps {
  suratId?: number; // If editing an existing draft
  initialJenisId?: number; // Chosen from modal
  initialWargaId?: number;
  onNavigate: (view: string) => void;
}

export const SuratForm: React.FC<SuratFormProps> = ({
  suratId,
  initialJenisId,
  initialWargaId,
  onNavigate,
}) => {
  const { user } = useAuth();
  const wargaList = useMemo(() => StorageService.getWargaList(), []);
  const jenisList = useMemo(() => StorageService.getJenisSurat(), []);

  // Find existing draft if editing
  const existingSurat = useMemo(() => {
    return suratId ? StorageService.getSuratList().find(s => s.id === suratId) : undefined;
  }, [suratId]);

  const selectedJenisId = existingSurat ? existingSurat.jenis_surat_id : (initialJenisId || jenisList[0]?.id || 1);

  const [nikInput, setNikInput] = useState<string>(() => {
    if (existingSurat) {
      const w = wargaList.find(item => item.id === existingSurat.warga_id);
      return w ? w.nik : '';
    }
    if (initialWargaId) {
      const w = wargaList.find(item => item.id === initialWargaId);
      return w ? w.nik : '';
    }
    return '';
  });

  const [selectedWarga, setSelectedWarga] = useState<Warga | null>(() => {
    if (existingSurat) {
      return wargaList.find(item => item.id === existingSurat.warga_id) || null;
    }
    if (initialWargaId) {
      return wargaList.find(item => item.id === initialWargaId) || null;
    }
    return null;
  });

  const [customFields, setCustomFields] = useState<Record<string, string>>(() => {
    if (existingSurat) {
      return { ...existingSurat.data_isian };
    }
    return {};
  });

  const [errorMessage, setErrorMessage] = useState<string | null>(null);
  const [successMessage, setSuccessMessage] = useState<string | null>(null);

  const currentJenis = useMemo(() => {
    return jenisList.find(j => j.id === selectedJenisId) || jenisList[0];
  }, [jenisList, selectedJenisId]);

  // Auto-fill resident data whenever nikInput changes
  useEffect(() => {
    const cleanNik = nikInput.trim();
    if (!cleanNik) {
      setSelectedWarga(null);
      return;
    }
    const match = wargaList.find(w => w.nik === cleanNik);
    if (match) {
      setSelectedWarga(match);
      setErrorMessage(null);
    } else {
      setSelectedWarga(null);
    }
  }, [nikInput, wargaList]);

  // Sync / initialize custom fields
  useEffect(() => {
    if (currentJenis && currentJenis.template_field) {
      setCustomFields(prev => {
        let hasChanges = false;
        const next: Record<string, string> = { ...prev };
        currentJenis.template_field.forEach(f => {
          if (next[f.key] === undefined) {
            next[f.key] = '';
            hasChanges = true;
          }
        });
        return hasChanges ? next : prev;
      });
    }
  }, [currentJenis]);

  const handleFieldChange = (key: string, val: string) => {
    setCustomFields(prev => ({ ...prev, [key]: val }));
    setErrorMessage(null);
  };

  const handleSave = (status: 'draft' | 'diajukan') => {
    setErrorMessage(null);

    if (!nikInput.trim()) {
      setErrorMessage('Nomor NIK 16 digit wajib diisi pada template surat.');
      return;
    }

    if (!selectedWarga) {
      setErrorMessage(`NIK "${nikInput}" belum terdaftar di data kependudukan desa.`);
      return;
    }

    if (!currentJenis) {
      setErrorMessage('Jenis surat tidak valid.');
      return;
    }

    if (!user) return;

    if (status === 'diajukan') {
      for (const field of currentJenis.template_field) {
        if (!customFields[field.key] || !customFields[field.key].trim()) {
          setErrorMessage(`Kolom isian "${field.label}" wajib dilengkapi sebelum diajukan.`);
          return;
        }
      }
    }

    if (existingSurat) {
      const updated = StorageService.updateSurat(
        existingSurat.id,
        selectedWarga.id,
        currentJenis.id,
        status,
        customFields,
        user
      );
      if (updated) {
        setSuccessMessage(
          status === 'draft'
            ? 'Draft surat berhasil diperbarui!'
            : 'Surat berhasil diajukan ke Kepala Desa!'
        );
        setTimeout(() => {
          onNavigate(`surat_detail_${updated.id}`);
        }, 600);
      }
    } else {
      const newSurat = StorageService.addSurat(
        selectedWarga.id,
        currentJenis.id,
        status,
        customFields,
        user
      );
      setSuccessMessage(
        status === 'draft'
          ? 'Surat berhasil disimpan sebagai Draft!'
          : 'Surat berhasil diajukan ke Kepala Desa!'
      );
      setTimeout(() => {
        onNavigate(`surat_detail_${newSurat.id}`);
      }, 600);
    }
  };

  return (
    <div className="mx-auto max-w-4xl space-y-6 pb-16">
      {/* Alert Error Box */}
      {errorMessage && (
        <div className="flex items-center gap-3 rounded-xl bg-red-50 p-4 text-sm font-semibold text-red-800 ring-1 ring-red-200">
          <AlertCircle className="h-5 w-5 shrink-0 text-red-600" />
          <span>{errorMessage}</span>
        </div>
      )}

      {/* Alert Success Box */}
      {successMessage && (
        <div className="flex items-center gap-3 rounded-xl bg-emerald-50 p-4 text-sm font-semibold text-emerald-900 ring-1 ring-emerald-300">
          <CheckCircle2 className="h-5 w-5 shrink-0 text-emerald-600" />
          <span>{successMessage}</span>
        </div>
      )}

      {/* TEMPLATE SURAT RESMI LANGSUNG (A4 PAPER) */}
      <div className="rounded-2xl bg-white p-8 sm:p-14 shadow-lg ring-1 ring-slate-200 text-slate-900 space-y-6">
        {/* KOP SURAT RESMI DESA */}
        <div className="border-b-4 border-double border-slate-900 pb-5 text-center relative">
          <div className="flex flex-col items-center justify-center">
            <p className="font-serif text-xs sm:text-sm font-bold tracking-widest text-slate-800 uppercase">
              Pemerintah Kabupaten Deli Serdang
            </p>
            <p className="font-serif text-xs sm:text-sm font-bold tracking-widest text-slate-800 uppercase">
              Kecamatan Sunggal
            </p>
            <h2 className="font-serif text-lg sm:text-2xl font-black tracking-wider text-slate-950 uppercase mt-0.5">
              Pemerintah Desa Tanjung Selamat
            </h2>
            <p className="text-xs text-slate-600 mt-1">
              Jl. Besar Tanjung Selamat No. 12, Kode Pos 20353 &bull; Telp: (061) 8456-7890
            </p>
          </div>
        </div>

        {/* JUDUL SURAT & NOMOR */}
        <div className="text-center space-y-1.5 pt-2">
          <h3 className="font-serif text-base sm:text-lg font-bold underline uppercase tracking-wide text-slate-900">
            {currentJenis?.nama_jenis}
          </h3>
          <p className="font-mono text-xs text-slate-600">
            Nomor: 470 / DRAFT / {currentJenis?.kode} / TS / {new Date().getFullYear()}
          </p>
        </div>

        {/* PARAGRAF PEMBUKA */}
        <p className="text-sm leading-relaxed text-justify text-slate-800 font-serif">
          Yang bertanda tangan di bawah ini Kepala Desa Tanjung Selamat, Kecamatan Sunggal, Kabupaten Deli Serdang, Provinsi Sumatera Utara, dengan ini menerangkan bahwa:
        </p>

        {/* BIODATA WARGA (INPUT NIK & AUTO-FILL) */}
        <div className="space-y-3.5 pl-2 sm:pl-6 text-sm text-slate-800">
          {/* NIK Field */}
          <div className="grid grid-cols-12 gap-2 items-center">
            <span className="col-span-12 sm:col-span-4 font-semibold text-slate-700">
              1. NIK (Nomor KTP) <span className="text-red-500">*</span>
            </span>
            <span className="hidden sm:block col-span-1 text-center font-bold">:</span>
            <div className="col-span-12 sm:col-span-7 flex items-center gap-2">
              <div className="relative flex-1">
                <input
                  type="text"
                  id="nik-surat"
                  data-testid="input-surat-nik"
                  maxLength={16}
                  value={nikInput}
                  onChange={e => {
                    const val = e.target.value.replace(/\D/g, '');
                    setNikInput(val);
                    setErrorMessage(null);
                  }}
                  placeholder="Ketik 16 digit NIK..."
                  className={`w-full rounded-lg border px-3 py-1.5 font-mono text-sm font-bold tracking-wider focus:outline-none transition ${
                    selectedWarga
                      ? 'border-emerald-500 bg-emerald-50/40 text-emerald-950'
                      : nikInput.length >= 16
                      ? 'border-amber-400 bg-amber-50/40'
                      : 'border-slate-300 focus:border-slate-800 bg-white'
                  }`}
                  autoFocus
                />
                {selectedWarga && (
                  <div data-testid="badge-autofill-success" className="absolute right-2.5 top-2 flex items-center gap-1 text-[11px] font-bold text-emerald-700">
                    <Check className="h-3.5 w-3.5 stroke-[3]" /> Terisi Otomatis
                  </div>
                )}
              </div>
            </div>
          </div>

          {/* Nama Pemohon (Auto-fill) */}
          <div className="grid grid-cols-12 gap-2 items-center">
            <span className="col-span-12 sm:col-span-4 font-semibold text-slate-700">2. Nama Lengkap</span>
            <span className="hidden sm:block col-span-1 text-center font-bold">:</span>
            <div className="col-span-12 sm:col-span-7">
              <input
                type="text"
                readOnly
                value={selectedWarga ? selectedWarga.nama : ''}
                placeholder="[Otomatis terisi setelah NIK diinput]"
                className="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm font-bold text-slate-900 focus:outline-none cursor-default"
              />
            </div>
          </div>

          {/* Tempat & Tanggal Lahir (Auto-fill) */}
          <div className="grid grid-cols-12 gap-2 items-center">
            <span className="col-span-12 sm:col-span-4 font-semibold text-slate-700">3. Tempat / Tgl Lahir</span>
            <span className="hidden sm:block col-span-1 text-center font-bold">:</span>
            <div className="col-span-12 sm:col-span-7">
              <input
                type="text"
                readOnly
                value={selectedWarga ? `${selectedWarga.tempat_lahir || '-'}, ${selectedWarga.tanggal_lahir || '-'}` : ''}
                placeholder="[Otomatis terisi setelah NIK diinput]"
                className="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm text-slate-800 focus:outline-none cursor-default"
              />
            </div>
          </div>

          {/* Jenis Kelamin (Auto-fill) */}
          <div className="grid grid-cols-12 gap-2 items-center">
            <span className="col-span-12 sm:col-span-4 font-semibold text-slate-700">4. Jenis Kelamin</span>
            <span className="hidden sm:block col-span-1 text-center font-bold">:</span>
            <div className="col-span-12 sm:col-span-7">
              <input
                type="text"
                readOnly
                value={selectedWarga ? selectedWarga.jenis_kelamin : ''}
                placeholder="[Otomatis terisi setelah NIK diinput]"
                className="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm text-slate-800 focus:outline-none cursor-default"
              />
            </div>
          </div>

          {/* Agama & Status Perkawinan (Auto-fill) */}
          <div className="grid grid-cols-12 gap-2 items-center">
            <span className="col-span-12 sm:col-span-4 font-semibold text-slate-700">5. Agama / Status</span>
            <span className="hidden sm:block col-span-1 text-center font-bold">:</span>
            <div className="col-span-12 sm:col-span-7">
              <input
                type="text"
                readOnly
                value={selectedWarga ? `${selectedWarga.agama || '-'} / ${selectedWarga.status_perkawinan || '-'}` : ''}
                placeholder="[Otomatis terisi setelah NIK diinput]"
                className="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm text-slate-800 focus:outline-none cursor-default"
              />
            </div>
          </div>

          {/* Pekerjaan (Auto-fill) */}
          <div className="grid grid-cols-12 gap-2 items-center">
            <span className="col-span-12 sm:col-span-4 font-semibold text-slate-700">6. Pekerjaan</span>
            <span className="hidden sm:block col-span-1 text-center font-bold">:</span>
            <div className="col-span-12 sm:col-span-7">
              <input
                type="text"
                readOnly
                value={selectedWarga ? selectedWarga.pekerjaan : ''}
                placeholder="[Otomatis terisi setelah NIK diinput]"
                className="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm text-slate-800 focus:outline-none cursor-default"
              />
            </div>
          </div>

          {/* Alamat (Auto-fill) */}
          <div className="grid grid-cols-12 gap-2 items-center">
            <span className="col-span-12 sm:col-span-4 font-semibold text-slate-700">7. Alamat Domisili</span>
            <span className="hidden sm:block col-span-1 text-center font-bold">:</span>
            <div className="col-span-12 sm:col-span-7">
              <input
                type="text"
                readOnly
                value={selectedWarga ? `${selectedWarga.alamat || '-'} RT ${selectedWarga.rt || '01'}/RW ${selectedWarga.rw || '01'}, Desa Tanjung Selamat` : ''}
                placeholder="[Otomatis terisi setelah NIK diinput]"
                className="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm text-slate-800 focus:outline-none cursor-default"
              />
            </div>
          </div>
        </div>

        {/* ISIAN KHUSUS LANGSUNG DI DALAM TEMPLATE SURAT */}
        <div className="border-t border-b border-slate-200 py-4 my-4 space-y-3.5 pl-2 sm:pl-6 text-sm text-slate-800">
          <p className="font-serif italic text-xs text-slate-500 mb-2">
            * Keterangan khusus permohonan surat {currentJenis?.nama_jenis}:
          </p>

          {currentJenis?.template_field.map((field, idx) => {
            const val = customFields[field.key] || '';
            return (
              <div key={field.key} className="grid grid-cols-12 gap-2 items-start">
                <span className="col-span-12 sm:col-span-4 font-semibold text-slate-900 pt-1.5">
                  {8 + idx}. {field.label} <span className="text-red-500">*</span>
                </span>
                <span className="hidden sm:block col-span-1 text-center font-bold pt-1.5">:</span>
                <div className="col-span-12 sm:col-span-7">
                  {field.type === 'textarea' ? (
                    <textarea
                      rows={3}
                      value={val}
                      onChange={e => handleFieldChange(field.key, e.target.value)}
                      placeholder={`Ketik ${field.label.toLowerCase()}...`}
                      className="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800"
                    />
                  ) : field.type === 'date' ? (
                    <input
                      type="date"
                      value={val}
                      onChange={e => handleFieldChange(field.key, e.target.value)}
                      className="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800"
                    />
                  ) : field.type === 'number' ? (
                    <input
                      type="number"
                      value={val}
                      onChange={e => handleFieldChange(field.key, e.target.value)}
                      placeholder={`Masukkan ${field.label.toLowerCase()}...`}
                      className="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800"
                    />
                  ) : (
                    <input
                      type="text"
                      value={val}
                      onChange={e => handleFieldChange(field.key, e.target.value)}
                      placeholder={`Ketik ${field.label.toLowerCase()}...`}
                      className="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800"
                    />
                  )}
                </div>
              </div>
            );
          })}
        </div>

        {/* PARAGRAF PENUTUP */}
        <p className="text-sm leading-relaxed text-justify text-slate-800 font-serif">
          Demikian surat keterangan ini kami perbuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya oleh yang bersangkutan.
        </p>

        {/* TANDA TANGAN KADES & PETUGAS */}
        <div className="pt-6 flex justify-between items-end text-xs">
          <div className="text-slate-400 space-y-0.5">
            <p>Petugas Pelayanan: <span className="font-semibold text-slate-700">{user?.name}</span></p>
            <p>Status: Draft / Diajukan</p>
          </div>

          <div className="text-right space-y-1">
            <p>Tanjung Selamat, {new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</p>
            <p className="font-bold text-slate-900 font-serif">Kepala Desa Tanjung Selamat</p>
            <div className="h-14 flex items-center justify-end">
              <span className="text-[10px] text-slate-400 italic">
                [QR E-Signature & Validasi Kades]
              </span>
            </div>
            <p className="font-bold text-slate-900 underline font-serif">H. AHMAD FAUZI, S.Sos</p>
          </div>
        </div>
      </div>

      {/* HANYA 3 BUTTON: BATAL, SIMPAN DRAFT, AJUKAN SURAT */}
      <div className="flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-2xl ring-1 ring-slate-200 shadow-xs">
        {/* Button 1: Batal */}
        <button
          type="button"
          id="btn-surat-batal"
          onClick={() => onNavigate('surat')}
          className="flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition shadow-xs"
        >
          <X className="h-4 w-4 text-slate-500" />
          <span>Batal</span>
        </button>

        <div className="flex items-center gap-3">
          {/* Button 2: Simpan Draft */}
          <button
            type="button"
            id="btn-surat-draft"
            data-testid="btn-surat-draft"
            onClick={() => handleSave('draft')}
            className="flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition shadow-xs"
          >
            <Save className="h-4 w-4 text-slate-600" />
            <span>Simpan Draft</span>
          </button>

          {/* Button 3: Ajukan Surat */}
          <button
            type="button"
            id="btn-surat-ajukan"
            data-testid="btn-surat-ajukan"
            onClick={() => handleSave('diajukan')}
            className="flex items-center gap-2 rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white hover:bg-slate-800 transition shadow-md"
          >
            <Send className="h-4 w-4 text-emerald-400" />
            <span>Ajukan Surat</span>
          </button>
        </div>
      </div>
    </div>
  );
};
