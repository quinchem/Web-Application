<?php

// ✅ ĐÚNG — lên 1 cấp là tới Web-Application/, có Configs/ ở đó
require_once __DIR__ . '/../Configs/Database.php';

// ✅ ĐÚNG — lên 1 cấp rồi vào App/Models/
require_once __DIR__ . '/../App/Models/Client.php';
class ClientRepository
{
    private $conn;

    public function __construct()
    {
        $database = new Database();

        $this->conn = $database->connect();
    }


    // =========================
    // LOGIN
    // =========================

    public function getUserByUsername($username)
    {
        try {
            // Chú ý: Thay 'users' bằng tên bảng thực tế của bạn trong CSDL nếu khác
            $sql = "SELECT * FROM user WHERE user_name = :username LIMIT 1";

            // Nếu bạn dùng PDO:
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            // Trả về một mảng chứa thông tin user, hoặc false nếu không tìm thấy
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Xử lý lỗi nếu có
            echo "Lỗi truy vấn: " . $e->getMessage();
            return false;
        }
    }
    // =========================
    // QUẢN LÝ TÀI KHOẢN
    // =========================

    public function changeUserPassword($userId, $currentPassword, $newPassword)
    {
        try {
            // 1. Lấy mật khẩu hiện tại từ DB để kiểm tra
            $sql = "SELECT password FROM user WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT); // Hoặc PARAM_STR tùy kiểu dữ liệu ID của bạn
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return "Không tìm thấy tài khoản!";
            }

            // 2. Kiểm tra mật khẩu cũ (Dùng password_verify nếu lúc đăng ký bạn băm mật khẩu bằng password_hash)
            // Lưu ý: Nếu data cũ của bạn đang lưu password dạng chữ thường (không mã hóa), 
            // thì đổi dòng if này thành: if ($currentPassword !== $user['password'])
            if (!password_verify($currentPassword, $user['password'])) {
                return "Mật khẩu hiện tại không chính xác!";
            }

            // 3. Mã hóa mật khẩu mới và Cập nhật
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $updateSql = "UPDATE user SET password = :new_password WHERE user_id = :user_id";
            $updateStmt = $this->conn->prepare($updateSql);
            $updateStmt->bindParam(':new_password', $hashedNewPassword, PDO::PARAM_STR);
            $updateStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

            $success = $updateStmt->execute();

            return $success ? true : "Đã xảy ra lỗi khi cập nhật mật khẩu!";

        } catch (PDOException $e) {
            return "Lỗi CSDL: " . $e->getMessage();
        }
    }

    public function checkLogin($email, $password)
    {
        try {

            $sql = "

            SELECT *

            FROM User

            WHERE email = :email

            LIMIT 1
        ";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([

                'email' => $email
            ]);

            $user = $stmt->fetch(PDO::FETCH_OBJ);


            // KHÔNG TÌM THẤY EMAIL

            if (!$user) {

                return false;
            }


            // KIỂM TRA PASSWORD HASH

            if (password_verify($password, $user->password)) {

                return $user;
            }

            return false;

        } catch (PDOException $e) {

            echo "Lỗi đăng nhập: " .
                $e->getMessage();

            return false;
        }

        // Bổ sung vào bên trong Class ClientRepository (file Repositories/ClientRepository.php)


    }
    public function updateProfile($userId, $username, $fullName, $gender) 
    {
        try {
            // Đã sửa đổi tên bảng từ 'users' thành 'user' cho khớp với các hàm trên
            $sql = "UPDATE user SET user_name = ?, full_name = ?, gender = ? WHERE user_id = ?";
            
            // ĐÃ SỬA LỖI: Sử dụng $this->conn thay vì $this->db bị trống (null)
            $stmt = $this->conn->prepare($sql);
            
            // Thực thi mảng tham số truyền theo đúng thứ tự các dấu hỏi chấm (?)
            return $stmt->execute([$username, $fullName, $gender, $userId]);
            
        } catch (PDOException $e) {
            error_log("Lỗi Update Profile: " . $e->getMessage());
            return false;
        }
    }

    /**
 * Cập nhật mật khẩu mới đã băm vào Cơ sở dữ liệu
 * @param string|int $userId Định danh người dùng (user_id)
 * @param string $hashedPassword Mật khẩu mới đã được băm qua password_hash
 * @return bool Trả về true nếu thành công, false nếu thất bại
 */
public function updatePassword($userId, $hashedPassword) 
{
    try {
        // Tên bảng: user | Thuộc tính: password, user_id
        $sql = "UPDATE user SET password = ? WHERE user_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        
        // Thực thi truyền mảng tham số theo đúng thứ tự dấu hỏi chấm (?)
        return $stmt->execute([$hashedPassword, $userId]);
        
    } catch (PDOException $e) {
        error_log("Lỗi tại Update Password: " . $e->getMessage());
        return false;
    }
}

/**
     * Cập nhật URL ảnh đại diện vào cột thumbnail_url trong CSDL
     * @param int|string $userId Định danh người dùng
     * @param string $thumbnailUrl Link URL tải từ Cloudinary
     * @return bool
     */
    public function updateAvatarUrl($userId, $avatarUrl) 
    {
        try {
            // Sửa tên cột thành `avatar`
            $sql = "UPDATE user SET avatar = ? WHERE user_id = ?";
            
            $stmt = $this->conn->prepare($sql);
            
            return $stmt->execute([$avatarUrl, $userId]);
            
        } catch (PDOException $e) {
            error_log("Lỗi hệ thống khi cập nhật cột ảnh đại diện: " . $e->getMessage());
            return false;
        }
    }
    // =========================
    // ADMIN PROFILE
    // =========================

    public function updateAdminProfile($userId, $fullname, $username, $email, $bio, $avatarPath = null)
    {
        try {
            if ($avatarPath) {
                $sql = "UPDATE user SET full_name = ?, user_name = ?, email = ?, bio = ?, avatar = ? WHERE user_id = ?";
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([$fullname, $username, $email, $bio, $avatarPath, $userId]);
            } else {
                $sql = "UPDATE user SET full_name = ?, user_name = ?, email = ?, bio = ? WHERE user_id = ?";
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([$fullname, $username, $email, $bio, $userId]);
            }
        } catch (PDOException $e) {
            error_log("Lỗi updateAdminProfile: " . $e->getMessage());
            return "Lỗi CSDL: " . $e->getMessage();
        }
    }
}

