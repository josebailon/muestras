<?php defined("NODIRECT") or die; ?>

<?php echo $this->getViewHtml("header"); ?>


<div class="panel panel-default">
    <div class="panel-heading">IMPRESORAS</div>
    <div class="panel-body">

        <div class="container colored">
            <div class="row">
                <div class="col-md-1"><a href="./admin.php?sec=printers&func=editprinter" class="btn btn-xs btn-success">+ AÑADIR</a></div>
                <div class="col-md-1">Código</div>
                <div class="col-md-1">Com. Copias</div>
                <div class="col-md-1">Com. Bn</div>
                <div class="col-md-1">Com. Color</div>
                <div class="col-md-1">Com. Enc. Largo</div>
                <div class="col-md-1">Com. Enc. Corto</div>
                <div class="col-md-1">Com. Informes</div>
                <div class="col-md-0_5">Nivel PS</div>
                <div class="col-md-0_5">Fit To Page</div>
                <div class="col-md-1">Modo</div>
                <div class="col-md-1"></div>
            </div>
            <?php foreach ($printers as $p): ?>
                <div class="row">
                    <div class="col-md-1"><a class="btn btn-primary btn-xs" href="./admin.php?sec=printers&func=editprinter&code=<?php echo $p->code; ?>">EDITAR</a></div>
                    <div class="col-md-1" data-toggle="tooltip" data-placement="top" title="<?php echo "Nombre:".$p->name; ?>"><?php echo $p->code; ?></div>
                    <div class="col-md-1 monoline" data-toggle="tooltip" data-placement="top" title="<?php echo $p->comcopies; ?>"><?php echo $p->comcopies; ?></div>
                    <div class="col-md-1 monoline" data-toggle="tooltip" data-placement="top" title="<?php echo $p->combn; ?>"><?php echo $p->combn; ?></div>
                    <div class="col-md-1 monoline" data-toggle="tooltip" data-placement="top" title="<?php echo $p->comcolor; ?>"><?php echo $p->comcolor; ?></div>
                    <div class="col-md-1 monoline" data-toggle="tooltip" data-placement="top" title="<?php echo $p->com2sidesbindingnormal; ?>"><?php echo $p->com2sidesbindingnormal; ?></div>
                    <div class="col-md-1 monoline" data-toggle="tooltip" data-placement="top" title="<?php echo $p->com2sidesbindingsmall; ?>"><?php echo $p->com2sidesbindingsmall; ?></div>
                    <div class="col-md-1 monoline" data-toggle="tooltip" data-placement="top" title="<?php echo $p->comsummaryjobprint; ?>"><?php echo $p->comsummaryjobprint; ?></div>
                    <div class="col-md-0_5 monoline" data-toggle="tooltip" data-placement="top" title="<?php echo $p->pslevel; ?>"><?php echo $p->pslevel; ?></div>
                    <div class="col-md-0_5">
                        <?php echo ($p->fittopage ? "<span class='label label-success'>SI</span>" : "<span class='label label-danger'>NO</span>"); ?>
                    </div>
                    <div class="col-md-1">
                        <?php
                        switch ($p->printmode) {
                            case 0:
                                echo "PostScript";
                                break;
                            case 1:
                                echo "PDF";
                                break;
                        }
                        ?>
                    </div>
                    <div class="col-md-1"><button class="btn btn-danger btn-xs" onclick="borrarPrinter('<?php echo $p->code; ?>', '<?php echo $p->name; ?>')">X</button></div>
                </div>
<?php endforeach; ?>
        </div>
    </div>
</div>



<div class="panel panel-default">
    <div class="panel-heading">FORMATOS</div>
    <div class="panel-body">

        <div class="container colored">
            <div class="row">
                <div class="col-md-1"><a href="./admin.php?sec=printers&func=editpaperformat" class="btn btn-xs btn-success">+ AÑADIR</a></div>
                <div class="col-md-1">Nombre</div>
                <div class="col-md-1">Código</div>
                <div class="col-md-2">Tamaño pt</div>
                <div class="col-md-2">Tamaño cm</div>
            </div>
            <?php foreach ($paperFormats as $pf): ?>
                <div class="row">
                    <div class="col-md-1"><a class="btn btn-primary btn-xs" href="./admin.php?sec=printers&func=editpaperformat&code=<?php echo $pf->code; ?>">EDITAR</a></div>
                    <div class="col-md-1" data-toggle="tooltip" data-placement="top" title="<?php echo "Nombre:".$pf->name; ?>"><?php echo $pf->name; ?></div>
                    <div class="col-md-1" data-toggle="tooltip" data-placement="top" title="<?php echo "Nombre:".$pf->code; ?>"><?php echo $pf->code; ?></div>
                    <div class="col-md-2"><?php echo $pf->w; ?> pt X <?php echo $pf->h; ?>pt</div>
                    <div class="col-md-2"> <?php echo bcdiv(floatval($pf->w), floatval(jboConf::$ptXcm),2);?> cm X <?php echo bcdiv(floatval($pf->h), floatval(jboConf::$ptXcm),2);?> cm</div>
                    <div class="col-md-1"><button class="btn btn-danger btn-xs" onclick="borrarPaperFormat('<?php echo $pf->code; ?>', '<?php echo $pf->name; ?>')">X</button></div>
                </div>
<?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    function borrarPrinter(code, name) {
        if (confirm("¿Borrar la impresora:" + code + " : " + name + "?")) {
            location.href = "./admin.php?sec=printers&func=deleteprinter&code=" + code;
        }
    }

    function borrarPaperFormat(code,name) {
        if (confirm("¿Borrar el formato:" + code + " : " + name + "?")) {
            location.href = "./admin.php?sec=printers&func=deletepaperformat&code=" + code;
        }
    }
</script>