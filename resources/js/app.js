import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('click', (event) => {
    const link = event.target.closest?.('a[href*="wa.me"]');
    if (!link) {
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const body = new FormData();
    if (token) {
        body.append('_token', token);
    }
    body.append('placement', link.dataset.whatsappPlacement || 'unknown');
    body.append('path', window.location.pathname + window.location.search);

    if (navigator.sendBeacon) {
        navigator.sendBeacon('/whatsapp-clicks', body);
        return;
    }

    fetch('/whatsapp-clicks', {
        method: 'POST',
        body,
        credentials: 'same-origin',
        keepalive: true,
        headers: token ? { 'X-CSRF-TOKEN': token } : {},
    }).catch(() => {});
});
