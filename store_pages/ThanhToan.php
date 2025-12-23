<?php
$page_title = "Thanh Toán | LocknLock";
$page_css = "../css/ThanhToan.css";
include 'includes/header.php';
require_once '../db.php';

if (!isset($_SESSION['user_id'])) { echo "<script>window.location='DangNhap.php';</script>"; exit; }
$user_id = $_SESSION['user_id'];

// Check Giỏ hàng
if (!isset($_POST['selected_skus'])) { echo "<script>window.location='GioHang.php';</script>"; exit; }
$selected_skus = json_decode($_POST['selected_skus']);
if(empty($selected_skus)) { echo "<script>window.location='GioHang.php';</script>"; exit; }

$ids = implode(',', array_map('intval', $selected_skus));

// 1. Load Sản phẩm
$sql = "SELECT g.*, s.Name, s.GiaGoc, s.GiaGiam, spu.TenSanPham,
        (SELECT File FROM Media m JOIN MediaSanPham ms ON m.MaMedia=ms.MaMedia WHERE ms.MaSKU=g.MaSKU LIMIT 1) as HinhAnh
        FROM GioHang g JOIN SKU s ON g.MaSKU=s.MaSKU JOIN SPU spu ON s.MaSPU=spu.MaSPU
        WHERE g.MaNguoiDung=$user_id AND g.MaSKU IN ($ids)";
$cart = $conn->query($sql);
$subtotal = 0;
$items_data = [];

// 2. Load Địa chỉ Mặc định
$addr = $conn->query("SELECT * FROM DiaChiNhanHang WHERE MaNguoiDung=$user_id ORDER BY LaDiaChiMacDinh DESC LIMIT 1")->fetch_assoc();

// 3. Load Voucher Active
$vouchers = $conn->query("SELECT * FROM MaGiamGia WHERE TrangThai = 'ACTIVE' AND NgayHetHan >= NOW()");
?>

