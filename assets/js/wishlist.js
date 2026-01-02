// Toggle Wishlist (Add/Remove)
$(document).on('click', '.toggle-wishlist', function() {
    const productId = $(this).data('product-id');
    const isInWishlist = $(this).data('in-wishlist') === '1' || $(this).data('in-wishlist') === 1;
    const baseUrl = window.location.origin + '/perfume_shop_php/';
    const url = isInWishlist ? baseUrl + 'views/wishlist/remove.php' : baseUrl + 'views/wishlist/add.php';
    
    $.ajax({
        url: url,
        method: 'POST',
        data: { product_id: productId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showNotification('success', response.message);
                updateWishlistCount();
                
                // Toggle icon and button style
                const button = $('.toggle-wishlist[data-product-id="' + productId + '"]');
                const icon = button.find('i');
                
                if (isInWishlist) {
                    // Remove from wishlist
                    icon.removeClass('fas').addClass('far');
                    button.removeClass('btn-danger').addClass('btn-outline-danger');
                    button.data('in-wishlist', '0');
                } else {
                    // Add to wishlist
                    icon.removeClass('far').addClass('fas');
                    button.removeClass('btn-outline-danger').addClass('btn-danger');
                    button.data('in-wishlist', '1');
                }
            } else {
                if (response.message.includes('đăng nhập')) {
                    const baseUrl = window.location.origin + '/perfume_shop_php/';
                    window.location.href = baseUrl + 'views/auth/login.php';
                } else {
                    showNotification('error', response.message);
                }
            }
        },
        error: function() {
            showNotification('error', 'Có lỗi xảy ra, vui lòng thử lại!');
        }
    });
});
