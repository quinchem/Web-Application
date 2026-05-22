<?php
// Routes/AdminRouter.php

class AdminRouter {
    private array $routes = [];

    // Đăng ký phương thức GET
    public function get(string $pageName, array $handler): void {
        $this->routes['GET'][$pageName] = $handler;
    }

    // Đăng ký phương thức POST
    public function post(string $pageName, array $handler): void {
        $this->routes['POST'][$pageName] = $handler;
    }

    // Hàm điều hướng phân tích tham số ?page=...
    public function resolve(string $requestMethod) {
        // Mặc định đối với Admin là trang quản lý bài viết admin_user_posts
        $page = $_GET['page'] ?? 'admin_user_posts';
        
        $handler = $this->routes[$requestMethod][$page] ?? null;

        if (!$handler) {
            header("HTTP/1.0 404 Not Found");
            echo "<div style='text-align: center; padding: 50px; font-family: sans-serif;'>";
            echo "  <h1 style='color: #dc3545;'>404 - Admin</h1>";
            echo "  <h3>Trang quản trị không tồn tại hoặc khóa '<strong>".htmlspecialchars($page)."</strong>' chưa được đăng ký!</h3>";
            echo "</div>";
            return;
        }

        [$controllerClass, $method] = $handler;

        // Tự động bổ sung Namespace nếu chưa có
        if (!class_exists($controllerClass) && class_exists("App\\Controllers\\" . $controllerClass)) {
            $controllerClass = "App\\Controllers\\" . $controllerClass;
        }

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $method)) {
                return $controller->$method();
            }
        }

        header("HTTP/1.0 500 Internal Server Error");
        echo "500 - Không tìm thấy Hàm hoặc Lớp điều hướng tương ứng cho Admin.";
    }
}