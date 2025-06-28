

<!-- jQuery y Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



<script>
// Prellenar modal al editar
$(document).ready(function() {
    $('a[href*="editar="]').click(function(e) {
        e.preventDefault();
        $.get($(this).attr('href'), function(data) {
            let empresa = JSON.parse(data);
            $('#id_empresa').val(empresa.id_empresa);
            $('input[name="nombre"]').val(empresa.nombre);
            $('input[name="rif"]').val(empresa.rif);
            $('#modalEmpresa').modal('show');
        });
    });
});
</script>


<!-- jQuery y Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



<script>
// Prellenar modal de cuentas
$(document).ready(function() {
    $('a[href*="editar="]').click(function(e) {
        e.preventDefault();
        $.get($(this).attr('href'), function(data) {
            let cuenta = JSON.parse(data);
            $('#id_cuenta').val(cuenta.id_cuenta);
            $('input[name="codigo"]').val(cuenta.codigo);
            $('input[name="nombre"]').val(cuenta.nombre);
            $('select[name="tipo"]').val(cuenta.tipo);
            $('#modalCuenta').modal('show');
        });
    });
});
</script>
