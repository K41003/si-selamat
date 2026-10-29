export type UserRole = 'staff' | 'kades';

export interface User {
  id: number;
  name: string;
  username: string;
  password?: string;
  email: string;
  role: UserRole;
  is_active: boolean;
  active_staff_name?: string; // Nama petugas staff yang bertugas/shift hari ini
}

export interface Warga {
  id: number;
  nik: string;
  no_kk?: string;
  nama: string;
  tempat_lahir?: string;
  tanggal_lahir?: string; // YYYY-MM-DD
  jenis_kelamin?: 'Laki-laki' | 'Perempuan';
  agama?: string;
  pekerjaan?: string;
  status_perkawinan?: 'Belum Kawin' | 'Kawin' | 'Cerai Hidup' | 'Cerai Mati';
  alamat?: string;
  rt?: string;
  rw?: string;
  no_hp?: string;
  created_at: string;
  updated_at: string;
}

export interface TemplateField {
  key: string;
  label: string;
  type: 'text' | 'textarea' | 'date' | 'number';
}

export interface JenisSurat {
  id: number;
  kode: string;
  nama_jenis: string;
  deskripsi?: string;
  template_field: TemplateField[];
  is_active: boolean;
}

export type SuratStatus = 'draft' | 'diajukan' | 'disetujui' | 'ditolak';

export interface SuratValidasi {
  id: number;
  surat_id: number;
  validated_by: number;
  validator_name: string;
  status: 'disetujui' | 'ditolak';
  qr_code_token: string;
  catatan?: string;
  validated_at: string;
}

export interface Surat {
  id: number;
  nomor_surat: string;
  warga_id: number;
  warga?: Warga; // Snapshot data warga pemohon saat surat dibuat/diajukan
  jenis_surat_id: number;
  status: SuratStatus;
  data_isian: Record<string, string>;
  tanggal_pengajuan: string;
  created_by: number;
  created_by_name: string; // Menyimpan nama staff pembuat/yang bertugas
  validasi?: SuratValidasi;
  catatan_penolakan?: string;
  created_at: string;
  updated_at: string;
}

export interface ActivityLog {
  id: number;
  user_id?: number;
  user_name: string;
  user_role: string;
  aksi: string;
  deskripsi: string;
  model_type?: string;
  model_id?: number;
  created_at: string;
}
