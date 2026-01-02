<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../models/Brand.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Order.php';

if (!is_admin()) {
    set_message('error', 'Bạn không có quyền truy cập!');
    redirect('index.php');
}

$productModel = new Product();
$categoryModel = new Category();
$brandModel = new Brand();
$userModel = new User();
$orderModel = new Order();

// Thống kê tổng quan
$total_products = $productModel->count([]);
$total_categories = $categoryModel->count();
$total_brands = $brandModel->count();
$total_users = $userModel->count();
$total_orders = $orderModel->count([]);

// Thống kê đơn hàng theo trạng thái
$pending_orders = $orderModel->count(['status' => ORDER_STATUS_PENDING]);
$approved_orders = $orderModel->count(['status' => ORDER_STATUS_APPROVED]);
$shipping_orders = $orderModel->count(['status' => ORDER_STATUS_SHIPPING]);
$completed_orders = $orderModel->count(['status' => ORDER_STATUS_COMPLETED]);

// Thống kê doanh thu
$revenue_stats = $orderModel->getRevenue();

// Đơn hàng mới nhất
$recent_orders = $orderModel->getAll([], 5, 0);

// Sản phẩm sắp hết hàng
$low_stock_products = $productModel->getLowStock(5);

$page_title = "Admin Dashboard";
include __DIR__ . '/layout/header.php';
?>

<div class="container-fluid my-4">
    <!-- Admin Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Quản Trị</h2>
            <p class="text-muted">Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Tổng sản phẩm</h6>
                            <h3 class="mb-0"><?php echo $total_products; ?></h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-10 border-0">
                    <a href="products/" class="text-white text-decoration-none small">
                        Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Tổng đơn hàng</h6>
                            <h3 class="mb-0"><?php echo $total_orders; ?></h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-10 border-0">
                    <a href="orders/" class="text-white text-decoration-none small">
                        Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Người dùng</h6>
                            <h3 class="mb-0"><?php echo $total_users; ?></h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-10 border-0">
                    <a href="users/" class="text-white text-decoration-none small">
                        Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Doanh thu tháng</h6>
                            <h3 class="mb-0"><?php echo format_currency($revenue_stats['month'] ?? 0); ?></h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-10 border-0">
                    <small>Tổng: <?php echo format_currency($revenue_stats['total'] ?? 0); ?></small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Status Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Chưa duyệt</h6>
                    <h4 class="mb-0 text-warning"><?php echo $pending_orders; ?> đơn</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Đã duyệt</h6>
                    <h4 class="mb-0 text-info"><?php echo $approved_orders; ?> đơn</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Đang giao</h6>
                    <h4 class="mb-0 text-primary"><?php echo $shipping_orders; ?> đơn</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Hoàn thành</h6>
                    <h4 class="mb-0 text-success"><?php echo $completed_orders; ?> đơn</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Đơn hàng mới nhất</h5>
                    <a href="orders/" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recent_orders)): ?>
                    <div class="p-4 text-center text-muted">Chưa có đơn hàng nào</div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td><strong>#<?php echo $order['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($order['ten_nguoi_nhan']); ?></td>
                                    <td><?php echo format_currency($order['tong_tien']); ?></td>
                                    <td>
                                        <?php
                                        $status = (int)$order['trang_thai'];
                                        $badge = 'secondary';
                                        $text = 'Không xác định';
                                        switch($status) {
                                            case ORDER_STATUS_PENDING: $badge = 'warning'; $text = 'Chưa duyệt'; break;
                                            case ORDER_STATUS_APPROVED: $badge = 'info'; $text = 'Đã duyệt'; break;
                                            case ORDER_STATUS_SHIPPING: $badge = 'primary'; $text = 'Đang giao hàng'; break;
                                            case ORDER_STATUS_COMPLETED: $badge = 'success'; $text = 'Hoàn thành'; break;
                                            case ORDER_STATUS_CANCELLED: $badge = 'danger'; $text = 'Đã hủy'; break;
                                        }
                                        ?>
                                        <span class="badge bg-<?php echo $badge; ?>"><?php echo $text; ?></span>
                                    </td>
                                    <td><?php echo format_date($order['ngay_dat']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Low Stock Products -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Sản phẩm sắp hết</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($low_stock_products)): ?>
                    <div class="text-center text-muted">Tất cả sản phẩm đều đủ hàng</div>
                    <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($low_stock_products as $product): ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 small"><?php echo htmlspecialchars($product['ten_san_pham']); ?></h6>
                                    <small class="text-muted"><?php echo htmlspecialchars($product['ten_thuong_hieu']); ?></small>
                                </div>
                                <span class="badge bg-danger"><?php echo $product['so_luong_ton']; ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
