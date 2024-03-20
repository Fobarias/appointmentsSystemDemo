$(document).ready(function () {
    $('#fixedPrice').change(function () {
        if(!this.checked) {
            $('#maxPrice').fadeIn('fast');
        } else {
            $('#maxPrice').fadeOut('fast');
        }
    })
});