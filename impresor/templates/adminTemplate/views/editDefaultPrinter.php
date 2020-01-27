<?php defined("NODIRECT") or die; ?>

<?php
echo $this->getViewHtml("header");
?>
<form method="post" action="./admin.php?sec=defaults&func=savedefaultprinter">
    <input type="hidden" name="formatCode" value="<?php echo $format->code;?>"
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo "Editar Impresoras por defecto para formato  " . $format->name . " - " . $format->code; ?>  <input type="submit" class="btn btn-xs btn-success pull-right" value="GUARDAR"> <a class="btn btn-xs btn-danger pull-right" href="./admin.php?sec=defaults">CANCELAR</a></div>
        <div class="panel-body">
            <div class="container colored">
                <div class="row">
                    <div class="col-md-2">Color 1 cara</div>
                    <div class="col-md-3">
                        <select name="color1">
                            <option value="" >Ninguna</option>
                            <?php
                            $color = true;
                            $doubleSided = false;
                            foreach ($printers as $p)://LOOP DE IMPRESORAS
                                ?>
                                <?php
                                $selected = false;
                                $valid = false; //se marca como true al encontrar una impresora que vale
                                foreach ($capabilities as $c) {//LOOP DE CAPABILITIES
                                    //detectamos la capabilitie concreta para esta impresora este formato color etc..
                                    if ($c->printerCode == $p->code && $c->formatCode == $format->code && $c->color == $color && $c->doubleSided == $doubleSided && $c->capable) {
                                        //como esta impresora vale
                                        $valid = true;
                                        //COMPROBAMOS SI ES LA ACTUAL
                                        if ($format->defaultPrinterColorSingle == $p->code) {
                                            $selected = true;
                                        }
                                    }
                                }//END LOOP DE CAPABILITIES
                                ?>
                                <?php if ($valid): ?>              
                                    <option value="<?php echo $p->code; ?>" <?php echo ($selected ? 'selected="selected"' : ""); ?>><?php echo $p->name; ?></option>
                                <?php endif; ?>
                            <?php endforeach; //END LOOP DE IMPRESORAS?>
                        </select>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">Color 2 caras</div>
                    <div class="col-md-3">
                        <select name="color2">
                            <option value="" >Ninguna</option>
                            <?php
                            $color = true;
                            $doubleSided = true;
                            foreach ($printers as $p)://LOOP DE IMPRESORAS
                                ?>

                                <?php
                                $selected = false;
                                $valid = false; //se marca como true al encontrar una impresora que vale
                                foreach ($capabilities as $c) {//LOOP DE CAPABILITIES
                                    //detectamos la capabilitie concreta para esta impresora este formato color etc..
                                    if ($c->printerCode == $p->code && $c->formatCode == $format->code && $c->color == $color && $c->doubleSided == $doubleSided && $c->capable) {

                                        //como esta impresora vale
                                        $valid = true;
                                        //COMPROBAMOS SI ES LA ACTUAL
                                        if ($format->defaultPrinterColorDouble == $p->code) {
                                            $selected = true;
                                        }
                                    }
                                }//END LOOP DE CAPABILITIES
                                ?>
                                <?php if ($valid): ?>              
                                    <option value="<?php echo $p->code; ?>" <?php echo ($selected ? 'selected="selected"' : ""); ?>><?php echo $p->name; ?></option>
                                <?php endif; ?>
                            <?php endforeach; //END LOOP DE IMPRESORAS?>
                        </select>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">BN 1 cara</div>
                    <div class="col-md-3">
                        <select name="bn1">
                            <option value="" >Ninguna</option>
                            <?php
                            $color = false;
                            $doubleSided = false;
                            foreach ($printers as $p)://LOOP DE IMPRESORAS
                                ?>

                                <?php
                                $selected = false;
                                $valid = false; //se marca como true al encontrar una impresora que vale
                                foreach ($capabilities as $c) {//LOOP DE CAPABILITIES
                                    //detectamos la capabilitie concreta para esta impresora este formato color etc..
                                    if ($c->printerCode == $p->code && $c->formatCode == $format->code && $c->color == $color && $c->doubleSided == $doubleSided && $c->capable) {

                                        //como esta impresora vale
                                        $valid = true;
                                        //COMPROBAMOS SI ES LA ACTUAL
                                        if ($format->defaultPrinterBnSingle == $p->code) {
                                            $selected = true;
                                        }
                                    }
                                }//END LOOP DE CAPABILITIES
                                ?>
                                <?php if ($valid): ?>              
                                    <option value="<?php echo $p->code; ?>" <?php echo ($selected ? 'selected="selected"' : ""); ?>><?php echo $p->name; ?></option>
                                <?php endif; ?>
                            <?php endforeach; //END LOOP DE IMPRESORAS?>
                        </select>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">BN 2 caras</div>
                    <div class="col-md-3">
                        <select name="bn2">
                            <option value="" >Ninguna</option>
                            <?php
                            $color = false;
                            $doubleSided = true;
                            foreach ($printers as $p)://LOOP DE IMPRESORAS
                                ?>

                                <?php
                                $selected = false;
                                $valid = false; //se marca como true al encontrar una impresora que vale
                                foreach ($capabilities as $c) {//LOOP DE CAPABILITIES
                                    //detectamos la capabilitie concreta para esta impresora este formato color etc..
                                    if ($c->printerCode == $p->code && $c->formatCode == $format->code && $c->color == $color && $c->doubleSided == $doubleSided && $c->capable) {

                                        //como esta impresora vale
                                        $valid = true;
                                        //COMPROBAMOS SI ES LA ACTUAL
                                        if ($format->defaultPrinterBnDouble == $p->code) {
                                            $selected = true;
                                        }
                                    }
                                }//END LOOP DE CAPABILITIES
                                ?>
                                <?php if ($valid): ?>              
                                    <option value="<?php echo $p->code; ?>" <?php echo ($selected ? 'selected="selected"' : ""); ?>><?php echo $p->name; ?></option>
                                <?php endif; ?>
                            <?php endforeach; //END LOOP DE IMPRESORAS?>
                        </select>

                    </div>
                </div>
            </div>
        </div>
    </div>              
</form>  

<script type="text/javascript">
    function checkForm() {
        var value = document.getElementById("formatCode").value;
        if (!value) {
            alert("El codigo está vacio o es inválido");
            return false;
        }
        return true;
    }
    function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type == "text")) {
            return false;
        }
    }

    document.onkeypress = stopRKey;

</script>