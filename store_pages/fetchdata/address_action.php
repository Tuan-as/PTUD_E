<?php
// FILE: store_pages/fetchdata/address_action.php
session_start();
require_once '../../db.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

// 1. LẤY DANH SÁCH
if ($action === 'get_list') {
    $sql = "SELECT * FROM DiaChiNhanHang WHERE MaNguoiDung = $user_id ORDER BY LaDiaChiMacDinh DESC, MaDiaChiNhanHang DESC";
    $res = $conn->query($sql);
    $data = [];
    while ($row = $res->fetch_assoc()) $data[] = $row;
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}

// 2. LẤY CHI TIẾT 1 ĐỊA CHỈ (Để sửa)
if ($action === 'get_detail') {
    $id = intval($_POST['id']);
    $res = $conn->query("SELECT * FROM DiaChiNhanHang WHERE MaDiaChiNhanHang=$id AND MaNguoiDung=$user_id");
    if($res->num_rows > 0) {
        echo json_encode(['success' => true, 'data' => $res->fetch_assoc()]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy']);
    }
    exit;
}

// 3. THÊM MỚI (ADD)
if ($action === 'add') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $addr = trim($_POST['addr']); 
    $is_default = (isset($_POST['is_default']) && $_POST['is_default'] == 'true') ? 'Y' : 'N';

    $check = $conn->query("SELECT COUNT(*) as cnt FROM DiaChiNhanHang WHERE MaNguoiDung=$user_id")->fetch_assoc();
    if ($check['cnt'] == 0) $is_default = 'Y';

    if ($is_default == 'Y') {
        $conn->query("UPDATE DiaChiNhanHang SET LaDiaChiMacDinh='N' WHERE MaNguoiDung=$user_id");
    }

    $stmt = $conn->prepare("INSERT INTO DiaChiNhanHang (MaNguoiDung, TenNhanHang, SDTNhanHang, DiaChiNhanHang, LaDiaChiMacDinh) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $name, $phone, $addr, $is_default);
    
    if ($stmt->execute()) echo json_encode(['success' => true]);
    else echo json_encode(['success' => false, 'message' => $conn->error]);
    exit;
}

// 4. CẬP NHẬT (UPDATE - Mới thêm)
if ($action === 'update') {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $addr = trim($_POST['addr']); 
    $is_default = (isset($_POST['is_default']) && $_POST['is_default'] == 'true') ? 'Y' : 'N';

    if ($is_default == 'Y') {
        $conn->query("UPDATE DiaChiNhanHang SET LaDiaChiMacDinh='N' WHERE MaNguoiDung=$user_id");
    }

    // Nếu không tick mặc định, nhưng nó đang là mặc định thì phải giữ nguyên là Y (trừ khi có cái khác được set Y)
    // Logic đơn giản: Chỉ update thông tin
    $sql = "UPDATE DiaChiNhanHang SET TenNhanHang=?, SDTNhanHang=?, DiaChiNhanHang=?, LaDiaChiMacDinh=? WHERE MaDiaChiNhanHang=? AND MaNguoiDung=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $name, $phone, $addr, $is_default, $id, $user_id);

    if ($stmt->execute()) echo json_encode(['success' => true]);
    else echo json_encode(['success' => false, 'message' => $conn->error]);
    exit;
}

// 5. XÓA
if ($action === 'delete') {
    $id = intval($_POST['id']);
    $chk = $conn->query("SELECT LaDiaChiMacDinh FROM DiaChiNhanHang WHERE MaDiaChiNhanHang=$id AND MaNguoiDung=$user_id")->fetch_assoc();
    
    if(!$chk) {
        echo json_encode(['success'=>false, 'message'=>'Không tìm thấy địa chỉ']);
        exit;
    }

    if($chk['LaDiaChiMacDinh']=='Y') {
        // Kiểm tra xem còn địa chỉ nào khác không
        $count = $conn->query("SELECT COUNT(*) as cnt FROM DiaChiNhanHang WHERE MaNguoiDung=$user_id")->fetch_assoc()['cnt'];
        if($count > 1) {
             echo json_encode(['success'=>false, 'message'=>'Vui lòng đặt địa chỉ khác làm mặc định trước khi xóa địa chỉ này.']);
             exit;
        }
    }
    
    $conn->query("DELETE FROM DiaChiNhanHang WHERE MaDiaChiNhanHang=$id AND MaNguoiDung=$user_id");
    echo json_encode(['success' => true]);
    exit;
}

// 6. SET DEFAULT
if ($action === 'set_default') {
    $id = intval($_POST['id']);
    $conn->query("UPDATE DiaChiNhanHang SET LaDiaChiMacDinh='N' WHERE MaNguoiDung=$user_id");
    $conn->query("UPDATE DiaChiNhanHang SET LaDiaChiMacDinh='Y' WHERE MaDiaChiNhanHang=$id AND MaNguoiDung=$user_id");
    echo json_encode(['success' => true]);
    exit;
}
?>