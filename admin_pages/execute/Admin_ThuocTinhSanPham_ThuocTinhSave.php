<?php
include '../fetch/Admin_GetData.php';

try {
  $pdo->beginTransaction();

  $id   = $_POST['MaThuocTinh'] ?? null;
  $ten  = trim($_POST['TenThuocTinh']);
  $loai = $_POST['LoaiDuLieu'];
  $giaTri = trim($_POST['GiaTri']);

  if ($id) {
    $stmt = $pdo->prepare("
      UPDATE ThuocTinh
      SET TenThuocTinh=?, LoaiDuLieu=?
      WHERE MaThuocTinh=?
    ");
    $stmt->execute([$ten, $loai, $id]);
    $maThuocTinh = $id;
  } else {
    $stmt = $pdo->prepare("
      INSERT INTO ThuocTinh (TenThuocTinh, LoaiDuLieu, IsVariant, SortOrder)
      VALUES (?, ?, 1, 0)
    ");
    $stmt->execute([$ten, $loai]);
    $maThuocTinh = $pdo->lastInsertId();
  }

  if ($giaTri !== '') {
    $pdo->prepare("DELETE FROM GiaTriThuocTinh WHERE MaThuocTinh=?")
        ->execute([$maThuocTinh]);

    $arr = array_filter(array_map('trim', explode(',', $giaTri)));
    $stmt = $pdo->prepare("
      INSERT INTO GiaTriThuocTinh (MaThuocTinh, GiaTri, SortOrder)
      VALUES (?, ?, 0)
    ");
    foreach ($arr as $v) {
      $stmt->execute([$maThuocTinh, $v]);
    }
  }

  $pdo->commit();
  echo json_encode(['success' => true]);

} catch (Exception $e) {
  $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
