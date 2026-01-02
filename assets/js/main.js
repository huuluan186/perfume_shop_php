// Global functions and event handlers

$(document).ready(function() {
    // Back to top button
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            $('#backToTop').fadeIn();
        } else {
            $('#backToTop').fadeOut();
        }
    });

    $('#backToTop').click(function() {
        $('html, body').animate({scrollTop: 0}, 600);
        return false;
    });

    // Add to cart
    $(document).on('click', '.add-to-cart', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        
        // Lấy số lượng từ input nếu có (trang detail), nếu không thì từ data-quantity, nếu không có thì mặc định là 1
        let quantity = 1;
        const quantityInput = document.getElementById('productQuantity');
        if (quantityInput) {
            quantity = parseInt(quantityInput.value) || 1;
        } else if ($(this).data('quantity')) {
            quantity = $(this).data('quantity');
        }
        
        console.log('Adding to cart - Product ID:', productId, 'Quantity:', quantity);
        
        $.ajax({
            url: baseUrl + 'views/cart/add.php',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    updateCartCount();
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function() {
                showNotification('error', 'Có lỗi xảy ra!');
            }
        });
    });

    // Add to wishlist
    $(document).on('click', '.add-to-wishlist', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        
        $.ajax({
            url: baseUrl + 'views/wishlist/add.php',
            method: 'POST',
            data: {
                product_id: productId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    updateWishlistCount();
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function() {
                showNotification('error', 'Có lỗi xảy ra!');
            }
        });
    });

    // Remove from wishlist
    $(document).on('click', '.remove-from-wishlist', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        
        if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?')) {
            $.ajax({
                url: baseUrl + 'views/wishlist/remove.php',
                method: 'POST',
                data: {
                    product_id: productId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showNotification('success', response.message);
                        updateWishlistCount();
                        location.reload();
                    } else {
                        showNotification('error', response.message);
                    }
                },
                error: function() {
                    showNotification('error', 'Có lỗi xảy ra!');
                }
            });
        }
    });

    // Update cart quantity
    $(document).on('change', '.cart-quantity', function() {
        const productId = $(this).data('product-id');
        const quantity = $(this).val();
        
        $.ajax({
            url: baseUrl + 'views/cart/update.php',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function() {
                showNotification('error', 'Có lỗi xảy ra!');
            }
        });
    });

    // Product image gallery
    $('.product-thumbnails img').click(function() {
        const newSrc = $(this).attr('src');
        $('.product-detail-image img').attr('src', newSrc);
        $('.product-thumbnails img').removeClass('active');
        $(this).addClass('active');
    });

    // Auto dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});

// Update cart count
function updateCartCount() {
    $.ajax({
        url: baseUrl + 'views/cart/count.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#cartCount').text(response.count);
            }
        }
    });
}

// Update wishlist count
function updateWishlistCount() {
    $.ajax({
        url: baseUrl + 'views/wishlist/count.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#wishlistCount').text(response.count);
            }
        }
    });
}

// Show notification
function showNotification(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-danger' : 'alert-info');
    const alert = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" 
             style="z-index: 9999; min-width: 300px;" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('body').append(alert);
    
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 3000);
}

// Form validation
function validateForm(formId) {
    let isValid = true;
    const form = document.getElementById(formId);
    
    $(form).find('input[required], select[required], textarea[required]').each(function() {
        const field = $(this);
        const value = field.val().trim();
        
        // Remove previous error
        field.removeClass('is-invalid');
        field.siblings('.error-message').remove();
        
        if (!value) {
            isValid = false;
            field.addClass('is-invalid');
            field.after('<div class="error-message show">Vui lòng nhập trường này</div>');
        }
        
        // Email validation
        if (field.attr('type') === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                field.addClass('is-invalid');
                field.after('<div class="error-message show">Email không hợp lệ</div>');
            }
        }
        
        // Phone validation
        if (field.attr('type') === 'tel' && value) {
            const phoneRegex = /^[0-9]{10}$/;
            if (!phoneRegex.test(value.replace(/\s/g, ''))) {
                isValid = false;
                field.addClass('is-invalid');
                field.after('<div class="error-message show">Số điện thoại không hợp lệ</div>');
            }
        }
        
        // Password confirmation
        if (field.attr('name') === 'confirm_password' && value) {
            const password = $('input[name="password"]').val();
            if (value !== password) {
                isValid = false;
                field.addClass('is-invalid');
                field.after('<div class="error-message show">Mật khẩu không khớp</div>');
            }
        }
    });
    
    return isValid;
}

// Base URL (set this in each page that uses AJAX)
const baseUrl = window.location.origin + '/perfume_shop_php/';
