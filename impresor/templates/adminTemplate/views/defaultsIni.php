<?php defined("NODIRECT") or die; ?>

<?php echo $this->getViewHtml("header"); ?>

<div class="panel panel-default">
    <div class="panel-heading">VALORES POR DEFECTO</div>
    <div class="panel-body">
        <p><a href="./admin.php?sec=defaults&func=editdefault" class="btn btn-xs btn-success">+ AÑADIR</a></p>
        <div class="container colored">
            <?php foreach ($defaults as $d): ?>
                <div class="row">
                    <div class="col-md-1"><a class="btn btn-primary btn-xs" href="./admin.php?sec=defaults&func=editdefault&code=<?php echo $d->code; ?>">EDITAR</a></div>
                    <div class="col-md-2"><?php echo $d->code; ?></div>
                    <div class="col-md-8"><?php echo $d->value; ?></div>
                    <div class="col-md-1"><button class="btn btn-danger btn-xs" onclick="borrarDefault('<?php echo $d->code; ?>', '<?php echo $d->value; ?>')">X</button></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="panel panel-default">
        <div class="panel-heading">IMPRESORAS POR DEFECTO</div>
        <div class="panel-body">
            <div class="container colored">  
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-2"><strong>Formato</strong></div>
                    <div class="col-md-2"><strong>Color 1 cara</strong></div>
                    <div class="col-md-2"><strong>Color 2 caras</strong></div>
                    <div class="col-md-2"><strong>BN 1 cara</strong></div>
                    <div class="col-md-2"><strong>BN 2 caras</strong></div>
                </div>
                <?php
                foreach ($formats as $f):
                ?>
                <div class="row">
                    <div class="col-md-1"><a class="btn btn-primary btn-xs" href="./admin.php?sec=defaults&func=editdefaultprinter&code=<?php echo $f->code; ?>">EDITAR</a></div>
                    <div class="col-md-2"><?php echo $f->name;?></div>
                    <div class="col-md-2"><?php echo $f->defaultPrinterColorSingle ;?></div>
                    <div class="col-md-2"><?php echo $f->defaultPrinterColorDouble ;?></div>
                    <div class="col-md-2"><?php echo $f->defaultPrinterBnSingle ;?></div>
                    <div class="col-md-2"><?php echo $f->defaultPrinterBnDouble ;?></div>
                </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</form>  


<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    function borrarDefault(code, value) {
        if (confirm("¿Borrar el valor por defecto:" + code + " : " + value + "?")) {
            location.href = "./admin.php?sec=defaults&func=deletedefault&code=" + code;
        }
    }

</script>