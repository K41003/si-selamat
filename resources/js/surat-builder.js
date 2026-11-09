/* ---------------------------------------------------------------------------
   Halaman "Buat Surat": pilih jenis surat, isi data warga langsung pada surat.

   Kontrak dengan backend (SuratController@store) TIDAK berubah:
   warga_id, jenis_surat_id, action (draft|ajukan), data_isian[...].
   Pencarian memakai endpoint yang sudah ada: route('warga.api.search')?q=
   --------------------------------------------------------------------------- */
import Alpine from "alpinejs";

const BULAN = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
    "Juli", "Agustus", "September", "Oktober", "November", "Desember",
];

const formatTanggal = (value) => {
    if (!value) return "";
    if (String(value).includes("/")) {
        const [d, m, y] = String(value).split("/");
        if (d && m && y && BULAN[Number(m) - 1]) return `${Number(d)} ${BULAN[Number(m) - 1]} ${y}`;
        return String(value);
    }
    const [y, m, d] = String(value).slice(0, 10).split("-");
    if (!y || !m || !d) return String(value);
    return `${Number(d)} ${BULAN[Number(m) - 1]} ${y}`;
};

const maskNik = (nik) =>
    nik && nik.length >= 8 ? `${nik.slice(0, 4)}********${nik.slice(-4)}` : nik || "";

Alpine.data("suratBuilder", (config) => ({
    searchUrl: config.searchUrl,
    jenisList: config.jenis || [],
    jenisId: Number(config.jenisId) || (config.jenis?.[0]?.id ?? 0),
    intro: config.intro || {},
    today: config.today || "",
    isian: config.isian || {},
    warga: config.warga || null,

    // Tiga kolom pencarian yang tampil di dalam surat (urutan sama dengan surat cetak)
    form: { nama: "", nik: "", kk: "" },
    searchFields: [
        { key: "nama", label: "Nama Lengkap", placeholder: "Ketik nama warga…", inputmode: "text", maxlength: 100 },
        { key: "nik", label: "NIK", placeholder: "16 digit NIK", inputmode: "numeric", maxlength: 16 },
        { key: "kk", label: "Nomor Kartu Keluarga", placeholder: "16 digit No. KK", inputmode: "numeric", maxlength: 16 },
    ],

    activeField: null,
    results: [],
    open: false,
    active: -1,
    loading: false,
    searched: false,
    error: "",
    announce: "",
    timer: null,
    controller: null,

    init() {
        if (this.warga) this.fillForm(this.warga);
    },

    // ---- turunan data -------------------------------------------------------
    get jenis() {
        return this.jenisList.find((j) => Number(j.id) === Number(this.jenisId)) || null;
    },
    get fields() {
        return this.jenis?.fields || this.jenis?.template_field || [];
    },
    get introParts() {
        return this.intro[this.jenis?.kode] || this.intro.DEFAULT || [];
    },
    get autoRows() {
        const w = this.warga;
        const rtrw = w && (w.rt || w.rw) ? ` RT ${w.rt || "-"} / RW ${w.rw || "-"}` : "";
        return [
            { label: "Tempat / Tanggal Lahir", value: w ? `${w.tempat_lahir || "-"}, ${formatTanggal(w.tanggal_lahir)}` : "" },
            { label: "Jenis Kelamin", value: w?.jenis_kelamin || "" },
            { label: "Agama", value: w?.agama || "" },
            { label: "Pekerjaan", value: w?.pekerjaan || "" },
            { label: "Status Perkawinan", value: w?.status_perkawinan || "" },
            { label: "Alamat Tempat Tinggal", value: w ? `${w.alamat || ""}${rtrw}` : "" },
        ];
    },
    mask: maskNik,

    // ---- pencarian ----------------------------------------------------------
    fillForm(w) {
        this.form = { nama: w.nama || "", nik: w.nik || "", kk: w.no_kk || "" };
    },

    onInput(key, el) {
        let value = el.value;
        if (key !== "nama") {
            value = value.replace(/\D/g, "");
            el.value = value; // buang huruf yang terlanjur diketik
        }

        // Mengubah kolom setelah warga dipilih = memulai pencarian baru.
        if (this.warga) {
            this.warga = null;
            this.form = { nama: "", nik: "", kk: "" };
        }
        this.form[key] = value;
        this.error = "";
        this.activeField = key;

        clearTimeout(this.timer);
        const q = value.trim();
        if (q.length < 3) {
            this.cancelSearch();
            this.results = [];
            this.open = false;
            this.searched = false;
            return;
        }
        this.timer = setTimeout(() => this.search(key, q), 300);
    },

    cancelSearch() {
        if (this.controller) this.controller.abort();
        this.controller = null;
        this.loading = false;
    },

    async search(key, q) {
        this.cancelSearch();
        const ctrl = new AbortController();
        this.controller = ctrl;
        this.loading = true;
        this.open = true;
        this.results = [];
        this.active = -1;

        try {
            const res = await fetch(`${this.searchUrl}?q=${encodeURIComponent(q)}`, {
                headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" },
                signal: ctrl.signal,
            });
            if (!res.ok) throw new Error(String(res.status));
            const data = await res.json();
            if (this.form[key].trim() !== q) return; // hasil basi, pengguna sudah mengetik lagi

            this.results = Array.isArray(data) ? data : [];
            this.searched = true;
            this.active = this.results.length ? 0 : -1;

            // NIK 16 digit yang persis cocok -> langsung terisi
            if (q.length === 16) {
                const exact =
                    key === "nik"
                        ? this.results.find((w) => w.nik === q)
                        : key === "kk" && this.results.length === 1
                          ? this.results[0]
                          : null;
                if (exact) {
                    this.pick(exact);
                    return;
                }
            }
            this.announce = this.results.length
                ? `${this.results.length} warga ditemukan. Gunakan panah atas dan bawah, lalu Enter.`
                : "Tidak ada warga yang cocok.";
        } catch (e) {
            if (e.name === "AbortError") return;
            this.error = "Pencarian gagal. Periksa koneksi, lalu coba lagi.";
            this.open = false;
        } finally {
            if (this.controller === ctrl) this.loading = false;
        }
    },

    pick(w) {
        this.warga = w;
        this.fillForm(w);
        this.results = [];
        this.open = false;
        this.active = -1;
        this.searched = false;
        this.error = "";
        this.announce = `Data ${w.nama} terisi pada surat.`;
    },

    clearWarga() {
        this.warga = null;
        this.form = { nama: "", nik: "", kk: "" };
        this.results = [];
        this.open = false;
        this.announce = "Pilihan warga dihapus.";
        this.$nextTick(() => document.getElementById("f-nama")?.focus());
    },

    move(delta) {
        if (!this.open || !this.results.length) return;
        this.active = (this.active + delta + this.results.length) % this.results.length;
        this.$nextTick(() =>
            document.getElementById(`warga-opt-${this.active}`)?.scrollIntoView({ block: "nearest" }),
        );
    },

    choose() {
        // Enter TIDAK boleh mengirim form; hanya memilih saran bila daftar terbuka.
        if (this.open && this.results[this.active]) this.pick(this.results[this.active]);
    },

    // ---- kirim --------------------------------------------------------------
    onSubmit(event) {
        if (!this.warga) {
            event.preventDefault();
            this.error =
                "Pilih warga dulu: ketik minimal 3 karakter pada Nama, NIK, atau No. KK, lalu pilih dari daftar.";
            document.getElementById("f-nama")?.focus();
        }
    },
}));
