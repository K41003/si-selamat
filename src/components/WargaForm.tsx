import React, { useState, useEffect } from 'react';
import { StorageService } from '../services/storage';
import { useAuth } from '../context/AuthContext';
import { Warga } from '../types';
import { ArrowLeft, Save, AlertCircle } from 'lucide-react';

interface WargaFormProps {
  wargaId?: number;
  onNavigate: (view: string) => void;
}

export const WargaForm: React.FC<WargaFormProps> = ({ wargaId, onNavigate }) => {
  const { user } = useAuth();
  const isEdit = Boolean(wargaId);

  const [formData, setFormData] = useState({
    nik: '',
    no_kk: '',
    nama: '',
    tempat_lahir: '',
    tanggal_lahir: '',
    jenis_kelamin: 'Laki-laki' as 'Laki-laki' | 'Perempuan',
    agama: 'Islam',
    pekerjaan: '',
    status_perkawinan: 'Belum Kawin' as 'Belum Kawin' | 'Kawin' | 'Cerai Hidup' | 'Cerai Mati',
    alamat: '',
    rt: '',
    rw: '',
    no_hp: '',
  });

  const [errors, setErrors] = useState<Record<string, string>>({});

  useEffect(() => {
    if (wargaId) {
      const list = StorageService.getWargaList();
      const existing = list.find(w => w.id === wargaId);
      if (existing) {
        setFormData({
          nik: existing.nik || '',
          no_kk: existing.no_kk || '',
          nama: existing.nama || '',
          tempat_lahir: existing.tempat_lahir || '',
          tanggal_lahir: existing.tanggal_lahir || '',
          jenis_kelamin: (existing.jenis_kelamin as any) || 'Laki-laki',
          agama: existing.agama || 'Islam',
          pekerjaan: existing.pekerjaan || '',
          status_perkawinan: (existing.status_perkawinan as any) || 'Belum Kawin',
          alamat: existing.alamat || '',
          rt: existing.rt || '',
          rw: existing.rw || '',
          no_hp: existing.no_hp || '',
        });
      }
    }
  }, [wargaId]);

  const validate = () => {
    const errs: Record<string, string> = {};
    if (!formData.nik.trim()) {
      errs.nik = 'NIK wajib diisi.';
    } else if (!/^\d{16}$/.test(formData.nik.trim())) {
      errs.nik = 'NIK harus terdiri dari 16 digit angka.';
    } else {
      const allWarga = StorageService.getWargaList();
      const duplicate = allWarga.find(w => w.nik === formData.nik.trim() && w.id !== wargaId);
      if (duplicate) {
        errs.nik = 'NIK ini sudah terdaftar di sistem.';
      }
    }

    if (formData.no_kk.trim() && !/^\d{16}$/.test(formData.no_kk.trim())) {
      errs.no_kk = 'Nomor KK harus terdiri dari 16 digit angka.';
    }

    if (!formData.nama.trim()) {
      errs.nama = 'Nama lengkap wajib diisi.';
    }

    setErrors(errs);
    return Object.keys(errs).length === 0;
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!validate() || !user) return;

    if (isEdit && wargaId) {
      StorageService.updateWarga(wargaId, formData, user.name, user.role);
    } else {
      StorageService.addWarga(formData, user.name, user.role);
    }

    onNavigate('warga');
  };

  return (
    <div className="mx-auto max-w-4xl space-y-6">
      <div className="flex items-center justify-between">
        <div className="flex items-center gap-3">
          <button
            onClick={() => onNavigate('warga')}
            className="rounded-lg border border-slate-200 bg-white p-2 text-slate-600 hover:bg-slate-50 transition"
          >
            <ArrowLeft className="h-4 w-4" />
          </button>
          <div>
            <h1 className="text-xl font-bold text-slate-900">
              {isEdit ? 'Update Data Warga' : 'Tambah Data Warga'}
            </h1>
            <p className="text-sm text-slate-500">
              {isEdit ? 'Perbarui informasi data kependudukan warga.' : 'Masukkan data identitas kependudukan warga desa.'}
            </p>
          </div>
        </div>
      </div>

      <div className="rounded-xl bg-white p-6 shadow-xs ring-1 ring-slate-200">
        <form onSubmit={handleSubmit} className="space-y-6">
          <div className="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
              <label htmlFor="nik" className="mb-1 block text-sm font-medium text-slate-700">
                NIK <span className="text-red-500">*</span>
              </label>
              <input
                type="text"
                id="nik"
                data-testid="input-warga-nik"
                maxLength={16}
                value={formData.nik}
                onChange={e => setFormData({ ...formData, nik: e.target.value.replace(/\D/g, '') })}
                placeholder="16 digit NIK"
                className={`w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-1 ${
                  errors.nik ? 'border-red-400 focus:ring-red-400 bg-red-50/30' : 'border-slate-300 focus:ring-slate-500'
                }`}
              />
              {errors.nik && <p data-testid="error-warga-nik" className="mt-1 text-xs text-red-600">{errors.nik}</p>}
            </div>

            <div>
              <label htmlFor="no_kk" className="mb-1 block text-sm font-medium text-slate-700">
                Nomor Kartu Keluarga (KK)
              </label>
              <input
                type="text"
                id="no_kk"
                data-testid="input-warga-kk"
                maxLength={16}
                value={formData.no_kk}
                onChange={e => setFormData({ ...formData, no_kk: e.target.value.replace(/\D/g, '') })}
                placeholder="16 digit Nomor KK"
                className={`w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-1 ${
                  errors.no_kk ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:ring-slate-500'
                }`}
              />
              {errors.no_kk && <p data-testid="error-warga-kk" className="mt-1 text-xs text-red-600">{errors.no_kk}</p>}
            </div>

            <div className="sm:col-span-2">
              <label htmlFor="nama" className="mb-1 block text-sm font-medium text-slate-700">
                Nama Lengkap <span className="text-red-500">*</span>
              </label>
              <input
                type="text"
                id="nama"
                data-testid="input-warga-nama"
                value={formData.nama}
                onChange={e => setFormData({ ...formData, nama: e.target.value })}
                placeholder="Nama lengkap sesuai KTP"
                className={`w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-1 ${
                  errors.nama ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:ring-slate-500'
                }`}
              />
              {errors.nama && <p data-testid="error-warga-nama" className="mt-1 text-xs text-red-600">{errors.nama}</p>}
            </div>

            <div>
              <label htmlFor="tempat_lahir" className="mb-1 block text-sm font-medium text-slate-700">
                Tempat Lahir
              </label>
              <input
                type="text"
                id="tempat_lahir"
                value={formData.tempat_lahir}
                onChange={e => setFormData({ ...formData, tempat_lahir: e.target.value })}
                placeholder="Kota / Kabupaten"
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
              />
            </div>

            <div>
              <label htmlFor="tanggal_lahir" className="mb-1 block text-sm font-medium text-slate-700">
                Tanggal Lahir
              </label>
              <input
                type="date"
                id="tanggal_lahir"
                value={formData.tanggal_lahir}
                onChange={e => setFormData({ ...formData, tanggal_lahir: e.target.value })}
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
              />
            </div>

            <div>
              <label htmlFor="jenis_kelamin" className="mb-1 block text-sm font-medium text-slate-700">
                Jenis Kelamin
              </label>
              <select
                id="jenis_kelamin"
                value={formData.jenis_kelamin}
                onChange={e => setFormData({ ...formData, jenis_kelamin: e.target.value as any })}
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
              >
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
              </select>
            </div>

            <div>
              <label htmlFor="agama" className="mb-1 block text-sm font-medium text-slate-700">
                Agama
              </label>
              <input
                type="text"
                id="agama"
                value={formData.agama}
                onChange={e => setFormData({ ...formData, agama: e.target.value })}
                placeholder="Islam, Kristen, dll."
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
              />
            </div>

            <div>
              <label htmlFor="pekerjaan" className="mb-1 block text-sm font-medium text-slate-700">
                Pekerjaan
              </label>
              <input
                type="text"
                id="pekerjaan"
                value={formData.pekerjaan}
                onChange={e => setFormData({ ...formData, pekerjaan: e.target.value })}
                placeholder="Wiraswasta, Petani, Guru, dll."
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
              />
            </div>

            <div>
              <label htmlFor="status_perkawinan" className="mb-1 block text-sm font-medium text-slate-700">
                Status Perkawinan
              </label>
              <select
                id="status_perkawinan"
                value={formData.status_perkawinan}
                onChange={e => setFormData({ ...formData, status_perkawinan: e.target.value as any })}
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
              >
                <option value="Belum Kawin">Belum Kawin</option>
                <option value="Kawin">Kawin</option>
                <option value="Cerai Hidup">Cerai Hidup</option>
                <option value="Cerai Mati">Cerai Mati</option>
              </select>
            </div>

            <div>
              <label htmlFor="no_hp" className="mb-1 block text-sm font-medium text-slate-700">
                Nomor HP / WhatsApp
              </label>
              <input
                type="text"
                id="no_hp"
                value={formData.no_hp}
                onChange={e => setFormData({ ...formData, no_hp: e.target.value })}
                placeholder="08..."
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
              />
            </div>

            <div className="grid grid-cols-2 gap-3">
              <div>
                <label htmlFor="rt" className="mb-1 block text-sm font-medium text-slate-700">
                  RT
                </label>
                <input
                  type="text"
                  id="rt"
                  maxLength={5}
                  value={formData.rt}
                  onChange={e => setFormData({ ...formData, rt: e.target.value })}
                  placeholder="01"
                  className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
                />
              </div>
              <div>
                <label htmlFor="rw" className="mb-1 block text-sm font-medium text-slate-700">
                  RW
                </label>
                <input
                  type="text"
                  id="rw"
                  maxLength={5}
                  value={formData.rw}
                  onChange={e => setFormData({ ...formData, rw: e.target.value })}
                  placeholder="02"
                  className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
                />
              </div>
            </div>

            <div className="sm:col-span-2">
              <label htmlFor="alamat" className="mb-1 block text-sm font-medium text-slate-700">
                Alamat Lengkap (Dusun / Jalan)
              </label>
              <textarea
                id="alamat"
                rows={3}
                value={formData.alamat}
                onChange={e => setFormData({ ...formData, alamat: e.target.value })}
                placeholder="Dusun I, Jl. Flamboyan No. 12"
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
              />
            </div>
          </div>

          <div className="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
            <button
              type="button"
              onClick={() => onNavigate('warga')}
              className="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
            >
              Batal
            </button>
            <button
              id="btn-save-warga"
              data-testid="btn-save-warga"
              type="submit"
              className="flex items-center gap-1.5 rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition shadow-xs"
            >
              <Save className="h-4 w-4" />
              {isEdit ? 'Simpan Perubahan' : 'Simpan Data Warga'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};
