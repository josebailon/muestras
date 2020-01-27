<?php defined("NODIRECT") or die; ?>

<?php
echo $this->getViewHtml("header");
if ($default->code == "") {
    $add = true;
} else {
    $add = false;
}
?>
<form method="post" action="./admin.php?sec=defaults&func=savedefault" onsubmit="return checkForm()">  
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo (!$add ? "Editar Valor por defecto: " . $default->code : "Añadir Valor Por Defecto" ); ?>  <input type="submit" class="btn btn-xs btn-success pull-right" value="GUARDAR"> <a class="btn btn-xs btn-danger pull-right" href="./admin.php?sec=defaults">CANCELAR</a></div>
        <div class="panel-body">
            <div class="container colored">
                <?php if ($add){
                    $readonlystr="";
                }else{
                    $readonlystr="readonly";
                } ?>
                    <div class = "row">
                        <div class = "col-md-2">Nombre</div>
                        <div class = "col-md-10"><input name = "code"  id="code" type = "text" size = "50" value = "<?php echo $default->code; ?>" <?php echo $readonlystr;?>></div>
                    </div>
                <div class="row">
                    <div class="col-md-2">Valor</div>
                    <div class="col-md-10"><input name="value" type="text" size="50" value="<?php echo $default->value; ?>"></div>
                </div>      
            </div>
        </div>
    </div>
</form>  

<script type="text/javascript">
    function checkForm() {
        var value=document.getElementById("code").value;
        if (!value){
            alert("El nombre está vacio o es inválido");
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