(function ($) {
    'use strict';

    const swalDefaults = {
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
    };

    function showFlashMessages() {
        const flash = window.adminFlash || {};
        if (flash.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: flash.success,
                confirmButtonColor: swalDefaults.confirmButtonColor,
            });
        }
        if (flash.error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: flash.error,
                confirmButtonColor: swalDefaults.confirmButtonColor,
            });
        }
        if (flash.warning) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: flash.warning,
                confirmButtonColor: swalDefaults.confirmButtonColor,
            });
        }
        if (flash.info) {
            Swal.fire({
                icon: 'info',
                title: 'Notice',
                text: flash.info,
                confirmButtonColor: swalDefaults.confirmButtonColor,
            });
        }
    }

    $(function () {
        showFlashMessages();

        $('body').on('click', '[data-swal-confirm]', function (e) {
            e.preventDefault();
            const $el = $(this);

            Swal.fire({
                title: $el.data('swal-title') || 'Are you sure?',
                html: $el.data('swal-html') || $el.data('swal-text') || '',
                icon: $el.data('swal-icon') || 'question',
                showCancelButton: true,
                confirmButtonColor: $el.data('swal-confirm-color') || swalDefaults.confirmButtonColor,
                cancelButtonColor: swalDefaults.cancelButtonColor,
                confirmButtonText: $el.data('swal-confirm-text') || 'Yes, continue',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    const href = $el.attr('href');
                    if (href) {
                        window.location.href = href;
                    } else if ($el.data('swal-form')) {
                        $($el.data('swal-form')).submit();
                    }
                }
            });
        });

        $('body').on('click', '[data-copy-url]', function () {
            const url = $(this).data('copy-url');
            navigator.clipboard.writeText(url).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'URL copied to clipboard.',
                    timer: 2000,
                    showConfirmButton: false,
                });
            }).catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Copy failed',
                    text: 'Could not copy the URL.',
                    confirmButtonColor: swalDefaults.confirmButtonColor,
                });
            });
        });

        $('body').on('click', '[data-swal-delete]', function (e) {
            e.preventDefault();
            const $el = $(this);
            const url = $el.data('swal-delete');
            const title = $el.data('swal-title') || 'Delete this item?';
            const text = $el.data('swal-text') || 'This action cannot be undone.';

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: swalDefaults.cancelButtonColor,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading(),
                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: $el.data('swal-success') || 'Item has been deleted.',
                            confirmButtonColor: swalDefaults.confirmButtonColor,
                        }).then(() => location.reload());
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong while deleting.',
                            confirmButtonColor: swalDefaults.confirmButtonColor,
                        });
                    },
                });
            });
        });
    });
})(jQuery);
