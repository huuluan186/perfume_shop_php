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
$revenue_total = $orderModel->getRevenue();
$total_revenue = $revenue_total['total_revenue'] ?? 0;

// Doanh thu tháng này
$first_day_of_month = date('Y-m-01');
$last_day_of_month = date('Y-m-t');
$revenue_month = $orderModel->getRevenue($first_day_of_month, $last_day_of_month);
$month_revenue = $revenue_month['total_revenue'] ?? 0;

// Doanh thu 7 ngày qua (cho biểu đồ)
$daily_revenue = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $revenue = $orderModel->getRevenue($date, $date);
    $daily_revenue[] = [
        'date' => date('d/m', strtotime($date)),
        'revenue' => $revenue['total_revenue'] ?? 0
    ];
}

// Đơn hàng mới nhất
$recent_orders = $orderModel->getAll([], 5, 0);

// Sản phẩm sắp hết hàng
$low_stock_products = $productModel->getLowStock(5);

// Sản phẩm bán chạy nhất
$top_selling_products = $productModel->getTopSelling(5);

$page_title = "Admin Dashboard";
include __DIR__ . '/layout/header.php';
?>

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
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
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
            <div class="card border-0 shadow-sm bg-success text-white h-100">
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
            <div class="card border-0 shadow-sm bg-warning text-white h-100">
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
            <div class="card border-0 shadow-sm bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Tổng doanh thu</h6>
                            <h3 class="mb-0 fs-5"><?php echo format_currency($total_revenue); ?></h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fas fa-dollar-sign"></i>
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
    
    <!-- Revenue Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2"></i>Doanh thu 7 ngày gần đây</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Selling Products Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-chart-bar me-2"></i>Top 5 sản phẩm bán chạy nhất</h5>
                </div>
                <div class="card-body">
                    <canvas id="topSellingChart" height="80"></canvas>
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
                                    <td><?php echo htmlspecialchars($order['ho_ten_nguoi_nhan'] ?? ''); ?></td>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Dữ liệu biểu đồ doanh thu
const revenueData = <?php echo json_encode($daily_revenue); ?>;
const revenueLabels = revenueData.map(item => item.date);
const revenueValues = revenueData.map(item => item.revenue);

// Vẽ biểu đồ doanh thu
const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctxRevenue, {
    type: 'line',
    data: {
        labels: revenueLabels,
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: revenueValues,
            borderColor: 'rgb(13, 110, 253)',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: 'rgb(13, 110, 253)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                    }
                }
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Ngày',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Doanh thu (VNĐ)',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                },
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN', { notation: 'compact', compactDisplay: 'short' }).format(value);
                    }
                }
            }
        }
    }
});

// Dữ liệu biểu đồ sản phẩm bán chạy
const topSellingData = <?php echo json_encode($top_selling_products); ?>;
const productLabels = topSellingData.map(item => {
    const name = item.ten_san_pham;
    return name.length > 25 ? name.substring(0, 25) + '...' : name;
});
const productValues = topSellingData.map(item => parseInt(item.total_sold));

// Vẽ biểu đồ sản phẩm bán chạy
const ctxTopSelling = document.getElementById('topSellingChart').getContext('2d');
const topSellingChart = new Chart(ctxTopSelling, {
    type: 'bar',
    data: {
        labels: productLabels,
        datasets: [{
            label: 'Số lượng đã bán',
            data: productValues,
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    title: function(context) {
                        const index = context[0].dataIndex;
                        return topSellingData[index].ten_san_pham;
                    },
                    label: function(context) {
                        return 'Đã bán: ' + context.parsed.y + ' sản phẩm';
                    }
                }
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Sản phẩm',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Số lượng đã bán',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                },
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
