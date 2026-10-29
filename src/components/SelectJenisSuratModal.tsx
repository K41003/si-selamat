import React, { useState } from 'react';
import { StorageService } from '../services/storage';
import { FileText, X, ArrowRight, ChevronDown } from 'lucide-react';

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
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs p-4 animate-in fade-in duration-200">
      <div className="w-full max-w-lg rounded-3xl bg-white p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-5">
        {/* Header */}
        <div className="flex items-start justify-between border-b border-slate-100 pb-3">
          <div className="flex items-center gap-3">
            <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#1E3A8A]/10 text-[#1E3A8A] border border-[#1E3A8A]/20 font-bold shadow-xs">
              <FileText className="h-5 w-5 stroke-[1.8]" />
            </div>
            <div>
              <h3 className="text-base font-bold text-slate-900">Pilih Jenis Surat</h3>
              <p className="text-xs text-slate-500">Pilih format surat dari menu dropdown</p>
            </div>
          </div>
          <button
            type="button"
            onClick={onClose}
            aria-label="Tutup"
            className="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition"
          >
            <X className="h-5 w-5 stroke-[1.8]" />
          </button>
        </div>

        {/* Dropdown Selector */}
        <div className="space-y-2">
          <label htmlFor="select-jenis-surat-dropdown" className="text-xs font-semibold text-slate-700 block">
            Jenis / Format Surat Resmi:
          </label>
          <div className="relative">
            <select
              id="select-jenis-surat-dropdown"
              data-testid="select-jenis-surat-dropdown"
              value={selectedId}
              onChange={e => setSelectedId(Number(e.target.value))}
              className="w-full appearance-none rounded-xl border border-slate-200 bg-white px-4 py-3 pr-10 text-xs sm:text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none focus:ring-2 focus:ring-[#1E3A8A]/20 transition cursor-pointer shadow-2xs"
            >
              {jenisList.map(j => (
                <option key={j.id} value={j.id} className="py-2 text-slate-900">
                  [{j.kode}] &mdash; {j.nama_jenis}
                </option>
              ))}
            </select>
            <div className="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400">
              <ChevronDown className="h-4 w-4 stroke-[2]" />
            </div>
          </div>
        </div>

        {/* Selected Letter Summary Box */}
        {currentSelected && (
          <div className="rounded-2xl bg-slate-50 p-4 border border-slate-200 flex items-center justify-between gap-4">
            <div className="min-w-0">
              <span className="font-mono text-[11px] font-semibold text-[#1E3A8A] bg-[#1E3A8A]/10 border border-[#1E3A8A]/20 px-2.5 py-0.5 rounded-md">
                Kode: {currentSelected.kode}
              </span>
              <p className="text-sm font-bold text-slate-900 mt-1 truncate">{currentSelected.nama_jenis}</p>
            </div>
            <span className="text-xs font-semibold text-[#1E3A8A] bg-[#1E3A8A]/10 px-3 py-1 rounded-full border border-[#1E3A8A]/20 shrink-0">
              {currentSelected.template_field?.length || 0} Kolom Isian
            </span>
          </div>
        )}

        {/* Action Buttons */}
        <div className="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
          <button
            type="button"
            onClick={onClose}
            className="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
          >
            Batal
          </button>
          <button
            type="button"
            id="btn-confirm-jenis-surat"
            onClick={handleConfirm}
            className="inline-flex items-center gap-1.5 rounded-xl bg-[#1E3A8A] hover:bg-[#172554] px-5 py-2.5 text-xs font-semibold text-white transition active:scale-95 shadow-sm"
          >
            <span>Buka Template Surat</span>
            <ArrowRight className="h-3.5 w-3.5 stroke-[2]" />
          </button>
        </div>
      </div>
    </div>
  );
};
