<?php
include '../fetch/Admin_GetData.php';
// Lấy danh sách SKU đang kinh doanh
$skus = $pdo->query("SELECT MaSKU, Name, SKUCode, TonKho FROM SKU ORDER BY Name ASC")->fetchAll();
?>

<form id="formCreateInventory">
    <div class="mb-3">
        <label class="form-label small fw-bold">CHỌN SẢN PHẨM / BIẾN THỂ</label>
        <select name="MaSKU" id="selectSKU" class="form-select rounded-0" required>
            <option value="">-- Chọn sản phẩm --</option>
            <?php foreach($skus as $s): ?>
                <option value="<?= $s['MaSKU'] ?>" data-stock="<?= $s['TonKho'] ?>">
                    <?= htmlspecialchars($s['Name']) ?> (Mã: <?= $s['SKUCode'] ?> - Tồn: <?= $s['TonKho'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label small fw-bold">LOẠI THAY ĐỔI</label>
            <select name="LoaiThayDoi" id="selectType" class="form-select rounded-0">
                <option value="Nhập kho">Nhập kho</option>
                <option value="Xuất kho">Xuất kho</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label small fw-bold">SỐ LƯỢNG</label>
            <input type="number" name="SoLuong" class="form-control rounded-0" min="1" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label small fw-bold">GHI CHÚ</label>
        <textarea name="GhiChu" class="form-control rounded-0" rows="3" placeholder="Lý do nhập/xuất..."></textarea>
    </div>

    <div id="stockWarning" class="alert alert-warning d-none small"></div>

    <button type="submit" id="btnSubmitInventory" class="btn btn-dark w-100 rounded-0 fw-bold">
        XÁC NHẬN LƯU PHIẾU
    </button>
</form>
<script>
document.getElementById('formCreateInventory').onsubmit = function(e) {
    submitInventoryForm(e);
};
</script>