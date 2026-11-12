/* ---------------------------------------------------------------------------
   Halaman "Buat Surat": pilih jenis surat, isi data warga langsung pada surat.

   Kontrak dengan backend (SuratController@store) TIDAK berubah:
   warga_id, jenis_surat_id, action (draft|ajukan), data_isian[...].
   Pencarian memakai endpoint yang sudah ada: route('warga.cari')?q=
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
    isian: Array.isArray(config.isian) ? {} : { ...(config.isian || {}) },
    pengikut: Object.values((config.isian && config.isian.pengikut) || {}).map((p) => ({
        nama: p.nama || "",
        jenis_kelamin: p.jenis_kelamin || "",
        tanggal_lahir: p.tanggal_lahir || "",
        hubungan: p.hubungan || "",
    })),
    warga: config.warga || null,

    // Tiga kolom pencarian yang tampil di dalam surat (urutan sama dengan surat cetak)
    form: { nama: "", nik: "", kk: "" },
    notFound: { nama: false, nik: false, kk: false },
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
    get hasNotFound() {
        return !this.warga && (Boolean(this.notFound.nama) || Boolean(this.notFound.nik) || Boolean(this.notFound.kk));
    },
    get notFoundSummary() {
        const parts = [];
        if (this.notFound.nama) parts.push(`Nama "${this.notFound.nama}"`);
        if (this.notFound.nik) parts.push(`NIK "${this.notFound.nik}"`);
        if (this.notFound.kk) parts.push(`No. KK "${this.notFound.kk}"`);
        return parts.join(", ");
    },
    get kode() {
        return this.jenis?.kode || "";
    },
    addPengikut() {
        this.pengikut.push({ nama: "", jenis_kelamin: "", tanggal_lahir: "", hubungan: "" });
    },
    removePengikut(i) {
        this.pengikut.splice(i, 1);
    },
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
        const v = {
            ttl: w ? `${w.tempat_lahir || "-"}, ${formatTanggal(w.tanggal_lahir)}` : "",
            jk: w?.jenis_kelamin || "",
            agama: w ? `Indonesia / ${w.agama || ""}` : "",
            status: w?.status_perkawinan || "",
            kerja: w?.pekerjaan || "",
            alamat: w ? `${w.alamat || ""}${rtrw}` : "",
        };
        const statusLabel = this.kode === "SKTM" ? "Status" : "Status Perkawinan";
        const ttl = { label: "Tempat/Tanggal Lahir", value: v.ttl };
        const jk = { label: "Jenis Kelamin", value: v.jk };
        const agama = { label: "Bangsa / Agama", value: v.agama };
        const status = { label: statusLabel, value: v.status };
        const kerja = { label: "Pekerjaan", value: v.kerja };
        const alamat = { label: "Alamat", value: v.alamat };
        // Urutan baris sama dengan surat cetak masing-masing jenis
        if (this.kode === "SKTM") return [ttl, jk, agama, status, kerja, alamat];
        if (this.kode === "SKD") return [ttl, jk, agama, status, kerja, alamat];
        return [ttl, jk, agama, kerja, status, alamat];
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
            this.notFound = { nama: false, nik: false, kk: false };
        }
        this.form[key] = value;
        this.notFound[key] = false;
        this.error = "";
        this.activeField = key;

        clearTimeout(this.timer);
        const q = value.trim();
        if (q.length < 3) {
            this.cancelSearch();
            this.results = [];
            this.open = false;
            this.searched = false;
            this.notFound[key] = false;
            return;
        }
        this.timer = setTimeout(() => this.search(key, q), 300);
    },

    onBlur(key) {
        // Jangan langsung tutup agar klik item autocomplete tetap berfungsi
        setTimeout(() => {
            if (this.activeField === key) {
                this.open = false;
            }
        }, 250);

        const q = (this.form[key] || "").trim();
        if (!this.warga && q.length >= 3 && !this.loading && this.results.length === 0 && !this.notFound[key]) {
            this.search(key, q);
        }
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

            if (this.results.length === 0) {
                this.notFound[key] = q;
            } else {
                this.notFound[key] = false;
            }

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
        this.notFound = { nama: false, nik: false, kk: false };
        this.open = false;
        this.active = -1;
        this.searched = false;
        this.error = "";
        this.announce = `Data ${w.nama} terisi pada surat.`;
    },

    clearWarga() {
        this.warga = null;
        this.form = { nama: "", nik: "", kk: "" };
        this.notFound = { nama: false, nik: false, kk: false };
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
            this.error = this.hasNotFound
                ? `Data warga (${this.notFoundSummary}) tidak ada di database. Silakan periksa kembali atau daftarkan warga baru terlebih dahulu.`
                : "Pilih warga dulu: ketik minimal 3 karakter pada Nama, NIK, atau No. KK, lalu pilih dari daftar saran.";
            document.getElementById("f-nama")?.focus();
        }
    },
}));
