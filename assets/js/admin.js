// Admin Panel JavaScript

$(document).ready(function() {
    // Auto dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Confirm delete actions
    $('.btn-delete').on('click', function(e) {
        if (!confirm('Bạn có chắc chắn muốn xóa?')) {
            e.preventDefault();
            return false;
        }
    });

    // Preview image before upload
    $('#hinh_anh').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px;">');
            };
            reader.readAsDataURL(file);
        }
    });

    // Table search
    $('#tableSearch').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Select all checkboxes
    $('#selectAll').on('click', function() {
        $('.item-checkbox').prop('checked', this.checked);
    });

    // Bulk actions
    $('#bulkAction').on('change', function() {
        const action = $(this).val();
        const selected = $('.item-checkbox:checked');
        
        if (action && selected.length > 0) {
            if (confirm('Bạn có chắc chắn muốn thực hiện hành động này với ' + selected.length + ' mục đã chọn?')) {
                // Implement bulk action logic
                console.log('Bulk action: ' + action, selected);
            }
        }
        $(this).val('');
    });

    // Number formatting
    $('.format-number').each(function() {
        const value = $(this).text();
        $(this).text(parseInt(value).toLocaleString('vi-VN'));
    });

    // Currency formatting
    $('.format-currency').each(function() {
        const value = $(this).text();
        $(this).text(parseInt(value).toLocaleString('vi-VN') + ' ₫');
    });

    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Form validation
    $('form.needs-validation').on('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });

    // Sidebar toggle for mobile
    $('#sidebarToggle').on('click', function() {
        $('.sidebar').toggleClass('show');
    });

    // Prevent double submission
    $('form').on('submit', function() {
        $(this).find('button[type="submit"]').prop('disabled', true);
    });
});

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Format currency
function formatCurrency(amount) {
    return parseInt(amount).toLocaleString('vi-VN') + ' ₫';
}

// Show loading spinner
function showLoading() {
    $('body').append('<div class="loading-overlay"><div class="spinner-border text-primary" role="status"></div></div>');
}

// Hide loading spinner
function hideLoading() {
    $('.loading-overlay').remove();
}
