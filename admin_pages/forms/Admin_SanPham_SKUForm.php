<?php
include '../includes/db.php';

$spuId = intval($_GET['spu'] ?? 0);
$skuId = intval($_GET['sku'] ?? 0);
$sku = [];
$selectedGiaTri = 0;
$selectedThuocTinh = 0;

if ($skuId) {
    $stmt = $pdo->prepare("
        SELECT SKU.*, TTS.MaGiaTri, TT.MaThuocTinh
        FROM SKU
        LEFT JOIN ThuocTinhSanPham TTS ON SKU.MaSKU = TTS.MaSKU
        LEFT JOIN GiaTriThuocTinh GTT ON TTS.MaGiaTri = GTT.MaGiaTri
        LEFT JOIN ThuocTinh TT ON GTT.MaThuocTinh = TT.MaThuocTinh
        WHERE SKU.MaSKU = ?
    ");
    $stmt->execute([$skuId]);
    $sku = $stmt->fetch();
    if($sku){
        $selectedGiaTri = $sku['MaGiaTri'] ?? 0;
        $selectedThuocTinh = $sku['MaThuocTinh'] ?? 0;
    }
}

$thuocTinhList = $pdo->query("SELECT DISTINCT MaThuocTinh, TenThuocTinh FROM ThuocTinh ORDER BY TenThuocTinh ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<form id="skuForm" onsubmit="submitSkuForm(event,this)">
  <input type="hidden" name="MaSPU" value="<?= $spuId ?>">
  <input type="hidden" name="MaSKU" value="<?= $sku['MaSKU'] ?? '' ?>">

  <div class="mb-3">
    <label>Mã SKU</label>
    <input type="text" name="SKUCode" class="form-control" required value="<?= $sku['SKUCode'] ?? '' ?>">
  </div>

  <div class="mb-3">
    <label>Tên biến thể</label>
    <input type="text" name="Name" class="form-control" required value="<?= $sku['Name'] ?? '' ?>">
  </div>

  <div class="mb-3 row">
    <div class="col">
      <label>Giá gốc</label>
      <input type="number" name="GiaGoc" class="form-control" required value="<?= $sku['GiaGoc'] ?? '' ?>">
    </div>
    <div class="col">
      <label>Giá giảm</label>
      <input type="number" name="GiaGiam" class="form-control" value="<?= $sku['GiaGiam'] ?? '' ?>">
    </div>
  </div>

  <div class="mb-3 row">
    <div class="col">
      <label>Thuộc tính</label>
      <select class="form-select" id="thuocTinhSelect" required>
        <option value="">Chọn thuộc tính</option>
        <?php foreach($thuocTinhList as $tt): ?>
          <option value="<?= $tt['MaThuocTinh'] ?>" <?= $tt['MaThuocTinh']==$selectedThuocTinh?'selected':'' ?>>
            <?= htmlspecialchars($tt['TenThuocTinh']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col">
      <label>Giá trị thuộc tính</label>
      <select class="form-select" id="giaTriSelect" name="GiaTri" required>
        <!-- Option sẽ load bằng JS -->
      </select>
    </div>
  </div>

  <div class="mb-3 row">
    <div class="col">
      <label>Tồn kho</label>
      <input type="number" name="TonKho" class="form-control" required value="<?= $sku['TonKho'] ?? 0 ?>">
    </div>
    <div class="col">
      <label>Trạng thái</label>
      <select name="TrangThai" class="form-select">
        <option value="ACTIVE" <?= ($sku['TrangThai']??'')=='ACTIVE'?'selected':'' ?>>ACTIVE</option>
        <option value="INACTIVE" <?= ($sku['TrangThai']??'')=='INACTIVE'?'selected':'' ?>>INACTIVE</option>
      </select>
    </div>
  </div>

  <div class="text-end">
    <button type="submit" class="btn btn-dark btn-sm">Lưu biến thể</button>
  </div>

  <!-- Hidden để JS lấy giá trị khi edit -->
  <input type="hidden" id="selectedThuocTinh" value="<?= $selectedThuocTinh ?>">
  <input type="hidden" id="selectedGiaTri" value="<?= $selectedGiaTri ?>">
</form>
