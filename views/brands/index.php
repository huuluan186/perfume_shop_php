<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Brand.php';

$brandModel = new Brand();
$all_brands = $brandModel->getAll(); // Lấy tất cả không phân trang

// Nhóm thương hiệu theo chữ cái đầu
$brands_by_letter = [];
foreach ($all_brands as $brand) {
    $first_letter = mb_strtoupper(mb_substr($brand['ten_thuong_hieu'], 0, 1));
    // Chuyển ký tự đặc biệt về chữ cái thường
    if (preg_match('/[A-Z]/', $first_letter)) {
        $brands_by_letter[$first_letter][] = $brand;
    } else {
        $brands_by_letter['#'][] = $brand;
    }
}
ksort($brands_by_letter);

$page_title = "Thương hiệu";
include __DIR__ . '/../layout/header.php';
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Thương Hiệu Nước Hoa</h2>
        <p class="text-muted">Khám phá <?php echo count($all_brands); ?> thương hiệu nước hoa nổi tiếng thế giới</p>
    </div>
    
    <?php if (empty($all_brands)): ?>
    <div class="text-center py-5">
        <i class="fas fa-crown fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Chưa có thương hiệu nào</h5>
    </div>
    <?php else: ?>
    
    <!-- Alphabet Navigation -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <?php 
                $all_letters = range('A', 'Z');
                $all_letters[] = '#'; // Thêm # cho các ký tự đặc biệt
                foreach ($all_letters as $letter): 
                    $has_brand = isset($brands_by_letter[$letter]);
                ?>
                <a href="#letter-<?php echo $letter; ?>" 
                   class="btn btn-sm alphabet-link <?php echo $has_brand ? 'btn-outline-primary' : 'btn-outline-secondary disabled'; ?>"
                   <?php echo !$has_brand ? 'aria-disabled="true"' : ''; ?>>
                    <?php echo $letter; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Brands by Letter -->
    <?php foreach ($brands_by_letter as $letter => $brands): ?>
    <div class="card border-0 shadow-sm mb-4" id="letter-<?php echo $letter; ?>">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 fw-bold"><?php echo $letter; ?></h4>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <?php foreach ($brands as $brand): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="detail.php?id=<?php echo $brand['id']; ?>" 
                       class="d-flex align-items-center text-decoration-none p-3 border rounded hover-shadow">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold text-dark"><?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?></h6>
                            <small class="text-muted">
                                <i class="fas fa-box me-1"></i><?php echo $brand['product_count']; ?> sản phẩm
                            </small>
                        </div>
                        <i class="fas fa-chevron-right text-muted ms-2"></i>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    
    <?php endif; ?>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    transform: translateX(5px);
    background-color: #f8f9fa;
}

.card-header {
    position: sticky;
    top: 70px;
    z-index: 10;
}

.alphabet-link.disabled {
    pointer-events: none;
    opacity: 0.4;
}
</style>

<script>
// Smooth scroll với offset cho alphabet navigation
document.addEventListener('DOMContentLoaded', function() {
    const alphabetLinks = document.querySelectorAll('.alphabet-link:not(.disabled)');
    
    alphabetLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const offset = 150; // Offset 150px để header hiện rõ hoàn toàn
                const targetPosition = targetElement.offsetTop - offset;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
