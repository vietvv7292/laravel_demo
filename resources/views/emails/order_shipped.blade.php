<!DOCTYPE html>
<html>
<head>
    <title>Đơn hàng đã được gửi</title>
</head>
<body>
    <p>Xin chào {{ $data['name'] }},</p>
    <p>Đơn hàng của bạn đã được gửi đi.</p>
    <p>Mã đơn hàng: {{ $data['order_id'] }}</p>
</body>
</html>
