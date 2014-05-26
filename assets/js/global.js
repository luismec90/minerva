var conn;
$(function() {
    if (idUsuarioGlobal != -1) {
        socket();
    }
    $(".formSubmit").submit(function() {
        $("#coverDisplay").css({
            "opacity": "1",
            "width": "100%",
            "height": "100%"
        });
    });
    $("#toast-container").delay(4000).fadeOut('normal');
    $(document).on("click", "img.rank", function() {
        var idCurso = $(this).data("id-curso");
        var idUsuario = $(this).data("id-estudiante");
        modalInfoUsuario(idUsuario, idCurso);

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
    $(".info-usuario").click(function() {
        var idCurso = $(this).data("id-curso");
        var idUsuario = $(this).data("id-usuario");
        modalInfoUsuario(idUsuario, idCurso);
    });
    verificarNuevoLogro();
});

function modalInfoUsuario(idUsuario, idCurso) {
    $("#coverDisplay").css({
        "opacity": "1",
        "width": "100%",
        "height": "100%"
    });
    $.ajax({
        url: base_url + "usuario/info",
        data: {
            idCurso: idCurso,
            idUsuario: idUsuario
        },
        success: function(data) {
            $("#coverDisplay").css({
                "opacity": "0",
                "width": "0",
                "height": "0"
            });
            $("#body-modal-info-usuario").html(data);
            $("#modal-info-usuario").modal();
        }
    });

}

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
                $("#idUsuarioCursoLogro").val(value.id_usuario_curso_logro);
                urlFacebook = value.share_facebook;
                urlTwitter = value.share_twitter;
                $("#modalLogro").modal();
            });
        }
    });
}

function socket() {
    console.log("ini");
     conn = new WebSocket('ws://guiame.medellin.unal.edu.co:8080');
    conn.onopen = function(e) {
        console.log("Connection established!");
        conn.send('Hello World!');
        
    };

    conn.onmessage = function(e) {
        console.log(e.data);
    };

    /*
     var wsUri = "ws://localhost:9000/application/controllers/socket.php";
     websocket = new WebSocket(wsUri);
     
     websocket.onopen = function(ev) { // connection is open 
     console.log("Conectado");
     }
     
     
     
     //#### Message received from server?
     websocket.onmessage = function(ev) {
     var msg = JSON.parse(ev.data); //PHP sends Json data
     console.log(msg);
     if (msg.type == "inicio") {
     var msg = {
     idUsuario: idUsuarioGlobal,
     nombreUsuario: nombreUsuarioGlobal
     };
     //convert and send data to server
     websocket.send(JSON.stringify(msg));
     } else if (msg.type == "run" && msg.idUsuario != null && idUsuarioGlobal != msg.idUsuario) {
     var idUsuario = msg.idUsuario; //message type
     var nombreUsuario = msg.nombreUsuario; //message text
     if ($("#usuario-" + idUsuario).length == 0) {
     $("#usuarios-conectados").append("<li id='usuario-" + idUsuario + "'><a>" + nombreUsuario + "</a></li>");
     }
     
     }
     };
     
     websocket.onerror = function(ev) {
     console.log("error" + ev);
     console.log(ev);
     };
     websocket.onclose = function(ev) {
     console.log("cerrar" + ev);
     };
     */
}