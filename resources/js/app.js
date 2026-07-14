/**
 * PENTING: Jangan import & start Alpine.js secara manual di file ini.
 *
 * Livewire (v3 & v4) sudah membawa Alpine.js sendiri secara otomatis lewat
 * @livewireScripts di layout, dan langsung menjalankan Alpine.start() untuk kamu.
 *
 * Kalau kita panggil Alpine.start() lagi secara manual di sini, Alpine akan
 * error "Alpine has already been initialized on this page" dan menyebabkan
 * semua directive Alpine (x-data, x-show, @click, dst) serta wire:click
 * jadi tidak responsif sama sekali — inilah sebab notifikasi tidak
 * meng-redirect kemanapun saat diklik.
 *
 * Kalau butuh akses ke Alpine secara manual (misal untuk plugin tambahan),
 * dengarkan event 'alpine:init' yang di-dispatch oleh Livewire, contoh:
 *
 * document.addEventListener('alpine:init', () => {
 *     Alpine.data('namaComponent', () => ({ ... }));
 * });
 *
*
*import Alpine from 'alpinejs';
*
*window.Alpine = Alpine;
*
*Alpine.start();
*/
