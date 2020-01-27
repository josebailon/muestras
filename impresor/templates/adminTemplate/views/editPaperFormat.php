<?php defined("NODIRECT") or die; ?>

<?php
echo $this->getViewHtml("header");
if ($paperformat->code == "") {
    $add = true;
} else {
    $add = false;
}
?>
<form method="post" action="./admin.php?sec=printers&func=savepaperformat" onsubmit="return checkForm()">  
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo (!$add ? "Editar Formato de papel: " . $paperformat->code : "Añadir Formato de papel" ); ?>  <input type="submit" class="btn btn-xs btn-success pull-right" value="GUARDAR"> <a class="btn btn-xs btn-danger pull-right" href="./admin.php?sec=printers">CANCELAR</a></div>
        <div class="panel-body">
            <div class="container colored">
                                <?php if ($add){
                    $readonlystr="";
                }else{
                    $readonlystr="readonly";
                } ?>
                <div class = "row">
                    <div class = "col-md-2">Nombre</div>
                    <div class = "col-md-10"><input name = "name"  id="code" type = "text" size = "50" value = "<?php echo $paperformat->name; ?>" ></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Código</div>
                    <div class="col-md-10"><input name="code" type="text" size="50" value="<?php echo $paperformat->code; ?>" <?php echo $readonlystr;?>></div>
                </div>      
                <div class="row">
                    <div class="col-md-2">Ancho en pt</div>
                    <div class="col-md-10"><input name="w" type="text" size="50" value="<?php echo $paperformat->w; ?>"></div>
                </div>      
                <div class="row">
                    <div class="col-md-2">Alto en pt</div>
                    <div class="col-md-10"><input name="h" type="text" size="50" value="<?php echo $paperformat->h; ?>"></div>
                </div>      
                <div clas="row"><a class="btn btn-primary" href="https://www.google.com/search?q=convertir+cm+a+puntos+tipograficos&oq=convertir+cm+a+puntos+tipograficos&aqs=chrome..69i57.6655j0j7&sourceid=chrome&ie=UTF-8" target="_blank">Abrir un conversor online a puntos</a></div>
            </div>
        </div>
    </div>
    
    
    
    <div class="panel panel-default">
        <div class="panel-heading">Impresoras por defecto</div>
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
                                    if ($c->printerCode == $p->code && $c->formatCode == $paperformat->code && $c->color == $color && $c->doubleSided == $doubleSided && $c->capable) {
                                        //como esta impresora vale
                                        $valid = true;
                                        //COMPROBAMOS SI ES LA ACTUAL
                                        if ($paperformat->defaultPrinterColorSingle == $p->code) {
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
                                    if ($c->printerCode == $p->code && $c->formatCode == $paperformat->code && $c->color == $color && $c->doubleSided == $doubleSided && $c->capable) {

                                        //como esta impresora vale
                                        $valid = true;
                                        //COMPROBAMOS SI ES LA ACTUAL
                                        if ($paperformat->defaultPrinterColorDouble == $p->code) {
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
                                    if ($c->printerCode == $p->code && $c->formatCode == $paperformat->code && $c->color == $color && $c->doubleSided == $doubleSided && $c->capable) {

                                        //como esta impresora vale
                                        $valid = true;
                                        //COMPROBAMOS SI ES LA ACTUAL
                                        if ($paperformat->defaultPrinterBnSingle == $p->code) {
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
                                    if ($c->printerCode == $p->code && $c->formatCode == $paperformat->code && $c->color == $color && $c->doubleSided == $doubleSided && $c->capable) {
                                        //como esta impresora vale
                                        $valid = true;
                                        //COMPROBAMOS SI ES LA ACTUAL
                                        if ($paperformat->defaultPrinterBnDouble == $p->code) {
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
        var code=document.getElementById("code").value;
        if (!code){
            alert("El codigo está vacio o es inválido");
            return false;
        }
        var w=document.getElementById("w").value;
        if (!w){
            alert("El ancho está vacio o es inválido");
            return false;
        }
        var h=document.getElementById("h").value;
        if (!h){
            alert("El alto está vacio o es inválido");
            return false;
        }
        document.getElementById("w").value=parseFloat(document.getElementById("w").value);
        document.getElementById("h").value=parseFloat(document.getElementById("h").value);
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