<?php defined("NODIRECT") or die; ?>

<?php echo $this->getViewHtml("header"); ?>
<div class="panel panel-default">
    <div class="panel-heading">CLIENTES</div>
    <div class="panel-body">

        <div class="container colored">
            <div class="row">
                <div class="col-md-1"><a href="./admin.php?sec=clients&func=editclient" class="btn btn-xs btn-success">+ AÑADIR</a></div>
                <div class="col-md-1"></div>
                <div class="col-md-1">Cliente</div>
                <div class="col-md-1">Tendero</div>
                <div class="col-md-1">Select. Impr.</div>
                <div class="col-md-1">Ruta Local</div>
                <div class="col-md-1">Hardware Id</div>
                <div class="col-md-1">Límite Seg. Transf.</div>
                <div class="col-md-1">Auto Cond.</div>
                <div class="col-md-1">Whatsapp</div>
                <div class="col-md-1">Telegram</div>
                <div class="col-md-1"></div>
            </div>
            <?php foreach ($clients as $c): ?>
                <div class="row">
                    <div class="col-md-1"><a class="btn btn-primary btn-xs" href="./admin.php?sec=clients&func=editclient&id=<?php echo $c->id; ?>">EDITAR</a></div>
                    <div class="col-md-1" data-toggle="tooltip" data-placement="top" title="<?php echo $c->notes; ?>"><?php echo $c->name; ?></div>
                    <div class="col-md-1"><?php echo ($c->client ? "<span class='label label-success'>SI</span>" : "<span class='label label-danger'>NO</span>"); ?></div>
                    <div class="col-md-1"><?php echo ($c->manager ? "<span class='label label-success'>SI</span>" : "<span class='label label-danger'>NO</span>"); ?></div>
                    <div class="col-md-1"><?php echo ($c->selectPrinter ? "<span class='label label-success'>SI</span>" : "<span class='label label-danger'>NO</span>"); ?></div>
                    <div class="col-md-1 monoline" data-toggle="tooltip" data-placement="top" title="<?php echo $c->localPath; ?>"><?php echo $c->localPath; ?></div>
                    <div class="col-md-1 monoline" data-toggle="tooltip" data-placement="top" title="<?php echo $c->hardwareId; ?>"><?php echo $c->hardwareId; ?></div>
                    <div class="col-md-1"><?php echo $c->transformTimeOutPerFile; ?></div>
                    <div class="col-md-1"><?php echo ($c->autoAcceptPrintConditions ? "<span class='label label-success'>SI</span>" : "<span class='label label-danger'>NO</span>"); ?></div>
                    <div class="col-md-1"><?php echo ($c->whatsapp ? "<span class='label label-success'>SI</span>" : "<span class='label label-danger'>NO</span>"); ?></div>
                    <div class="col-md-1"><?php echo ($c->telegram ? "<span class='label label-success'>SI</span>" : "<span class='label label-danger'>NO</span>"); ?></div>

                    <div class="col-md-1"><button class="btn btn-danger btn-xs" onclick="borrarUsuario(<?php echo $c->id; ?>, '<?php echo $c->name; ?>')">X</button></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    function borrarUsuario(id, name) {
        if (confirm("¿Borrar el usuaro:" + id + " " + name + "?")) {
            location.href = "./admin.php?sec=clients&func=deleteclient&id=" + id;
        }
    }

</script>