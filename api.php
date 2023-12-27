<?php

class ParkingApi
{
    private $conn;

    public function __construct()
    {
        $this->initDatabaseConnection();
    }

    private function initDatabaseConnection()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "db_parking";

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function handleRequest()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Max-Age: 3600");

        if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
            http_response_code(200);
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            if (isset($_GET['vtype'])) {
                $this->handleFilteredGetRequest();
            } elseif (isset($_GET['status'])) {
                $this->handleStatusFilteredGetRequest();
            }else {
                $this->handleGetRequest();
            }
        } elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            $this->handlePostRequest($data);
        } else {
            http_response_code(405);
            echo json_encode(["error" => "Method Not Allowed"]);
        }
    }

    private function handleGetRequest()
    {
        $data = $this->getParkingLogs();
        
        if (!empty($data)) {
            http_response_code(200);
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "No records found"]);
        }
    }

    private function handleFilteredGetRequest()
    {
        $filter = $_GET['vtype'] ?? null;
        $data = $this->getParkingLogs($filter);

        if (!empty($data)) {
            http_response_code(200);
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "No records found for the specified filter"]);
        }
    }

    private function handleStatusFilteredGetRequest()
    {
        $status = $_GET['status'] ?? null;
        $data = $this->getParkingLogsByStatus($status);

        if (!empty($data)) {
            http_response_code(200);
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "No records found for the specified status"]);
        }
    }


    private function getParkingLogs($filter = null)
    {
        $sql = "SELECT * FROM tbl_parklogs";

        if ($filter) {
            $sql .= " WHERE p_vtype = ? ORDER BY p_id DESC";
        }

        $stmt = $this->conn->prepare($sql);

        if ($filter) {
            $stmt->bind_param("s", $filter);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();

        return $data;
    }


    private function getParkingLogsByStatus($status)
    {
        $sql = "SELECT * FROM tbl_parklogs WHERE status = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();

        return $data;
    }

    private function handlePostRequest($data)
    {
        if (!isset($data["action"])) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid action"]);
            return;
        }

        $action = $data["action"];
        switch ($action) {
            case 'register':
                $this->handleRegistration($data);
                break;
            case 'login':
                $this->handleLogin($data);
                break;
            case 'submitForm':
                $this->handleSubmitForm($data);
                break;
            case 'updateStatus':
                $this->updateStatus($data);
                break;
            default:
                http_response_code(400);
                echo json_encode(["error" => "Invalid action"]);
                break;
        }
    }

    private function handleRegistration($data)
    {
        $username = $data["username"];
        $password = password_hash($data["password"], PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO tbl_users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["message" => "User registered successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to register user"]);
        }

        $stmt->close();
    }

    private function handleLogin($data)
    {
        $username = $data["username"];
        $password = $data["password"];
        $user_id = "";
        $hashed_password = "";

        $stmt = $this->conn->prepare("SELECT user_id, password FROM tbl_users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($user_id, $hashed_password);

        if ($stmt->fetch() && password_verify($password, $hashed_password)) {
            http_response_code(200);
            echo json_encode(["message" => "Login successful", "user_id" => $user_id]);
        } else {
            http_response_code(401);
            echo json_encode(["error" => "Invalid username or password"]);
        }

        $stmt->close();
    }

    private function handleSubmitForm($data)
    {
        $name = $data["name"];
        $phone = $data["phone"];
        $vbrand = $data["vbrand"];
        $vmodel = $data["vmodel"];
        $plateNo = $data["plateNo"];
        $spot = $data["spot"];
        $vtype = $data["vtype"];

        $stmt = $this->conn->prepare("INSERT INTO tbl_parklogs (p_name, p_phone, p_vbrand, p_vmodel, p_plateNo, p_time, p_date, p_spot, p_vtype) VALUES (?, ?, ?, ?, ?, CURRENT_TIME, CURRENT_DATE, ?, ?)");
        $stmt->bind_param("sisssss", $name, $phone, $vbrand, $vmodel, $plateNo, $spot, $vtype);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["message" => "Form submitted successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to submit form"]);
        }

        $stmt->close();
    }

    private function updateStatus($data)
    {
        if (!isset($data["p_id"]) || !isset($data["status"])) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid data"]);
            return;
        }

        $logId = $data["p_id"];
        $status = $data["status"];
        // $out = $data["out"];

        $stmt = $this->conn->prepare("UPDATE tbl_parklogs SET status = ?, p_out = CURRENT_TIMESTAMP WHERE p_id = ?");
        $stmt->bind_param("ii", $status, $logId);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["message" => "Status updated successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to update status"]);
        }

        $stmt->close();
    }
}

$api = new ParkingApi();
$api->handleRequest();
?>
