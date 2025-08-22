$(document).ready(function () {
    // Barra de búsqueda de texto libre ----
    $('.search-container').submit(function (e) {
        e.preventDefault();
        var texto = $('.search-container input[type="search"]').val().trim().toLowerCase();
        if (texto.length === 0) {
            // Si el campo de búsqueda está vacío, muestra todas las filas cargadas
            $('#tabla-reportes tbody tr').show();
        } else {
            $('#tabla-reportes tbody tr').each(function () {
                var encontrado = false;
                $(this).find('td').each(function () {
                    if ($(this).text().toLowerCase().includes(texto)) {
                        encontrado = true;
                    }
                });
                if (encontrado) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    });
    
    $('#btn-generar').click(function () {
        var tipo = $('#tipo-recurso').val();
        var fecha_inicio = $('#fecha-inicio').val();
        var fecha_fin = $('#fecha-fin').val();
        $.post('index.php?controller=reportes&action=generar', {
            tipo: tipo,
            fecha_inicio: fecha_inicio,
            fecha_fin: fecha_fin
        }, function (resp) {
            try {
                var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                var html = '';
                if (r.success && r.reportes.length > 0) {
                    r.reportes.forEach(function (rep) {
                        html += '<tr>';
                        html += '<td>' + rep.tipo + '</td>';
                        html += '<td>' + rep.recurso + '</td>';
                        html += '<td>' + rep.usuario + ' ' + rep.apellido + '</td>';
                        html += '<td>' + rep.fecha_prestamo + '</td>';
                        html += '<td>' + rep.fecha_devolucion + '</td>';
                        html += '<td>' + rep.estado + '</td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="6">No hay resultados</td></tr>';
                }
                $('#tabla-reportes tbody').html(html);
            } catch (e) {
                $('#tabla-reportes tbody').html(
                    '<tr><td colspan="6">Error al cargar reportes</td></tr>');
            }
        });
    });

    $('#exportar-excel').click(function () {
        var table = document.getElementById('tabla-reportes');
        var html = table.outerHTML.replace(/ /g, '%20');
        var a = document.createElement('a');
        a.href = 'data:application/vnd.ms-excel,' + html;
        a.download = 'reporte.xls';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    });
});