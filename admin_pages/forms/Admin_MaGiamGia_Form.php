<?php
include '../fetch/Admin_GetData.php';
$id = $_GET['id'] ?? '';
$data = [
    'CodeGiamGia' => '', 'LoaiGiamGia' => 'PERCENT', 'MucGiamGia' => 0,
    'GiaTriDonHangToiThieu' => 0, 'SoLanSuDungToiDa' => 100,
    'NgayBatDau' => date('Y-m-d\TH:i'), 'NgayHetHan' => '', 'TrangThai' => 'ACTIVE'
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM MaGiamGia WHERE MaGiamGia = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();
}
?>
<form onsubmit="submitPromoForm(event, this)">
    <input type="hidden" name="MaGiamGia" value="<?= $id ?>">
    
    <div class="mb-3">
        <label class="form-label">Mã code</label>
        <input type="text" name="CodeGiamGia" class="form-control" value="<?= $data['CodeGiamGia'] ?>" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Loại</label>
            <select name="LoaiGiamGia" class="form-select">
                <option value="PERCENT" <?= $data['LoaiGiamGia'] == 'PERCENT' ? 'selected' : '' ?>>Phần trăm (%)</option>
                <option value="FIXED" <?= $data['LoaiGiamGia'] == 'FIXED' ? 'selected' : '' ?>>Số tiền (đ)</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Mức giảm</label>
            <input type="number" name="MucGiamGia" class="form-control" value="<?= $data['MucGiamGia'] ?>" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Đơn tối thiểu</label>
            <input type="number" name="GiaTriDonHangToiThieu" class="form-control" value="<?= $data['GiaTriDonHangToiThieu'] ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Số lần sử dụng tối đa</label>
            <input type="number" name="SoLanSuDungToiDa" class="form-control" value="<?= $data['SoLanSuDungToiDa'] ?>">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Ngày bắt đầu</label>
            <input type="datetime-local" name="NgayBatDau" class="form-control" value="<?= $data['NgayBatDau'] ? date('Y-m-d\TH:i', strtotime($data['NgayBatDau'])) : '' ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Ngày hết hạn</label>
            <input type="datetime-local" name="NgayHetHan" class="form-control" value="<?= $data['NgayHetHan'] ? date('Y-m-d\TH:i', strtotime($data['NgayHetHan'])) : '' ?>">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="TrangThai" class="form-select">
            <option value="ACTIVE" <?= $data['TrangThai'] == 'ACTIVE' ? 'selected' : '' ?>>ACTIVE</option>
            <option value="INACTIVE" <?= $data['TrangThai'] == 'INACTIVE' ? 'selected' : '' ?>>INACTIVE</option>
        </select>
    </div>

    <button type="submit" class="btn btn-dark w-100">Lưu thông tin</button>
</form>