document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-eliminar-opinion').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (confirm('¿Estás seguro de que deseas eliminar esta opinión?')) {
                fetch('eliminar_opinion.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id_comentario=' + encodeURIComponent(id)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.opinion-item').remove();
                    } else {
                        alert(data.message || 'No se pudo eliminar la opinión.');
                    }
                })
                .catch(() => alert('Error al eliminar la opinión.'));
            }
        });
    });
});
