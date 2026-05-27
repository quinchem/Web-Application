<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@600;700;800&family=Montserrat:wght@400;500;600;700;800&family=Newsreader:opsz,wght@6..72,500;6..72,700;6..72,800&display=swap" rel="stylesheet">
   
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
                <div id="dynamic-content" style="min-height: 480px;">
                    </div>
            </div>

        </div>
    </div>

    <?php include __DIR__ . '/../../Partials/Client/Footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="Public/Client/Js/ClientProfileTab.js"></script>
    <script src="Public/Client/Js/ClientProfile_Edit.js"></script>
    <script src="Public/Client/Js/ClientProfile_SavedPost.js"></script>
    <script src="Public/Client/Js/ClientProfile_MyPost.js"></script>
    
</body>
</html>