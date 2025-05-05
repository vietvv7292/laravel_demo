import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Gắn vào window (optional)
window.Pusher = Pusher;

// Khởi tạo Laravel Echo
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,   // key từ .env
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // cluster từ .env
    wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
