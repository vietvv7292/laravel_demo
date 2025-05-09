import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    encrypted: true
});

console.log('Pusher Key:', import.meta.env.VITE_PUSHER_APP_KEY);
console.log('Pusher Cluster:', import.meta.env.VITE_PUSHER_APP_CLUSTER);
// Lắng nghe sự kiện 'MessageSent' trên kênh 'chat'
window.Echo.channel('chat')
    .listen('MessageSent', (e) => {
        console.log('Tin nhắn mới:', e.message);
        // Hiển thị tin nhắn trong giao diện
        const messageDiv = document.getElementById('messages');
        const messageElement = document.createElement('p');
        messageElement.textContent = e.message;
        messageDiv.appendChild(messageElement);
    });
