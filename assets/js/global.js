base_url = "http://localhost/minerva/";
$(function() {
    $(".formSubmit").submit(function() {
        $("#coverDisplay").css({
            "opacity": "1",
            "width": "100%",
            "height": "100%"
        });
    });
    $("#toast-container").delay(4000).fadeOut('normal');
    $(document).on("click", "img.rank", function() {
        if ($(this).hasClass("clicked")) {
            $("img.rank").removeClass("clicked");
            $("#infoUsuario").hide();
        } else {
            $("img.rank").removeClass("clicked");
            var position = $(this).offset();
            position.top += $(this).height() + 5;
            position.left -= 170;
            $("#infoUsuario").css(position).show();
            $(this).addClass("clicked");
            var nombreEstudiante = $(this).data("nombre");
            $("#divNombreEstudiante").html(nombreEstudiante);
            var idCurso = $(this).data("id-curso");
            var idModulo = $(this).data("id-modulo");
            var idUsuario = $(this).data("id-estudiante");
            $("#contenidoPopover").html("");
            $.ajax({
                url: "../usuario/info",
                data: {
                    idCurso: idCurso,
                    idModulo: idModulo,
                    idUsuario: idUsuario
                },
                success: function(data) {
                    $("#contenidoPopover").html(data);
                }
            });
        }
    });
    $("#cerrarPopover").click(function() {
        $("img.rank").removeClass("clicked");
        $("#infoUsuario").hide();
    });
    /*
     $("img.rank").click(function(e) {
     $("#infoUsuario").show();
     }); */
    $('.modal').on('hidden.bs.modal', function() {
        var form = $(this).find('form');
        if (form.length > 0) {
            form[0].reset();
        }
    });
    $(".btn-file :file").change(function() {

        var input = $(this);
        var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.parent().parent().siblings("input").val(label);
    });
    $("#link-foro").click(function() {
        $.ajax({
            method: "GET",
            url: base_url + "ajax/notificaciones/foroOff",
            success: function(data) {
                //   console.log(data);
            }
        });
    });
    verificarNuevoLogro();
});
function verificarNuevoLogro() {
    $.ajax({
        method: "GET",
        url: base_url + "ajax/logro",
        success: function(data) {
            var json = JSON.parse(data);
            $.each(json, function(key, value) {
                $("#img-logro").attr("src", value.imagen);
                $("#nombre-logro").html(value.nombre);
                $("#nombre-asignatura").html(value.nombre_asignatura);
                $("#descripcion-logro").html(value.descripcion);
                $("#fecha-logro").html(value.fecha_obtencion);
                urlFacebook = value.share_facebook;
                urlTwitter = value.share_twitter;
                $("#modalLogro").modal();
            });
        }
    });
}