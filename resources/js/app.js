import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

/* ---------------------------------------------------------------------------
   Pembantu UI (Fase 2)
   --------------------------------------------------------------------------- */

// Buka <x-confirm-dialog>: <button onclick="openDialog('setujui-12')">
window.openDialog = (id) => {
    const dialog = document.getElementById(id);
    if (dialog && typeof dialog.showModal === "function") dialog.showModal();
};

// Cegah kirim ganda: nonaktifkan tombol submit setelah form dikirim.
// Tidak berlaku untuk form GET (filter/cari), form yang dibatalkan (mis. confirm() ditolak),
// atau form dengan atribut data-allow-resubmit.
document.addEventListener("submit", (event) => {
    const form = event.target;
    if (
        !(form instanceof HTMLFormElement) ||
        event.defaultPrevented ||
        form.method.toLowerCase() === "get" ||
        form.hasAttribute("data-allow-resubmit")
    ) {
        return;
    }
    const buttons = form.querySelectorAll('button[type="submit"], button:not([type])');
    // setTimeout: tombol pengirim baru dinonaktifkan SETELAH data form terkumpul.
    setTimeout(() => {
        buttons.forEach((button) => {
            button.disabled = true;
            button.setAttribute("aria-busy", "true");
        });
    }, 0);
});

// Tombol "Kembali" di browser (bfcache) tidak boleh meninggalkan tombol dalam keadaan nonaktif.
window.addEventListener("pageshow", (event) => {
    if (!event.persisted) return;
    document.querySelectorAll('button[aria-busy="true"]').forEach((button) => {
        button.disabled = false;
        button.removeAttribute("aria-busy");
    });
});
