<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Laravel</title>
    <!-- Thêm Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h1>Danh Sách Demo Laravel</h1>
            </div>
            <div class="card-body">
                <p class="text-muted text-center">Chọn một tính năng để thử nghiệm</p>
                <ul class="list-group">
                    <li class="list-group-item">
                        <h5 class="text-success">Demo Queue</h5>
                        <p class="text-muted">Xử lý công việc nền như gửi email, resize ảnh mà không làm chậm ứng dụng.</p>
                        <div class="d-flex">
                            <a href="{{ url('/docs/queue') }}" class="btn btn-outline-secondary flex-fill me-2">Tài liệu</a>
                            {{-- <a href="{{ url('/docs/queue/guide') }}" class="btn btn-outline-secondary flex-fill me-2">Tạo Demo</a> --}}
                            <a href="{{ url('/demo/queue') }}" class="btn btn-success flex-fill">Demo</a>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <h5 class="text-info">Demo Event</h5>
                        <p class="text-muted">Kích hoạt sự kiện và xử lý tự động, ví dụ: gửi email khi đơn hàng được giao.</p>
                        <div class="d-flex">
                            <a href="{{ url('/docs/event') }}" class="btn btn-outline-secondary flex-fill me-2">Tài liệu</a>
                            {{-- <a href="{{ url('/docs/event/guide') }}" class="btn btn-outline-secondary flex-fill me-2">Tạo Demo</a> --}}
                            <a href="{{ url('/demo/event') }}" class="btn btn-info flex-fill">Demo</a>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <h5 class="text-warning">Demo Authenticate</h5>
                        <p class="text-muted">Xác thực người dùng, đăng nhập, đăng ký và bảo vệ trang.</p>
                        <div class="d-flex">
                            <a href="{{ url('/docs/authenticate') }}" class="btn btn-outline-secondary flex-fill me-2">Tài liệu</a>
                            {{-- <a href="{{ url('/docs/authenticate/guide') }}" class="btn btn-outline-secondary flex-fill me-2">Tạo Demo</a> --}}
                            <a href="{{ url('/demo/auth') }}" class="btn btn-warning flex-fill">Demo</a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <small class="text-muted">© 2025 Laravel Demo</small>
            </div>
        </div>
    </div>

    <!-- Thêm Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
