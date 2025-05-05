<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Message</title>
</head>
<body>
    <h1>Gửi Tin Nhắn</h1>
    <form id="messageForm">
        <input type="text" id="messageInput" placeholder="Nhập tin nhắn" required>
        <button type="submit">Gửi</button>
    </form>

    <script type="module">
        import axios from 'https://cdn.skypack.dev/axios';

        const form = document.getElementById('messageForm');
        const input = document.getElementById('messageInput');

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            axios.post('/demo/message', { message })
                .then(() => input.value = '')
                .catch(console.error);
        });
    </script>
</body>
</html>
