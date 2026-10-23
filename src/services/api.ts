import { Warga } from '../types';

export interface PaginatedWargaResponse {
  status: string;
  data: Warga[];
  total: number;
  current_page: number;
  last_page: number;
  per_page: number;
}

export interface StatsResponse {
  total_warga: number;
  total_surat: number;
  total_draft: number;
  total_diajukan: number;
  total_disetujui: number;
}

export const ApiService = {
  async getWarga(page = 1, perPage = 8, q = ''): Promise<PaginatedWargaResponse | null> {
    try {
      const res = await fetch(`/api/warga?page=${page}&per_page=${perPage}&q=${encodeURIComponent(q)}`);
      if (!res.ok) return null;
      return await res.json();
    } catch (e) {
      console.warn('API getWarga error, falling back to local:', e);
      return null;
    }
  },

  async getWargaByNik(nik: string): Promise<Warga | null> {
    try {
      const res = await fetch(`/api/warga?nik=${encodeURIComponent(nik)}`);
      if (!res.ok) return null;
      const json = await res.json();
      return json.data && json.data.length > 0 ? json.data[0] : null;
    } catch {
      return null;
    }
  },

  async getWargaById(id: number): Promise<Warga | null> {
    try {
      const res = await fetch(`/api/warga/${id}`);
      if (!res.ok) return null;
      const json = await res.json();
      return json.data || null;
    } catch {
      return null;
    }
  },

  async getStats(): Promise<StatsResponse | null> {
    try {
      const res = await fetch('/api/stats');
      if (!res.ok) return null;
      const json = await res.json();
      return json.data || null;
    } catch {
      return null;
    }
  },

  async deleteWarga(id: number): Promise<boolean> {
    try {
      const res = await fetch(`/api/warga/${id}`, { method: 'DELETE' });
      return res.ok;
    } catch {
      return false;
    }
  },

  async createWarga(warga: Partial<Warga>): Promise<Warga | null> {
    try {
      const res = await fetch('/api/warga', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(warga),
      });
      if (!res.ok) return null;
      const json = await res.json();
      return json.data;
    } catch {
      return null;
    }
  },

  async updateWarga(id: number, warga: Partial<Warga>): Promise<Warga | null> {
    try {
      const res = await fetch(`/api/warga/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(warga),
      });
      if (!res.ok) return null;
      const json = await res.json();
      return json.data;
    } catch {
      return null;
    }
  }
};
