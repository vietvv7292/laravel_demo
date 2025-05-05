<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Broadcasting Chat Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: #f5f7fa;
            font-family: Arial, sans-serif;
            padding: 0;
            margin: 0;
        }
        .chat-container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 80vh;
        }
        .chat-header {
            padding: 15px;
            background: #007bff;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }
        .chat-messages {
            padding: 15px;
            flex: 1;
            overflow-y: auto;
            background: #f1f1f1;
        }
        .message {
            background: #ffffff;
            margin-bottom: 10px;
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            max-width: 80%;
        }
        .timestamp {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
        }
        .chat-footer {
            padding: 15px;
            border-top: 1px solid #ddd;
            background: #fafafa;
        }
    </style>
</head>
<body>

<div class="chat-container">
    <div class="chat-header">Real-Time Chat</div>
    <div id="messages" class="chat-messages">
        <!-- Tin nhắn sẽ xuất hiện ở đây -->
    </div>
    <div class="chat-footer text-center">
        <em>TIN NHẮN</em>
    </div>
</div>

@vite('resources/js/app.ts')

</body>
</html>
