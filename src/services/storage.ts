import { Warga, Surat, ActivityLog, JenisSurat, User } from '../types';
import { INITIAL_USERS, INITIAL_JENIS_SURAT, INITIAL_WARGA, INITIAL_SURAT, INITIAL_LOGS } from '../data/initialData';

const KEYS = {
  USERS: 'si_selamat_users_v2',
  JENIS_SURAT: 'si_selamat_jenis_surat_v2',
  WARGA: 'si_selamat_warga_v2',
  SURAT: 'si_selamat_surat_v2',
  LOGS: 'si_selamat_logs_v2',
  CURRENT_USER: 'si_selamat_current_user_v2',
};

export const StorageService = {
  getUsers(): User[] {
    const raw = localStorage.getItem(KEYS.USERS);
    if (!raw) {
      localStorage.setItem(KEYS.USERS, JSON.stringify(INITIAL_USERS));
      return INITIAL_USERS;
    }
    return JSON.parse(raw);
  },

  saveUsers(users: User[]) {
    localStorage.setItem(KEYS.USERS, JSON.stringify(users));
  },

  updateUserPassword(username: string, newPassword: string): boolean {
    const users = this.getUsers();
    const userIndex = users.findIndex(u => u.username.toLowerCase() === username.toLowerCase());
    if (userIndex === -1) return false;
    users[userIndex].password = newPassword;
    this.saveUsers(users);
    return true;
  },

  updateStaffActiveName(staffName: string): User | null {
    const users = this.getUsers();
    const staffIndex = users.findIndex(u => u.role === 'staff');
    if (staffIndex === -1) return null;
    users[staffIndex].active_staff_name = staffName;
    this.saveUsers(users);

    const currentUser = this.getCurrentUser();
    if (currentUser && currentUser.role === 'staff') {
      currentUser.active_staff_name = staffName;
      this.setCurrentUser(currentUser);
    }
    return users[staffIndex];
  },

  getJenisSurat(): JenisSurat[] {
    const raw = localStorage.getItem(KEYS.JENIS_SURAT);
    if (!raw) {
      localStorage.setItem(KEYS.JENIS_SURAT, JSON.stringify(INITIAL_JENIS_SURAT));
      return INITIAL_JENIS_SURAT;
    }
    return JSON.parse(raw);
  },

  getWargaList(): Warga[] {
    const raw = localStorage.getItem(KEYS.WARGA);
    if (!raw) {
      localStorage.setItem(KEYS.WARGA, JSON.stringify(INITIAL_WARGA));
      return INITIAL_WARGA;
    }
    return JSON.parse(raw);
  },

  saveWargaList(list: Warga[]) {
    localStorage.setItem(KEYS.WARGA, JSON.stringify(list));
  },

  upsertWarga(warga: Warga): Warga {
    const list = this.getWargaList();
    const index = list.findIndex(w => w.id === warga.id || (warga.nik && w.nik === warga.nik));
    if (index >= 0) {
      list[index] = { ...list[index], ...warga };
    } else {
      list.unshift(warga);
    }
    this.saveWargaList(list);
    return warga;
  },

  addWarga(data: Omit<Warga, 'created_at' | 'updated_at'> & { id?: number }, actorName: string, actorRole: string): Warga {
    const list = this.getWargaList();
    const newId = data.id || (list.length > 0 ? Math.max(...list.map(w => w.id)) + 1 : 1);
    const now = new Date().toISOString().replace('T', ' ').substring(0, 19);
    const newWarga: Warga = {
      ...data,
      id: newId,
      created_at: now,
      updated_at: now,
    };
    const updated = [newWarga, ...list.filter(w => w.id !== newId && w.nik !== newWarga.nik)];
    this.saveWargaList(updated);

    this.addLog({
      user_name: actorName,
      user_role: actorRole,
      aksi: 'Tambah Data Warga',
      deskripsi: `Menambahkan data warga: ${newWarga.nama} (NIK: ${newWarga.nik})`,
      model_type: 'Warga',
      model_id: newWarga.id,
    });

    return newWarga;
  },

  updateWarga(id: number, data: Partial<Warga>, actorName: string, actorRole: string): Warga | null {
    const list = this.getWargaList();
    const index = list.findIndex(w => w.id === id);
    if (index === -1) return null;

    const now = new Date().toISOString().replace('T', ' ').substring(0, 19);
    const updatedWarga: Warga = {
      ...list[index],
      ...data,
      id,
      updated_at: now,
    };
    list[index] = updatedWarga;
    this.saveWargaList(list);

    this.addLog({
      user_name: actorName,
      user_role: actorRole,
      aksi: 'Update Data Warga',
      deskripsi: `Memperbarui data warga: ${updatedWarga.nama} (NIK: ${updatedWarga.nik})`,
      model_type: 'Warga',
      model_id: updatedWarga.id,
    });

    return updatedWarga;
  },

  deleteWarga(id: number, actorName: string, actorRole: string): { success: boolean; message?: string } {
    const suratList = this.getSuratList();
    const hasSurat = suratList.some(s => s.warga_id === id);
    if (hasSurat) {
      return {
        success: false,
        message: 'Data warga ini tidak dapat dihapus karena memiliki riwayat permohonan surat di sistem.',
      };
    }

    const list = this.getWargaList();
    const target = list.find(w => w.id === id);
    const filtered = list.filter(w => w.id !== id);
    this.saveWargaList(filtered);

    if (target) {
      this.addLog({
        user_name: actorName,
        user_role: actorRole,
        aksi: 'Hapus Data Warga',
        deskripsi: `Menghapus data kependudukan warga: ${target.nama} (NIK: ${target.nik})`,
        model_type: 'Warga',
        model_id: id,
      });
    }

    return { success: true };
  },

  getSuratList(): Surat[] {
    const raw = localStorage.getItem(KEYS.SURAT);
    if (!raw) {
      localStorage.setItem(KEYS.SURAT, JSON.stringify(INITIAL_SURAT));
      return INITIAL_SURAT;
    }
    return JSON.parse(raw);
  },

  saveSuratList(list: Surat[]) {
    localStorage.setItem(KEYS.SURAT, JSON.stringify(list));
  },

  addSurat(
    wargaId: number,
    jenisSuratId: number,
    status: 'draft' | 'diajukan',
    dataIsian: Record<string, string>,
    actor: User,
    wargaSnapshot?: Warga
  ): Surat {
    const list = this.getSuratList();
    const jenisList = this.getJenisSurat();
    const jenis = jenisList.find(j => j.id === jenisSuratId);
    const kode = jenis ? jenis.kode : 'SURAT';

    const now = new Date();
    const year = now.getFullYear();
    const dateStr = now.toISOString().split('T')[0];
    const timeStr = now.toISOString().replace('T', ' ').substring(0, 19);

    const countThisYear = list.filter(s => s.nomor_surat && s.nomor_surat.endsWith(`/${year}`)).length + 1;
    const nomorPadded = String(countThisYear).padStart(2, '0');
    const nomorSurat = `470/${nomorPadded}/${kode}/TS/${year}`;

    const authorDisplayName = actor.role === 'staff' && actor.active_staff_name
      ? `${actor.active_staff_name} (Staff Pelayanan)`
      : actor.name;

    const newSurat: Surat = {
      id: list.length > 0 ? Math.max(...list.map(s => s.id)) + 1 : 1,
      nomor_surat: nomorSurat,
      warga_id: wargaId,
      warga: wargaSnapshot,
      jenis_surat_id: jenisSuratId,
      status,
      data_isian: dataIsian,
      tanggal_pengajuan: dateStr,
      created_by: actor.id,
      created_by_name: authorDisplayName,
      created_at: timeStr,
      updated_at: timeStr,
    };

    if (wargaSnapshot) {
      this.upsertWarga(wargaSnapshot);
    }

    const updated = [newSurat, ...list];
    this.saveSuratList(updated);

    const targetWargaNama = wargaSnapshot?.nama || this.getWargaList().find(w => w.id === wargaId)?.nama || '';

    this.addLog({
      user_name: authorDisplayName,
      user_role: actor.role === 'staff' ? 'Staff Pelayanan' : 'Kepala Desa',
      aksi: status === 'draft' ? 'Simpan Draft Surat' : 'Buat Permohonan Surat',
      deskripsi: `${status === 'draft' ? 'Menyimpan draft' : 'Membuat permohonan'} surat ${jenis?.nama_jenis || ''} untuk warga ${targetWargaNama}`,
      model_type: 'Surat',
      model_id: newSurat.id,
    });

    return newSurat;
  },

  updateSurat(
    id: number,
    wargaId: number,
    jenisSuratId: number,
    status: 'draft' | 'diajukan',
    dataIsian: Record<string, string>,
    actor: User,
    wargaSnapshot?: Warga
  ): Surat | null {
    const list = this.getSuratList();
    const index = list.findIndex(s => s.id === id);
    if (index === -1) return null;

    const now = new Date().toISOString().replace('T', ' ').substring(0, 19);
    const current = list[index];

    const authorDisplayName = actor.role === 'staff' && actor.active_staff_name
      ? `${actor.active_staff_name} (Staff Pelayanan)`
      : actor.name;

    const updated: Surat = {
      ...current,
      warga_id: wargaId,
      warga: wargaSnapshot || current.warga,
      jenis_surat_id: jenisSuratId,
      status,
      data_isian: dataIsian,
      updated_at: now,
    };

    if (wargaSnapshot) {
      this.upsertWarga(wargaSnapshot);
    }

    list[index] = updated;
    this.saveSuratList(list);

    const jenisList = this.getJenisSurat();
    const jenis = jenisList.find(j => j.id === jenisSuratId);

    this.addLog({
      user_name: authorDisplayName,
      user_role: actor.role === 'staff' ? 'Staff Pelayanan' : 'Kepala Desa',
      aksi: status === 'diajukan' ? 'Pengajuan Surat ke Kades' : 'Perbarui Draft Surat',
      deskripsi: `${status === 'diajukan' ? 'Mengajukan permohonan' : 'Memperbarui draft'} surat ${jenis?.nama_jenis || ''} No. ${current.nomor_surat}`,
      model_type: 'Surat',
      model_id: id,
    });

    return updated;
  },

  attachWargaToSurat(suratId: number, warga: Warga): Surat | null {
    const list = this.getSuratList();
    const index = list.findIndex(s => s.id === suratId);
    if (index === -1) return null;

    list[index] = {
      ...list[index],
      warga_id: warga.id,
      warga: warga,
    };
    this.saveSuratList(list);
    this.upsertWarga(warga);
    return list[index];
  },

    return updated;
  },

  deleteSurat(id: number, actor: User): boolean {
    const list = this.getSuratList();
    const target = list.find(s => s.id === id);
    if (!target) return false;

    const authorDisplayName = actor.role === 'staff' && actor.active_staff_name
      ? `${actor.active_staff_name} (Staff Pelayanan)`
      : actor.name;

    const filtered = list.filter(s => s.id !== id);
    this.saveSuratList(filtered);

    this.addLog({
      user_name: authorDisplayName,
      user_role: actor.role === 'staff' ? 'Staff Pelayanan' : 'Kepala Desa',
      aksi: 'Hapus Surat',
      deskripsi: `Menghapus surat No. ${target.nomor_surat}`,
      model_type: 'Surat',
      model_id: id,
    });

    return true;
  },

  updateSuratStatus(
    id: number,
    status: 'draft' | 'diajukan' | 'disetujui' | 'ditolak',
    actor: User,
    notes?: string
  ): Surat | null {
    if (status === 'disetujui' || status === 'ditolak') {
      return this.validateSurat(id, status, notes, actor);
    }

    const list = this.getSuratList();
    const index = list.findIndex(s => s.id === id);
    if (index === -1) return null;

    const current = list[index];
    const now = new Date().toISOString().replace('T', ' ').substring(0, 19);

    const authorDisplayName = actor.role === 'staff' && actor.active_staff_name
      ? `${actor.active_staff_name} (Staff Pelayanan)`
      : actor.name;

    const updated: Surat = {
      ...current,
      status,
      updated_at: now,
    };

    list[index] = updated;
    this.saveSuratList(list);

    const jenisList = this.getJenisSurat();
    const jenis = jenisList.find(j => j.id === current.jenis_surat_id);

    this.addLog({
      user_name: authorDisplayName,
      user_role: actor.role === 'staff' ? 'Staff Pelayanan' : 'Kepala Desa',
      aksi: status === 'diajukan' ? 'Pengajuan Surat ke Kades' : `Ubah Status Surat (${status})`,
      deskripsi: `${status === 'diajukan' ? 'Mengajukan surat' : 'Mengubah status surat'} ${jenis?.nama_jenis || ''} No. ${current.nomor_surat}`,
      model_type: 'Surat',
      model_id: id,
    });

    return updated;
  },

  validateSurat(
    id: number,
    status: 'disetujui' | 'ditolak',
    notes: string | undefined,
    actor: User
  ): Surat | null {
    const list = this.getSuratList();
    const index = list.findIndex(s => s.id === id);
    if (index === -1) return null;

    const current = list[index];
    const now = new Date().toISOString().replace('T', ' ').substring(0, 19);
    const dateFormatted = now.substring(0, 10).replace(/-/g, '');

    const updated: Surat = {
      ...current,
      status,
      updated_at: now,
    };

    if (status === 'disetujui' || status === 'ditolak') {
      updated.validasi = {
        id: current.validasi?.id || Date.now(),
        surat_id: id,
        validated_by: actor.id,
        validator_name: actor.name,
        status,
        qr_code_token: `QR-TS-${updated.nomor_surat.replace(/\//g, '-')}-${dateFormatted}`,
        catatan: notes,
        validated_at: now,
      };
    }

    list[index] = updated;
    this.saveSuratList(list);

    const jenisList = this.getJenisSurat();
    const jenis = jenisList.find(j => j.id === current.jenis_surat_id);

    this.addLog({
      user_name: actor.name,
      user_role: 'Kepala Desa',
      aksi: status === 'disetujui' ? 'Validasi Surat Disetujui' : 'Validasi Surat Ditolak',
      deskripsi: `${status === 'disetujui' ? 'Menyetujui & menerbitkan e-signature' : 'Menolak'} surat ${jenis?.nama_jenis || ''} No. ${current.nomor_surat}${notes ? ` (Catatan: ${notes})` : ''}`,
      model_type: 'Surat',
      model_id: id,
    });

    return updated;
  },

  getLogs(): ActivityLog[] {
    const raw = localStorage.getItem(KEYS.LOGS);
    if (!raw) {
      localStorage.setItem(KEYS.LOGS, JSON.stringify(INITIAL_LOGS));
      return INITIAL_LOGS;
    }
    return JSON.parse(raw);
  },

  addLog(log: Omit<ActivityLog, 'id' | 'created_at'>) {
    const list = this.getLogs();
    const newId = list.length > 0 ? Math.max(...list.map(l => l.id)) + 1 : 1;
    const now = new Date().toISOString().replace('T', ' ').substring(0, 19);
    const newLog: ActivityLog = {
      ...log,
      id: newId,
      created_at: now,
    };
    const updated = [newLog, ...list];
    localStorage.setItem(KEYS.LOGS, JSON.stringify(updated.slice(0, 150)));
  },

  getCurrentUser(): User | null {
    const raw = localStorage.getItem(KEYS.CURRENT_USER);
    if (!raw) return null;
    return JSON.parse(raw);
  },

  setCurrentUser(user: User | null) {
    if (!user) {
      localStorage.removeItem(KEYS.CURRENT_USER);
    } else {
      localStorage.setItem(KEYS.CURRENT_USER, JSON.stringify(user));
    }
  },

  resetToDefault() {
    localStorage.setItem(KEYS.USERS, JSON.stringify(INITIAL_USERS));
    localStorage.setItem(KEYS.JENIS_SURAT, JSON.stringify(INITIAL_JENIS_SURAT));
    localStorage.setItem(KEYS.WARGA, JSON.stringify(INITIAL_WARGA));
    localStorage.setItem(KEYS.SURAT, JSON.stringify(INITIAL_SURAT));
    localStorage.setItem(KEYS.LOGS, JSON.stringify(INITIAL_LOGS));
  }
};
