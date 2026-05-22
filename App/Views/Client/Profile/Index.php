<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài đặt tài khoản - Client</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Tùy chỉnh trạng thái đang chọn (Active) của menu bên trái */
        #account-menu .list-group-item-action.active {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: #ffffff !important;
            font-weight: 600;
        }
        /* Hiệu ứng hover mượt mà cho các mục chưa active */
        #account-menu .list-group-item-action:hover:not(.active) {
            background-color: #f8f9fa;
            color: #0d6efd;
        }
        /* Cố định kích thước icon để thanh menu thẳng hàng tăm tắp */
        #account-menu .list-group-item-action i {
            width: 24px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-light">

    <?php include __DIR__ . '/../../Partials/Client/Header.php'; ?>

    <div class="container my-5">
        <div class="row g-4">
            
            <div class="col-md-3">
                <?php include __DIR__ . '/../Partials/Menu.php'; ?>
            </div>

            <div class="col-md-9">
                <div class="card shadow-sm border-0 p-4" style="min-height: 480px;">
                    <div id="dynamic-content">
                        <?php include __DIR__ . '/edit.php'; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include __DIR__ . '/../../Partials/Client/Footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="/Public/Client/Js/Profile_tab.js"></script>
</body>
</html>