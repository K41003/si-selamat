import React from 'react';
import { StorageService } from '../services/storage';
import { ArrowLeft, Printer, Download } from 'lucide-react';

interface SuratPrintPreviewProps {
  suratId: number;
  onNavigate: (view: string) => void;
}

export const SuratPrintPreview: React.FC<SuratPrintPreviewProps> = ({ suratId, onNavigate }) => {
  const suratList = StorageService.getSuratList();
  const wargaList = StorageService.getWargaList();
  const jenisList = StorageService.getJenisSurat();

  const surat = suratList.find(s => s.id === suratId);
  const warga = wargaList.find(w => w.id === surat?.warga_id);
  const jenis = jenisList.find(j => j.id === surat?.jenis_surat_id);

  if (!surat || !warga || !jenis) {
    return (
      <div className="rounded-xl bg-white p-8 text-center shadow-xs ring-1 ring-slate-200">
        <p className="text-slate-500">Berkas surat tidak lengkap untuk dicetak.</p>
        <button
          onClick={() => onNavigate('arsip')}
          className="mt-4 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
        >
          Kembali
        </button>
      </div>
    );
  }

  const handlePrint = () => {
    window.print();
  };

  const currentDate = new Date().toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  });

  return (
    <div className="space-y-6">
      {/* Top Control Bar (Hidden on print) */}
      <div className="flex flex-wrap items-center justify-between gap-3 print:hidden">
        <div className="flex items-center gap-3">
          <button
            onClick={() => onNavigate('arsip')}
            className="rounded-lg border border-slate-200 bg-white p-2 text-slate-600 hover:bg-slate-50 transition"
          >
            <ArrowLeft className="h-4 w-4" />
          </button>
          <div>
            <h1 className="text-xl font-bold text-slate-900">Pratinjau Cetak Surat Resmi</h1>
            <p className="text-xs text-slate-500">Format surat baku Desa Tanjung Selamat dengan tanda tangan elektronik.</p>
          </div>
        </div>
        <div className="flex items-center gap-2">
          <button
            id="btn-print-surat"
            onClick={handlePrint}
            className="flex items-center gap-1.5 rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800 transition shadow-xs"
          >
            <Printer className="h-4 w-4" />
            Cetak / Simpan PDF
          </button>
        </div>
      </div>

      {/* Official A4 Document Sheet */}
      <div
        id="printable-surat"
        className="mx-auto max-w-2xl bg-white p-10 shadow-lg ring-1 ring-slate-200 print:max-w-none print:p-0 print:shadow-none print:ring-0 text-black font-serif leading-relaxed"
      >
        {/* Kop Surat Desa Tanjung Selamat */}
        <div className="border-b-4 border-double border-black pb-4 text-center">
          <h3 className="text-sm font-bold uppercase tracking-wider">Pemerintah Kabupaten Deli Serdang</h3>
          <h4 className="text-sm font-bold uppercase tracking-wider">Kecamatan Sunggal</h4>
          <h2 className="text-xl font-extrabold uppercase tracking-wide mt-0.5">Pemerintah Desa Tanjung Selamat</h2>
          <p className="text-xs italic mt-1 font-sans text-slate-700">
            Alamat: Jl. Flamboyan No. 1, Desa Tanjung Selamat, Kec. Sunggal, Kab. Deli Serdang 20351
          </p>
        </div>

        {/* Judul & Nomor Surat */}
        <div className="mt-6 text-center">
          <h3 className="text-base font-bold underline uppercase tracking-wide">
            {jenis.nama_jenis}
          </h3>
          <p className="text-xs font-mono font-medium mt-0.5">
            Nomor: {surat.nomor_surat}
          </p>
        </div>

        {/* Pembuka */}
        <div className="mt-6 text-sm">
          <p>
            Yang bertanda tangan di bawah ini, Kepala Desa Tanjung Selamat, Kecamatan Sunggal, Kabupaten Deli Serdang, dengan ini menerangkan bahwa:
          </p>
        </div>

        {/* Biodata Warga */}
        <div className="mt-4 pl-4 text-sm">
          <table className="w-full text-sm">
            <tbody>
              <tr>
                <td className="w-40 py-1 font-medium">Nama Lengkap</td>
                <td className="w-4 py-1">:</td>
                <td className="py-1 font-bold uppercase">{warga.nama}</td>
              </tr>
              <tr>
                <td className="py-1 font-medium">NIK</td>
                <td className="py-1">:</td>
                <td className="py-1 font-mono">{warga.nik}</td>
              </tr>
              <tr>
                <td className="py-1 font-medium">Tempat / Tgl. Lahir</td>
                <td className="py-1">:</td>
                <td className="py-1">{warga.tempat_lahir || '-'}, {warga.tanggal_lahir || '-'}</td>
              </tr>
              <tr>
                <td className="py-1 font-medium">Jenis Kelamin</td>
                <td className="py-1">:</td>
                <td className="py-1">{warga.jenis_kelamin || '-'}</td>
              </tr>
              <tr>
                <td className="py-1 font-medium">Agama</td>
                <td className="py-1">:</td>
                <td className="py-1">{warga.agama || '-'}</td>
              </tr>
              <tr>
                <td className="py-1 font-medium">Pekerjaan</td>
                <td className="py-1">:</td>
                <td className="py-1">{warga.pekerjaan || '-'}</td>
              </tr>
              <tr>
                <td className="py-1 font-medium">Alamat</td>
                <td className="py-1">:</td>
                <td className="py-1">{warga.alamat || '-'} (RT {warga.rt || '01'}/RW {warga.rw || '01'})</td>
              </tr>
            </tbody>
          </table>
        </div>

        {/* Isi Spesifik Surat Berdasarkan Jenis */}
        <div className="mt-5 text-sm space-y-3">
          {jenis.kode === 'SKD' && (
            <p>
              Benar yang bersangkutan adalah warga yang berdomisili dan bertempat tinggal sah di alamat tersebut di atas di lingkungan wilayah Desa Tanjung Selamat. Surat keterangan ini dibuat untuk keperluan: <strong>{surat.data_isian['keperluan'] || 'Keperluan administrasi.'}</strong>
            </p>
          )}

          {jenis.kode === 'SKTM' && (
            <p>
              Berdasarkan catatan kependudukan dan pemantauan di lapangan, yang bersangkutan benar-benar tergolong dalam keluarga kurang mampu/ekonomi lemah. Surat keterangan ini dikeluarkan untuk keperluan: <strong>{surat.data_isian['keperluan'] || 'Pengajuan beasiswa / bantuan.'}</strong>
            </p>
          )}

          {jenis.kode === 'SKU' && (
            <div>
              <p>Benar nama tersebut di atas memiliki usaha aktif sebagai berikut:</p>
              <div className="mt-2 pl-4">
                <table className="w-full text-sm">
                  <tbody>
                    <tr>
                      <td className="w-36 py-0.5 font-medium">Nama Usaha</td>
                      <td className="w-4 py-0.5">:</td>
                      <td className="py-0.5 font-semibold">{surat.data_isian['nama_usaha'] || '-'}</td>
                    </tr>
                    <tr>
                      <td className="py-0.5 font-medium">Jenis Usaha</td>
                      <td className="py-0.5">:</td>
                      <td className="py-0.5">{surat.data_isian['jenis_usaha'] || '-'}</td>
                    </tr>
                    <tr>
                      <td className="py-0.5 font-medium">Alamat Usaha</td>
                      <td className="py-0.5">:</td>
                      <td className="py-0.5">{surat.data_isian['alamat_usaha'] || '-'}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {jenis.kode === 'SKKL' && (
            <div>
              <p>Menerangkan bahwa telah lahir seorang anak dengan data:</p>
              <div className="mt-2 pl-4">
                <table className="w-full text-sm">
                  <tbody>
                    <tr>
                      <td className="w-36 py-0.5 font-medium">Nama Anak</td>
                      <td className="w-4 py-0.5">:</td>
                      <td className="py-0.5 font-semibold">{surat.data_isian['nama_anak'] || '-'}</td>
                    </tr>
                    <tr>
                      <td className="py-0.5 font-medium">Tanggal Lahir</td>
                      <td className="py-0.5">:</td>
                      <td className="py-0.5">{surat.data_isian['tanggal_lahir_anak'] || '-'}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {jenis.kode === 'SPPK' && (
            <p>
              Surat pengantar ini diterbitkan sesuai dengan data kependudukan resmi untuk keperluan: <strong>{surat.data_isian['keperluan'] || 'Keperluan pengantar.'}</strong>
            </p>
          )}

          <p className="pt-2">
            Demikian surat keterangan ini kami terbitkan dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.
          </p>
        </div>

        {/* Tanda Tangan & QR E-Signature */}
        <div className="mt-10 flex justify-end">
          <div className="w-64 text-center text-sm">
            <p>Tanjung Selamat, {currentDate}</p>
            <p className="font-semibold">Kepala Desa Tanjung Selamat</p>

            {/* QR Code Validation Box */}
            <div className="my-3 flex flex-col items-center justify-center p-2 rounded border border-slate-300">
              {/* Scalable SVG QR Representation */}
              <svg viewBox="0 0 100 100" className="h-20 w-20 text-slate-900 fill-current">
                <path d="M10,10 h30 v30 h-30 z M15,15 v20 h20 v-20 z M20,20 h10 v10 h-10 z" />
                <path d="M60,10 h30 v30 h-30 z M65,15 v20 h20 v-20 z M70,20 h10 v10 h-10 z" />
                <path d="M10,60 h30 v30 h-30 z M15,65 v20 h20 v-20 z M20,70 h10 v10 h-10 z" />
                <rect x="45" y="15" width="8" height="8" />
                <rect x="45" y="30" width="8" height="8" />
                <rect x="45" y="45" width="8" height="8" />
                <rect x="15" y="45" width="8" height="8" />
                <rect x="30" y="45" width="8" height="8" />
                <rect x="60" y="45" width="10" height="10" />
                <rect x="75" y="45" width="15" height="8" />
                <rect x="45" y="60" width="8" height="20" />
                <rect x="60" y="65" width="25" height="8" />
                <rect x="60" y="80" width="12" height="10" />
                <rect x="78" y="80" width="12" height="10" />
              </svg>
              <span className="text-[9px] font-mono mt-1 text-slate-600 block">
                {surat.validasi?.qr_code_token || `QR-TS-E-SIGN-${surat.id}`}
              </span>
              <span className="text-[8px] font-sans uppercase font-bold tracking-wider text-emerald-800">
                Tersertifikasi Elektronik
              </span>
            </div>

            <p className="font-bold underline uppercase">H. SUTISNA, S.Sos.</p>
            <p className="text-xs font-sans">NIP. 19740512 200212 1 003</p>
          </div>
        </div>
      </div>
    </div>
  );
};