<div class="checkout-container">
    <div class="address-border-top"></div>
    
    <div class="section-box">
        <div class="addr-header"><i class="fas fa-map-marker-alt"></i> Địa Chỉ Nhận Hàng</div>
        <div class="addr-content" id="addr-display">
            <?php if($addr): ?>
                <div>
                    <span class="addr-info-bold"><?php echo $addr['TenNhanHang']; ?> (+84) <?php echo $addr['SDTNhanHang']; ?></span>
                    <span class="text-secondary ms-2"><?php echo $addr['DiaChiNhanHang']; ?></span>
                    <?php if($addr['LaDiaChiMacDinh']=='Y') echo '<span class="tag-default" style="border:1px solid #d0021b; color:#d0021b; font-size:0.8em; padding:1px 5px; margin-left:10px;">Mặc định</span>'; ?>
                </div>
                <button class="btn-change-addr" onclick="openAddrModal()">Thay Đổi</button>
                <input type="hidden" id="addr-id" value="<?php echo $addr['MaDiaChiNhanHang']; ?>">
                <input type="hidden" id="addr-text" value="<?php echo $addr['DiaChiNhanHang']; ?>">
            <?php else: ?>
                <div class="text-danger">Bạn chưa có địa chỉ.</div>
                <button class="btn-change-addr" onclick="openAddrModal()">+ Thêm Địa Chỉ</button>
                <input type="hidden" id="addr-id" value="">
                <input type="hidden" id="addr-text" value="">
            <?php endif; ?>
        </div>
    </div>

    <div class="section-box">
        <div class="prod-header">
            <div>Sản phẩm</div>
            <div>Đơn giá</div>
            <div>Số lượng</div>
            <div>Thành tiền</div>
        </div>
        <?php while($item = $cart->fetch_assoc()): 
            $price = $item['GiaGiam'] > 0 ? $item['GiaGiam'] : $item['GiaGoc'];
            $total = $price * $item['SoLuong'];
            $subtotal += $total;
            $items_data[] = ['sku_id'=>$item['MaSKU'], 'qty'=>$item['SoLuong']];
            $img = $item['HinhAnh'] ? "../img_vid/img_products/".$item['HinhAnh'] : "../img_vid/no-image.png";
        ?>
        <div class="prod-item">
            <div class="prod-info">
                <img src="<?php echo $img; ?>" class="prod-img">
                <div>
                    <div class="prod-name"><?php echo $item['TenSanPham']; ?></div>
                    <div class="prod-meta">Loại: <?php echo $item['Name']; ?></div>
                </div>
            </div>
            <div><?php echo number_format($price); ?>₫</div>
            <div><?php echo $item['SoLuong']; ?></div>
            <div class="prod-total"><?php echo number_format($total); ?>₫</div>
        </div>
        <?php endwhile; ?>
    </div>

    <div class="section-box" style="padding:0; overflow:hidden;">
        <div class="msg-ship-container">
            <div class="msg-box">
                <label>Lời nhắn:</label>
                <input type="text" id="order-note" class="msg-input" placeholder="Lưu ý cho người bán...">
            </div>
            
            <div class="ship-box">
                <div class="ship-header-row">
                    <span>Đơn vị vận chuyển:</span>
                    <span id="ship-name-display" style="color:#00bfa5;">Nhanh</span>
                </div>
                <label class="ship-option-item">
                    <div style="display:flex; align-items:center;">
                        <input type="radio" name="ship" value="Nhanh" checked class="ship-radio" onchange="calcTotal()">
                        <div class="ship-details">
                            <div><strong>Nhanh</strong></div>
                            <div class="ship-date">Nhận hàng 2-4 ngày</div>
                        </div>
                    </div>
                    <div class="ship-price">15.000₫</div>
                </label>
                <label class="ship-option-item">
                    <div style="display:flex; align-items:center;">
                        <input type="radio" name="ship" value="HoaToc" class="ship-radio" onchange="calcTotal()">
                        <div class="ship-details">
                            <div><strong>Hỏa Tốc</strong> <span style="background:#d0021b; color:white; font-size:0.7em; padding:1px 4px;">NOW</span></div>
                            <div class="ship-date">Nhận trong ngày (Nội thành)</div>
                        </div>
                    </div>
                    <div class="ship-price">50.000₫</div>
                </label>
            </div>
        </div>

        <div style="padding: 0 30px 20px 30px;">
            <div class="voucher-section">
                <div style="color:#d0021b; font-weight:bold;"><i class="fas fa-ticket-alt"></i> LocknLock Voucher</div>
                <div style="display:flex; align-items:center; gap:15px;">
                    <span id="voucher-applied-text" style="color:#00bfa5; font-size:0.9em; font-weight:bold;"></span>
                    <span class="btn-select-voucher" onclick="openModal('voucherModal')">Chọn Voucher</span>
                </div>
            </div>
            <div class="text-end mt-3">
                Tổng số tiền (<?php echo count($items_data); ?> sản phẩm): 
                <span class="fs-4 fw-bold text-danger" id="temp-total-display">0₫</span>
            </div>
        </div>
    </div>

    <div class="section-box">
        <div class="option-label mb-3">Phương thức thanh toán</div>
        <div class="payment-methods">
            <div class="pay-btn active" onclick="setPay(this, 'COD')">Thanh toán khi nhận hàng</div>
            <div class="pay-btn" onclick="setPay(this, 'BANK')">Chuyển khoản Ngân hàng</div>
            <input type="hidden" id="pay-method" value="COD">
        </div>

        <div style="margin-top: 30px;"></div>

        <div class="summary-box">
            <div class="summary-row">
                <span class="summary-label">Tổng tiền hàng:</span>
                <span class="summary-value"><?php echo number_format($subtotal); ?>₫</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Phí vận chuyển:</span>
                <span class="summary-value" id="ship-val">15.000₫</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Tổng cộng Voucher giảm giá:</span>
                <span class="summary-value highlight" id="disc-val">-0₫</span>
            </div>
            
            <div class="grand-total-row">
                <span class="grand-total-label">Tổng thanh toán:</span>
                <span class="grand-total-value" id="final-val">0₫</span>
            </div>

            <div style="overflow: hidden; padding-top: 10px;">
                <div class="small text-muted float-start mt-4" style="max-width: 60%;">
                    Nhấn "Đặt hàng" đồng nghĩa với việc bạn đồng ý tuân theo <a href="ChinhSach.php?slug=terms" style="color:#0056b3;">Điều khoản LocknLock</a>
                </div>
                <button class="btn-place-order" onclick="placeOrder()">ĐẶT HÀNG</button>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal-overlay" id="voucherModal">
    <div class="modal-box" style="width:500px;">
        <div class="modal-header">Chọn LocknLock Voucher</div>
        <div class="modal-body voucher-list">
            <?php while($v = $vouchers->fetch_assoc()): 
                $min = $v['GiaTriDonHangToiThieu'];
                $val = $v['MucGiamGia'];
                $isP = $v['LoaiGiamGia'] == 'PERCENT';
                $desc = $isP ? "Giảm $val%" : "Giảm ".number_format($val)."đ";
            ?>
            <label class="voucher-ticket" data-code="<?php echo $v['CodeGiamGia']; ?>" data-id="<?php echo $v['MaGiamGia']; ?>" data-min="<?php echo $min; ?>" data-type="<?php echo $v['LoaiGiamGia']; ?>" data-val="<?php echo $val; ?>">
                <div class="vc-left">
                    <span class="small">Mã</span>
                    <span class="vc-code"><?php echo $v['CodeGiamGia']; ?></span>
                </div>
                <div class="vc-right">
                    <div style="font-weight:bold;"><?php echo $desc; ?></div>
                    <div style="font-size:0.8em; color:#888;">Đơn tối thiểu <?php echo number_format($min); ?>đ</div>
                    <div style="font-size:0.7em; color:#999; margin-top:5px;">HSD: <?php echo date('d.m.Y', strtotime($v['NgayHetHan'])); ?></div>
                </div>
                <input type="radio" name="sel_voucher" class="vc-radio">
            </label>
            <?php endwhile; ?>
        </div>
        <div class="modal-footer">
            <button class="btn-back" onclick="closeModal('voucherModal')">Trở lại</button>
            <button class="btn-confirm" onclick="applyVoucher()">OK</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="addrModal">
    <div class="modal-box">
        <div class="modal-header" id="modal-title">Địa Chỉ Của Tôi</div>
        <div id="addr-list-view" class="modal-body">
            <div id="addr-items">Loading...</div>
            <button class="btn-add-new" onclick="showAddrForm('add')">+ Thêm Địa Chỉ Mới</button>
        </div>
        <div id="addr-form-view" class="modal-body" style="display:none;">
            <form id="frm-addr">
                <input type="hidden" name="action" id="form-action" value="add">
                <input type="hidden" name="id" id="form-id" value="">
                <div class="form-grid-2">
                    <input type="text" name="name" id="inp-name" class="form-input" placeholder="Họ và Tên" required>
                    <input type="text" name="phone" id="inp-phone" class="form-input" placeholder="Số điện thoại" required>
                </div>
                <div class="form-grid-3">
                    <input type="text" id="inp-city" class="form-input" placeholder="Tỉnh/Thành phố" required>
                    <input type="text" id="inp-district" class="form-input" placeholder="Quận/Huyện" required>
                    <input type="text" id="inp-ward" class="form-input" placeholder="Phường/Xã" required>
                </div>
                <div style="margin-bottom:15px;"><input type="text" id="inp-specific" class="form-input" placeholder="Địa chỉ cụ thể" required></div>
                <label style="display:flex;align-items:center;gap:5px;"><input type="checkbox" name="is_default" id="inp-default" value="true"> Đặt làm mặc định</label>
            </form>
        </div>
        <div class="modal-footer" id="ft-list"><button class="btn-back" onclick="closeModal('addrModal')">Hủy</button><button class="btn-confirm" onclick="confirmAddrChoice()">Xác Nhận</button></div>
        <div class="modal-footer" id="ft-form" style="display:none;"><button class="btn-back" onclick="showAddrList()">Trở Lại</button><button class="btn-confirm" onclick="submitAddr()">Hoàn Thành</button></div>
    </div>
</div>

<script>
    const SUBTOTAL = <?php echo $subtotal; ?>;
    const ITEMS = <?php echo json_encode($items_data); ?>;
</script>
<script src="../js/ThanhToan.js"></script>
<?php include 'includes/footer.php'; ?>