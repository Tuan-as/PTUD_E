<?php
include '../includes/db.php';

$id = $_GET['id'] ?? null;

$data = [
    'TenSanPham' => '',
    'MoTaNgan' => '',
    'MoTaDai' => '',
    'MaDanhMuc' => ''
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM SPU WHERE MaSPU=?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Lấy danh mục
$cats = $pdo->query("SELECT MaDanhMuc, TenDanhMuc FROM DanhMucSanPham ORDER BY TenDanhMuc")->fetchAll(PDO::FETCH_ASSOC);
?>

<form method="post" action="execute/Admin_SanPham_SPUSave.php">
    <input type="hidden" name="MaSPU" value="<?= $id ?>">

    <label class="form-label">Tên sản phẩm</label>
    <input name="TenSanPham" class="form-control mb-2" value="<?= htmlspecialchars($data['TenSanPham']) ?>">

    <label class="form-label">Danh mục</label>
    <select name="MaDanhMuc" class="form-select mb-2">
        <option value="">-- Chọn danh mục --</option>
        <?php foreach ($cats as $c): ?>
            <option value="<?= $c['MaDanhMuc'] ?>" <?= ($c['MaDanhMuc'] == $data['MaDanhMuc']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['TenDanhMuc']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label class="form-label">Mô tả ngắn</label>
    <textarea name="MoTaNgan" class="form-control mb-2"><?= htmlspecialchars($data['MoTaNgan']) ?></textarea>

    <label class="form-label">Mô tả dài</label>
    <textarea name="MoTaDai" class="form-control mb-3"><?= htmlspecialchars($data['MoTaDai']) ?></textarea>

    <button class="btn btn-dark">Lưu</button>
</form>
