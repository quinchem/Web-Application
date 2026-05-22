<?php
// Routes/Router.php

class Router {
    private array $routes = [];

    // Đăng ký phương thức GET
    public function get(string $pageName, array $handler): void {
        $this->routes['GET'][$pageName] = $handler;
    }

    // Đăng ký phương thức POST
    public function post(string $pageName, array $handler): void {
        $this->routes['POST'][$pageName] = $handler;
    }

    // Hàm phân tích tham số ?page=... và kích hoạt chuẩn Controller
    public function resolve(string $requestMethod) {
        // Lấy tham số 'page' từ URL, nếu không truyền mặc định là 'homepage'
        $page = $_GET['page'] ?? 'homepage';
        
        // Tìm cấu hình tương ứng được đăng ký trong hệ thống
        $handler = $this->routes[$requestMethod][$page] ?? null;

        if (!$handler) {
            header("HTTP/1.0 404 Not Found");
            echo "<div style='text-align: center; padding: 50px; font-family: sans-serif;'>";
            echo "  <h1 style='color: #dc3545;'>404</h1>";
            echo "  <h3>Trang web không tồn tại hoặc khóa định tuyến '<strong>".htmlspecialchars($page)."</strong>' chưa được đăng ký!</h3>";
            echo "</div>";
            return;
        }

        [$controllerClass, $method] = $handler;

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $method)) {
                // Chạy hàm nghiệp vụ trong Controller và dừng luồng
                return $controller->$method();
            }
        }

        header("HTTP/1.0 500 Internal Server Error");
        echo "500 - Lỗi hệ thống: Không tìm thấy Hàm hoặc Lớp điều hướng tương ứng.";
    }
}