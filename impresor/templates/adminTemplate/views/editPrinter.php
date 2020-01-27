<?php defined("NODIRECT") or die; ?>

<?php
echo $this->getViewHtml("header");
if ($printer->code == "") {
    $add = true;
} else {
    $add = false;
}
?>
<form method="post" action="./admin.php?sec=printers&func=saveprinter">
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo ($printer->code!=""?"Editar Impresora: ".$printer->name . " - " . $printer->code:"Añadir Impresora" );?>  <input type="submit" class="btn btn-xs btn-success pull-right" value="GUARDAR"> <a class="btn btn-xs btn-danger pull-right" href="./admin.php?sec=printers">CANCELAR</a></div>
        <div class="panel-body">
            <div class="container colored">
                <?php if ($add){
                    $readonlystr="";
                }else{
                    $readonlystr="readonly";
                } ?>
                <div class="row">
                    <div class="col-md-2">Nombre</div>
                    <div class="col-md-10"><input name="name" type="text" size="50" value="<?php echo $printer->name; ?>"></div>
                </div>
                    <div class = "row">
                        <div class = "col-md-2">Código</div>
                        <div class = "col-md-10"><input name = "code"  id="code" type = "text" size = "50" value = "<?php echo $printer->code; ?>" <?php echo $readonlystr;?>></div>
                    </div>
                <div class="row">
                    <div class="col-md-2">Comando copias</div>
                    <div class="col-md-10"><input name="comcopies" type="text" size="50" value="<?php echo $printer->comcopies; ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Comando B&N</div>
                    <div class="col-md-10"><input name="combn" size="50" type="text" value="<?php echo $printer->combn; ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Comando Color</div>
                    <div class="col-md-10"><input name="comcolor" size="50" type="text" value="<?php echo $printer->comcolor; ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Com. borde largo</div>
                    <div class="col-md-10"><input name="com2sidesbindingnormal" size="50" type="text" value="<?php echo $printer->com2sidesbindingnormal; ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Com. borde corto</div>
                    <div class="col-md-10"><input name="com2sidesbindingsmall" size="50" type="text" value="<?php echo $printer->com2sidesbindingsmall; ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Com. impresión de informe de trabajo</div>
                    <div class="col-md-10"><input name="comsummaryjobprint" size="50" type="text" value="<?php echo $printer->comsummaryjobprint; ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Nivel PostScript</div>
                    <div class="col-md-10"><input name="pslevel" size="50" type="text" value="<?php echo $printer->pslevel; ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Modo de impresión</div>
                    <div class="col-md-10">
                        <select name="printmode">
                            <option value="0" <?php echo ($printer->printmode == 0 ? 'selected="selected"' : ""); ?>>PostScript</option>
                            <option value="1" <?php echo ($printer->printmode == 1 ? 'selected="selected"' : ""); ?>>PDF</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">Fit To Page en comando de impresión</div>
                    <div class="col-md-10"><input name="fittopage" type="checkbox"<?php echo ($printer->fittopage ? "checked" : ""); ?>></div>
                </div>
                
              </div>
        </div>
    </div>              
 <div class="panel panel-default">
        <div class="panel-heading">Capacidades de la impresora</div>
        <div><label id="checkalllabel"class="font-weight-normal">Marcar Todos:<input type="checkbox" id="checkall" name="checkall" value="value"></label></div>
        <div class="panel-body">
            <div class="container colored">               
                <?php
                $lastCap="";
                foreach ($capabilities as $cap):
                ?>
                <?php 
                if ($cap->name!=$lastCap):?>
                <div class="row">
                    <div class="col-md-12"><strong><?php echo $cap->name;?></strong></div>
                </div>
                <?php
                endif;
                $lastCap= $cap->name;//control para cabeceras de formato
                $strcolor = ($cap->color ? "Color" : "B&N"); 
                $strdoubleSided =  ($cap->doubleSided ? "2 Caras" : "1 Cara"); 
                ?>
                <div class="row">
                    <div class="col-md-2"><?php echo $strcolor." ".$strdoubleSided;?></div>
                    <div class="col-md-10"><input class="capability" name="<?php echo $cap->printcode ?>" type="checkbox"<?php echo ($cap->capable ? "checked" : ""); ?>></div>
                </div>
                <?php        endforeach;?>
            </div>
        </div>
    </div>
</form>  

<script type="text/javascript"> 
    
    
    
    $('#checkall').change(function() {
                $(".capability").prop('checked', $('#checkall').is(':checked'));
    });
    /*
    $("#checkall").on('change',function(){
        alert("ambrosio");
        $(".capability").prop('checked', $('#checkall').is(':checked'););
    });*/
    
    function checkForm() {
        var value=document.getElementById("code").value;
        if (!value){
            alert("El codigo está vacio o es inválido");
            return false;
        }
        return true;
    }
function stopRKey(evt) { 
  var evt = (evt) ? evt : ((event) ? event : null); 
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
} 

document.onkeypress = stopRKey; 

</script>