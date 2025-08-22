$(document).ready(function () {
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
                $('#tabla-reportes tbody').html('<tr><td colspan="6">Error al cargar reportes</td></tr>');
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