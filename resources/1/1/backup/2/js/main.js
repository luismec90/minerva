findAPITries = 0;

function findAPI(win)
{
// Check to see if the window (win) contains the API
// if the window (win) does not contain the API and
// the window (win) has a parent window and the parent window
// is not the same as the window (win)
    while ((win.API == null) && (win.parent != null) && (win.parent != win))
    {
// increment the number of findAPITries
        findAPITries++;
// Note: 7 is an arbitrary number, but should be more than sufficient
        if (findAPITries > 7)
        {
            alert("Error finding API -- too deeply nested.");
            return null;
        }
// set the variable that represents the window being
// being searched to be the parent of the current window
// then search for the API again
        win = win.parent;
    }
    return win.API;
}
function getAPI()
{
// start by looking for the API in the current window
    var theAPI = findAPI(window);
// if the API is null (could not be found in the current window)
// and the current window has an opener window
    if ((theAPI == null) &&
            (window.opener != null) &&
            (typeof (window.opener) != "undefined"))
    {
// try to find the API in the current window’s opener
        theAPI = findAPI(window.opener);
    }
// if the API has not been found
    if (theAPI == null)
    {
// Alert the user that the API Adapter could not be found
        alert("Unable to find an API adapter");
    }
    return theAPI;
}

var a, b, x, l1;

$(function() {
//    API = getAPI();
    //  API.LMSInitialize("");

    l1 = getRandom(250, 290);
    a = getRandom(10, 50);
    b = getRandom(30, 60);
    x = 180 - a - b;

    var correctAnswer = x;
    var missConception1 = 270 - a - b;
    console.log(correctAnswer + " " + missConception1);
    draw();

    $("#verificar").click(function() {
        var valor = $("#answer").val().trim();
        if (valor != "") {
            $("#correcto").addClass("hide");
            $("#feedback").addClass("hide");
            var calificacion = 0;
            var feedback = "";
            valor = parseFloat(valor);
            switch (valor) {
                case correctAnswer:
                    calificacion = 1.0;
                    $("#correcto").html("Calificaci&oacute;n: <b>" + calificacion + "</b>").removeClass("hide");
                    break;
                case missConception1:
                    calificacion = 0.5;
                    feedback = "Suma de los ángulos interiores de todo triángulo es de 270";
                    $("#feedback").html("Calificaci&oacute;n: <b>" + calificacion + "</b> <br> Probablemente no tienes clara la teoria de triangulos").removeClass("hide");
                    break;
                default:
                    calificacion = 0.0;
                    $("#feedback").html("Calificaci&oacute;n: <b>" + calificacion + "</b> <br>Te recomendamos este <a href='http://www.youtube.com/watch?v=8QccEGEBBTM' target='_blank'>video</a> acerca de triangulos.").removeClass("hide");
                    break;
            }
            $(this).attr("disabled", true);
            //  API.calificar(calificacion, feedback);
            // API.LMSSetValue("cmi.core.score.raw", calificacion);
            // API.LMSFinish("feedback", feedback);
        }
    });
});
function getRandom(bottom, top) {
    return Math.floor(Math.random() * (1 + top - bottom)) + bottom;
}
function draw() {

    ag = toDegrees(a);
    bg = toDegrees(b);
    xg = toDegrees(x);
    var x1 = 5;
    var y1 = 200;

    var x2 = l1;
    var y2 = y1;

    var x3 = x1 + Math.cos(ag) * l1 * Math.sin(bg) / Math.sin(xg);
    var y3 = y1 - Math.sin(ag) * l1 * Math.sin(bg) / Math.sin(xg);


    var canvas = document.getElementById('canvas');

    var ctx = canvas.getContext('2d');

    ctx.strokeStyle = "#0069B2";
    ctx.lineWidth = 2;
    ctx.moveTo(x1, y1);


    ctx.lineTo(x2, y2);

    ctx.lineTo(x3, y3);


    ctx.lineTo(x1, y1);

    ctx.stroke();

    ctx.beginPath(); //iniciar ruta
    ctx.strokeStyle = "FF9900"; //color de línea
    ctx.lineWidth = 1; //grosor de línea
    ctx.arc(x1, y1, 20, -ag, 0);
    ctx.stroke();

    ctx.beginPath(); //iniciar ruta
    ctx.arc(x2, y2, 20, -Math.PI, -Math.PI + bg);
    ctx.stroke();

    ctx.beginPath(); //iniciar ruta
    ctx.arc(x3, y3, 20, bg, -Math.PI - ag);
    ctx.stroke();


    ctx.font = "15px Verdana";
    ctx.fillText("x=?", x3 - 15, y3 - 5);
    ctx.fillText("a=" + a + String.fromCharCode(176), x1 + 10, y1 + 15);
    ctx.fillText("b=" + b + String.fromCharCode(176), x2 - 50, y2 + 15);
}
function toDegrees(angle) {
    return angle * (Math.PI / 180);
}