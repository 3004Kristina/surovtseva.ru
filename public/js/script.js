jQuery(function(){
    'use strict';

    jQuery('.modal').on('closed', function() {
        jQuery('.modal_resume form').trigger('reset');
    });

    jQuery('form').on('submit', (e) => {
        e.preventDefault();
        const form = e.target;
        console.log(e.target);
        const formData = new FormData(form);
        fetch('/server.php', {
            method: 'POST',
            body: formData
        })
            .then(response =>{
                form.reset();
            });
    });
});