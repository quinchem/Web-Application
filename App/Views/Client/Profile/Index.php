<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <?php include __DIR__ . '/../../Partials/Client/Header.php'; ?>

    <div class="container my-5">
        <div class="row g-4">
            
            <div class="col-md-3">
                <?php include __DIR__ . '/../../Partials/Client/Client_menu.php'; ?>
            </div>

            <div class="col-md-9">
                <div class="card shadow-sm border-0 p-4" style="min-height: 480px;">
                    <div id="dynamic-content">
                        </div>
                </div>
            </div>

        </div>
    </div>

    <?php include __DIR__ . '/../../Partials/Client/Footer.php'; ?>


</html>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="Public/Client/Js/ProfileTab.js"></script>
    <script src="Public/Client/Js/ProfileEdit.js"></script>
</body>
</html>