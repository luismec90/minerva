<footer>
    <div id="contenedor-footer" class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <img id="escudo-un" class="pull-right" height="60" src="<?= base_url() ?>assets/img/logo_un.png"> 
                <ul class="list-unstyled list-inline social">
                    <li><a href="#" ><i class="fa fa-facebook"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<?php if ($this->session->flashdata('mensaje')) { ?>
    <div id="toast-container" class="toast-top-center">
        <div class="toast toast-<?= $this->session->flashdata('tipo') ?>">
            <div class="toast-message"><?= $this->session->flashdata('mensaje') ?></div>
        </div>
    </div>
<?php } ?>
<div id="infoUsuario" class="popover fade bottom in">
    <div class="arrow"></div><h3 class="popover-title"> <button id="cerrarPopover" type="button" class="close"> &times; </button> <span id="divNombreEstudiante"></span></h3>
    <div id="contenidoPopover" class="popover-content">
    </div>
</div>
<div id="coverDisplay">
    <img id="imgLoading" src="<?= base_url() ?>assets/img/loading.gif">
</div>
<div class="modal fade" id="modalLogro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Logro obtenido</h4>
            </div>
            <div id="bodyModalLogro" class="modal-body">
                <div class="row">
                    <div class="col-xs-4">
                        <img id="img-logro" src="" class="col-xs-12">
                    </div>
                    <div class="col-xs-8">
                        <div class="row">
                            <div class="col-xs-12">
                                <b>Nombre: </b><span id="nombre-logro">
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <b>Curso: </b><span id="nombre-asignatura">
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <b>Descripción:</b> <span id="descripcion-logro" >
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <b>Fecha:</b> <span id="fecha-logro"></span></b>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="cerrar-modal-logros" type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <a id="compartir-facebook" class="btn btn-primary" href="javascript: void(0);" onclick="window.open(urlFacebook, 'ventanacompartir', 'toolbar=0, status=0, width=650, height=450');">Compartir en Facebook</a>
                <a id="compartir-facebook" class="btn btn-primary" href="javascript: void(0);" onclick="window.open(urlTwitter, 'ventanacompartir', 'toolbar=0, status=0, width=650, height=450');">Compartir en Twitter</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-info-usuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Información del usuario</h4>
            </div>
            <div id="body-modal-info-usuario" class="modal-body">
            </div>
            <div class="modal-footer">
                <button id="cerrar-modal-logros" type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>assets/libs/jQuery-1.11.0/jQuery.min.js"></script>
<script src="<?= base_url() ?>assets/libs/bootstrap-3.1.1/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/global.js"></script>
<?php if (isset($js)) foreach ($js as $row) { ?>
        <script src="<?= base_url() ?>assets/<?= $row ?>.js"></script>
    <?php } ?>
</body>
</html>