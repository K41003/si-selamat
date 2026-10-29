import React, { useState } from 'react';
import { StorageService } from '../services/storage';
import { FileText, X, ArrowRight, Layers, ChevronDown } from 'lucide-react';

interface SelectJenisSuratModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSelect: (jenisId: number) => void;
}

export const SelectJenisSuratModal: React.FC<SelectJenisSuratModalProps> = ({
  isOpen,
  onClose,
  onSelect,
}) => {
  const jenisList = StorageService.getJenisSurat();
  const [selectedId, setSelectedId] = useState<number>(jenisList[0]?.id || 1);

  if (!isOpen) return null;

  const currentSelected = jenisList.find(j => j.id === selectedId) || jenisList[0];

  const handleConfirm = () => {
    if (selectedId) {
      onSelect(selectedId);
      onClose();
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 animate-in fade-in duration-200">
      <div className="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200 space-y-5">
        {/* Header */}
        <div className="flex items-start justify-between border-b border-slate-100 pb-3">
          <div className="flex items-center gap-3">
            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10">
              <FileText className="h-5 w-5" />
            </div>
            <div>
              <h3 className="text-base font-bold text-slate-900">Buat Permohonan Surat</h3>
              <p className="text-xs text-slate-500">Pilih jenis surat yang ingin dibuat</p>
            </div>
          </div>
          <button
            type="button"
            onClick={onClose}
            className="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition"
          >
            <X className="h-4 w-4" />
          </button>
        </div>

        {/* Dropdown Box */}
        <div className="space-y-2">
          <label htmlFor="select-jenis-surat" className="text-xs font-bold uppercase tracking-wider text-slate-700">
            Pilih Jenis Surat
          </label>
          <div className="relative">
            <select
              id="select-jenis-surat"
              value={selectedId}
              onChange={e => setSelectedId(Number(e.target.value))}
              className="w-full appearance-none rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-800 shadow-xs focus:border-slate-600 focus:outline-none focus:ring-1 focus:ring-slate-600 pr-10"
              autoFocus
            >
              {jenisList.map(j => (
                <option key={j.id} value={j.id}>
                  [{j.kode}] - {j.nama_jenis}
                </option>
              ))}
            </select>
            <div className="pointer-events-none absolute right-3.5 top-3.5 text-slate-500">
              <ChevronDown className="h-4 w-4" />
            </div>
          </div>
        </div>

        {/* Selected Letter Preview Box */}
        {currentSelected && (
          <div className="rounded-xl bg-slate-50 p-4 text-xs space-y-2 border border-slate-200/80">
            <div className="flex items-center justify-between">
              <span className="font-mono font-bold text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded">
                Kode: {currentSelected.kode}
              </span>
              <span className="text-slate-400 flex items-center gap-1">
                <Layers className="h-3.5 w-3.5" />
                {currentSelected.template_field.length} isian data
              </span>
            </div>
            <p className="font-semibold text-slate-800 text-sm">{currentSelected.nama_jenis}</p>
            <p className="text-slate-500 leading-relaxed">{currentSelected.deskripsi}</p>
          </div>
        )}

        {/* Action Buttons */}
        <div className="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
          <button
            type="button"
            onClick={onClose}
            className="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
          >
            Batal
          </button>
          <button
            type="button"
            id="btn-confirm-jenis-surat"
            onClick={handleConfirm}
            className="flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-xs font-bold text-white hover:bg-slate-800 transition shadow-sm"
          >
            <span>Buka Template Surat</span>
            <ArrowRight className="h-3.5 w-3.5 text-emerald-400" />
          </button>
        </div>
      </div>
    </div>
  );
};
