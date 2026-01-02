<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/functions.php';

$page_title = "Giới thiệu";
include __DIR__ . '/layout/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Về Chúng Tôi</h2>
                <p class="text-muted">Cửa hàng nước hoa cao cấp hàng đầu Việt Nam</p>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4">Câu Chuyện Của Chúng Tôi</h4>
                    <p class="lead">
                        Perfume Shop được thành lập với sứ mệnh mang đến cho khách hàng Việt Nam những sản phẩm nước hoa 
                        chính hãng, cao cấp từ các thương hiệu nổi tiếng thế giới với giá cả hợp lý nhất.
                    </p>
                    <p>
                        Với đội ngũ nhân viên giàu kinh nghiệm và am hiểu về nước hoa, chúng tôi cam kết tư vấn nhiệt tình 
                        để giúp bạn tìm được mùi hương phù hợp nhất với phong cách và cá tính của mình.
                    </p>
                </div>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-certificate fa-3x text-primary"></i>
                        </div>
                        <h5 class="fw-bold">100% Chính Hãng</h5>
                        <p class="text-muted">Cam kết sản phẩm chính hãng, nhập khẩu trực tiếp</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-shipping-fast fa-3x text-primary"></i>
                        </div>
                        <h5 class="fw-bold">Giao Hàng Toàn Quốc</h5>
                        <p class="text-muted">Miễn phí vận chuyển cho đơn hàng từ 500.000đ</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-headset fa-3x text-primary"></i>
                        </div>
                        <h5 class="fw-bold">Hỗ Trợ 24/7</h5>
                        <p class="text-muted">Luôn sẵn sàng tư vấn và hỗ trợ khách hàng</p>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4">Tại Sao Chọn Chúng Tôi?</h4>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Sản phẩm đa dạng</h6>
                                    <p class="text-muted">Hơn 500+ sản phẩm từ các thương hiệu nổi tiếng như Chanel, Dior, Gucci, YSL...</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Giá cả cạnh tranh</h6>
                                    <p class="text-muted">Cam kết giá tốt nhất thị trường với nhiều chương trình ưu đãi hấp dẫn</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Đổi trả dễ dàng</h6>
                                    <p class="text-muted">Chính sách đổi trả linh hoạt trong vòng 7 ngày nếu không hài lòng</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Tư vấn chuyên nghiệp</h6>
                                    <p class="text-muted">Đội ngũ tư vấn giàu kinh nghiệm, nhiệt tình và chu đáo</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <h4 class="fw-bold mb-4">Liên Hệ Với Chúng Tôi</h4>
                <p class="mb-4">Để được tư vấn và hỗ trợ nhanh nhất, vui lòng liên hệ:</p>
                <div class="row g-4">
                    <div class="col-md-4">
                        <i class="fas fa-map-marker-alt fa-2x text-primary mb-3"></i>
                        <h6 class="fw-bold">Địa chỉ</h6>
                        <p class="text-muted">123 Nguyễn Huệ, Quận 1<br>TP. Hồ Chí Minh</p>
                    </div>
                    <div class="col-md-4">
                        <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                        <h6 class="fw-bold">Hotline</h6>
                        <p class="text-muted">1900 xxxx<br>0123 456 789</p>
                    </div>
                    <div class="col-md-4">
                        <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                        <h6 class="fw-bold">Email</h6>
                        <p class="text-muted">info@perfumeshop.vn<br>support@perfumeshop.vn</p>
                    </div>
                </div>
            </div>
                        </div>
                        <h5 class="fw-bold">Hỗ Trợ 24/7</h5>
                        <p class="text-muted">Đội ngũ tư vấn luôn sẵn sàng hỗ trợ bạn</p>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4">Cam Kết Của Chúng Tôi</h4>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Sản phẩm 100% chính hãng, có tem nhãn đầy đủ
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Giá cả cạnh tranh nhất thị trường
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Giao hàng nhanh chóng, an toàn
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Hỗ trợ đổi trả trong vòng 7 ngày
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Tư vấn chuyên nghiệp, nhiệt tình
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
