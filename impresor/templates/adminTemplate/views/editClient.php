<?php defined("NODIRECT") or die; ?>

<?php echo $this->getViewHtml("header"); ?>
<form method="post" action="./admin.php?sec=clients&func=saveclient">
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo ($client->id>-1?"Editar Cliente: ".$client->id . " - " . $client->name:"Añadir Cliente" );?>  <input type="submit" class="btn btn-xs btn-success pull-right" value="GUARDAR"> <a class="btn btn-xs btn-danger pull-right" href="./admin.php?sec=clients">CANCELAR</a></div>
        <div class="panel-body">
            <div class="container colored">

                <input type="hidden" name="id" value="<?php echo $client->id; ?>">
                <div class="row">
                    <div class="col-md-2">Nombre<br>(SIN ESPACIOS)</div>
                    <div class="col-md-10"><input name="name" type="text" size="50" value="<?php echo $client->name; ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Hardware ID</div>
                    <div class="col-md-10"><input name="hardwareId" type="text" size="50" value="<?php echo $client->hardwareId; ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Path temporal local</div>
                    <div class="col-md-10"><input name="localPath" size="50" type="text" value="<?php echo $client->localPath; ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Es Cliente</div>
                    <div class="col-md-10"><input name="client" type="checkbox"<?php echo ($client->client ? "checked" : ""); ?>></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Es Tendero</div>
                    <div class="col-md-10"><input name="manager" type="checkbox"<?php echo ($client->manager  ? "checked" : ""); ?>></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Puede seleccionar impresora</div>
                    <div class="col-md-10"><input name="selectPrinter" type="checkbox"<?php echo ($client->selectPrinter ? "checked" : ""); ?>></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Notas</div>
                    <div class="col-md-10"><input name="notes" size="50" type="text" value="<?php echo $client->notes; ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Tiempo Límite de transformacion</br>(En segundos por archivo)</div>
                    <div class="col-md-10"><input name="transformTimeOutPerFile" size="50" type="text" value="<?php echo $client->transformTimeOutPerFile; ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Condiciones de impresion preaceptadas</div>

                    <div class="col-md-10"><input name="autoAcceptPrintConditions" type="checkbox" <?php echo ($client->autoAcceptPrintConditions ? "checked" : ""); ?>></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Whatsapp en tendero</div>

                    <div class="col-md-10"><input name="whatsapp" type="checkbox" <?php echo ($client->whatsapp ? "checked" : ""); ?>></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Telegram en tendero</div>

                    <div class="col-md-10"><input name="telegram" type="checkbox" <?php echo ($client->telegram ? "checked" : ""); ?>></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Ruta por defecto para<br>botón añadir</div>
                    <div class="col-md-10"><input name="addFileDefaultPath" type="text" value="<?php echo $client->addFileDefaultPath; ?>"></div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Formatos de Archivo:</div>
         <div><label id="checkalllabel"class="font-weight-normal">Marcar Todos:<input type="checkbox" id="checkall" name="checkall" value="value"></label></div>
                   <div class="panel-body">
                        <?php foreach ($formats as $f): ?>
                            <?php
                            $cFormat = null;
                            $formatNotFound=true;
                            foreach ($clientformats as $clf) {
                                if ($clf->formatId == $f->id) {
                                    $cFormat = $clf;
                                    $formatNotFound=false;
                                }
                            }
                            //inicializamos $cFormat si aun es null
                            if ($cFormat == null) {
                                $cFormat = new stdClass();
                                $cFormat->allowed = 0;
                                $cFormat->transform = 0;
                                $cFormat->analysis = 0;
                            }
                            ?>
                            <div class="row <?php echo (($formatNotFound&&$client->id!=-1)?"danger":"");?>">
                                <div class="col-md-1"><?php echo $f->ext; ?></div>
                                <div class="col-md-1">

                                    Permitido: 
                                    <input  class="fileformatallowed" name="fallowed<?php echo $f->id ?>" type="checkbox" <?php echo ($cFormat->allowed ? "checked" : ""); ?>>

                                </div>
                                <div class="col-md-3">
                                    Transformar: 
                                    <select name="ftransform<?php echo $f->id ?>">
                                        <option value="0" <?php
                                        if ($cFormat->transform == 0) {
                                            echo 'selected="selected"';
                                        }
                                        ?>>No</option>
                                        <option value="1" <?php echo ($cFormat->transform == 1 ? 'selected="selected"' : ""); ?>>En Servidor</option>
                                        <option value="2" <?php echo ($cFormat->transform == 2 ? 'selected="selected"' : ""); ?>>En Cliente Secuencial</option>
                                        <option value="3" <?php echo ($cFormat->transform == 3 ? 'selected="selected"' : ""); ?>>En Cliente Asíncrono</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    Analizar: 
                                    <select name="fanalysis<?php echo $f->id ?>">
                                        <option value="0" <?php echo ($cFormat->analysis == 0 ? 'selected="selected"' : ""); ?>>En Servidor</option>
                                        <option value="1" <?php echo ($cFormat->analysis == 1 ? 'selected="selected"' : ""); ?>>En Cliente</option>
                                    </select>
                                </div>
                            </div>
                        <?php endforeach; ?>


                    </div>        
                </div>    
            </div>
        </div>
    </div>
</form>  

<script type="text/javascript"> 
    $('#checkall').change(function() {
                $(".fileformatallowed").prop('checked', $('#checkall').is(':checked'));
    });
function stopRKey(evt) { 
  var evt = (evt) ? evt : ((event) ? event : null); 
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
} 

document.onkeypress = stopRKey; 

</script>