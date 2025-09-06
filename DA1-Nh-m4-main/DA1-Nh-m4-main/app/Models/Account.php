<?php
class Account
{
    public $pdo; // Biến lưu đối tượng PDO để làm việc với cơ sở dữ liệu

    // Hàm khởi tạo: Kết nối cơ sở dữ liệu thông qua lớp Database
    public function __construct()
    {
        $db = new Database(); // Tạo một đối tượng Database
        $this->pdo = $db->getConnection(); // Lấy kết nối PDO từ lớp Database
    }

    // Lấy tất cả bản ghi từ bảng users
    public function all()
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kiểm tra email đã tồn tại trong cơ sở dữ liệu chưa
    public function checkEmailExist($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm một bản ghi mới vào bảng users
    public function insert($data)
    {
        $sql = "INSERT INTO users (fullname, email, password, phone, role, address, status, created_at, updated_at) 
                VALUES (:fullname, :email, :password, :phone, :role, :address, :status, :created_at, :updated_at)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'phone' => $data['phone'],
            'role' => $data['role'],
            'address' => $data['address'],
            'status' => $data['status'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at']
        ]);
    }

    // Tìm một bản ghi theo ID
    public function find($id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Xóa một bản ghi theo ID
    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    // Kiểm tra thông tin đăng nhập của người dùng
    public function checkUser($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Cập nhật một bản ghi
    public function update($id, $data)
    {
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (!isset($data['email'], $data['fullname'], $data['phone'], $data['role'], $data['status'])) {
            throw new Exception("Thiếu thông tin bắt buộc.");
        }

        // Gán giá trị mặc định nếu thiếu để tránh lỗi undefined key
        $data['address'] = $data['address'] ?? '';

        $sql = "UPDATE users SET 
                    email = :email, 
                    fullname = :fullname, 
                    phone = :phone, 
                    role = :role,
                    address = :address, 
                    status = :status"
            . (isset($data['password']) ? ", password = :password" : "") .
            " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':fullname', $data['fullname']);
        $stmt->bindValue(':phone', $data['phone']);
        $stmt->bindValue(':address', $data['address']);
        $stmt->bindValue(':role', $data['role']);
        $stmt->bindValue(':status', $data['status']);

        if (isset($data['password'])) {
            $stmt->bindValue(':password', $data['password']);
        }

        $stmt->bindValue(':id', $id);

        if ($stmt->execute()) {
            return true;
        } else {
            var_dump($stmt->errorInfo());
            return false;
        }
    }

    public function getUserByEmail($email)
    {
        try {
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ? $user : null;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }

    public function login($email, $password)
    {
        $user = $this->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    public function getUserByName($name)
    {
        $sql = "SELECT * FROM users WHERE fullname = :name LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
