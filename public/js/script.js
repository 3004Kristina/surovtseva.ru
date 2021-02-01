jQuery(function() {
    'use strict';

    jQuery('form').each(function() {
        jQuery(this).validate({
            rules: {
                name: 'required',
                phone: 'required',
                email: {
                    required: true,
                    email: true
                }
            },

            submitHandler(form) {
                const formData = new FormData(form);
                fetch('/server.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        jQuery('[data-remodal-id="modal"]').remodal().close();
                        jQuery('.thanks-modal').remodal().open();
                        form.reset();
                    });
            }
        });
    });


    jQuery('#telephone-input').mask('+7 (999) 999-99-99');
    jQuery('#modal-telephone-input').mask('+7 (999) 999-99-99');

    jQuery('a[href^="#block-"]').on('click', function(e) {
        e.preventDefault();
        const _href = jQuery(this).attr('href');
        jQuery('html, body').animate({scrollTop: jQuery(_href).offset().top + 'px'});
    });
});

