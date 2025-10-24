document.addEventListener('DOMContentLoaded', function() {
    const ordenSelect = document.querySelector('select[name="orden"]');
    if (ordenSelect) {
        ordenSelect.addEventListener('change', function() {
            document.getElementById('form-orden').submit();
        });
    }
});
