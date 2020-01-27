<?php defined("NODIRECT") or die; ?>
<?php echo $this->getViewHtml("header");?>
<div class="container">
 <div class="panel panel-default">
    <div class="panel-heading">CLIENTES ACTIVOS  
        <?php if($editingConfiguration):?>
       <a href="./admin.php?func=unblockClients" class="btn-xs btn-danger">LOS CLIENTES ESTÁN BLOQUEADOS POR EL TENDERO PULSE AQUÍ PARA DESBLOQUEAR</a>
        <?php endif;?>
    </div>
    <div class="panel-body">
        <?php
        if (count($clientsWorking)>0){
            foreach ($clientsWorking as $client):?>
        <div class="row"><a href="./admin.php?func=deactiveclient&id=<?php echo $client['id']?>" class="btn-xs btn-primary">DESACTIVAR</a> <?php echo $client['name'];?></div>
            <?php
           endforeach;
        }else{
            echo "No hay clientes iniciados";
        }
        ?>
    </div>
</div>
 
 <div class="panel panel-default">
    <div class="panel-heading">COLAS DE PREPARACIÓN  <a href="#" id="launchPreparerBtn"class="btn-xs btn-primary">LANZAR PREPARADORES</a></div>
    <div class="panel-body">
        <?php
        if (count($preparersWorking)>0){
            foreach ($preparersWorking as $printer):?>
        <div class="row"><a href="./admin.php?func=unblockPreparer&printercode=<?php echo $printer->code?>" class="btn-xs btn-primary">DESACTIVAR</a> <?php echo $printer->name;?></div>
            <?php
           endforeach;
        }else{
            echo "No hay colas de preparación iniciadas";
        }
        ?>
    </div>
</div>

 <div class="panel panel-default">
    <div class="panel-heading">TRABAJOS EN LA COLA DE IMPRESIÓN
            <?php if(count($printQueue)>0):?>
       <a href="./admin.php?func=emptyPrintQueue" onclick="return confirm('¿Seguro que quieres vaciar la cola de impresión?');" class="btn-xs btn-primary">VACIAR COLA DE IMPRESIÓN</a>
        <?php endif;?>
    </div>
    <div class="panel-body">
        <?php
        if (count($printQueue)>0){
            foreach ($printQueue as $pqjob):?>
        <div class="row"><a href="./admin.php?func=deletePrintQueueJob&jobid=<?php echo $pqjob->id?>" class="btn-xs btn-primary">ELIMINAR</a> <?php echo $pqjob->id;?></div>
            <?php
           endforeach;
        }else{
            echo "No hay trabajos en la cola de impresión";
        }
        ?>
    </div>
</div>

 <div class="panel panel-default">
    <div class="panel-heading">TRABAJOS EN LA BASE DE DATOS
        <?php if(count($jobs)>0):?>
            <a onclick="return confirm('¿Seguro que quieres eliminar todos los trabajos y sus archivos');" href="./admin.php?func=deleteAllJobs" class="btn-xs btn-primary">ELIMINAR TODOS</a>
        <?php endif;
            $files="";
            if (is_array($jobFolderContent)){
                foreach ($jobFolderContent as $f){
                    if ($f!="."&&$f!=".."){
                        $files.=$f."<br>";
                    }
                }
            }
        ?>
            
       <a  onclick="return confirm('¿Seguro que quieres vaciar la carpeta de trabajos? \n\r Si hay trabajos en la base de datos puede generar errores');" data-delay="1000" data-placement="right" data-toggle="tooltip" title="" data-original-title="ARCHIVOS y CARPETAS:<BR><?php echo $files;?>" href="./admin.php?func=emptyJobsFolder" class="btn-xs btn-danger">VACIAR CARPETA DE TRABAJOS</a>

    </div>
    <div class="panel-body">
       
        <?php
       if(count($jobs)>0){
            foreach ($jobs as $job):?>
                <hr>
                <div class="row">
                    <div class="col-xs-3"><strong> TRABAJO: <?php echo $job->id;?></strong></div>
                    <div class="col-xs-1">STATUS: <?php echo $job->status;?></div>
                    <?php if ($job->status>0):?>
                    <div class="col-xs-1"><a href="./admin.php?func=changeJobStatus&jobid=<?php echo $job->id?>&status=1" class="btn-xs btn-primary">STATUS A 1</a></div>
                                        <?php endif;?>
                    <?php
                    $files="";
                    foreach ($job->files as $f){
                        $files.=$f."-<a href='./admin.php?func=downloadfile&jobid=".$job->id."&file=".$f."' target='_blank'>Descargar</a><br>";
                    }
                    

                    if (!$job->status>0):?>
                    <div class="col-xs-1"></div>
                    <?php endif;?>
                    <div class="col-xs-2"><a  onclick="return confirm('¿Seguro que quieres eliminar los archivos temporales?');" data-delay="1000" data-placement="right" data-toggle="tooltip" title="" data-original-title="TODOS LOS ARCHIVOS:<BR><?php echo $files;?>" href="./admin.php?func=deleteJobTempFiles&jobid=<?php echo $job->id?>" class="btn-xs btn-primary withtooltip">BORRAR TEMPORALES</a></div>
                    <div class="col-xs-1"><a onclick="return confirm('¿Seguro que quieres borrar el trabajo y todos sus archivos?');" href="./admin.php?func=deleteJob&jobid=<?php echo $job->id?>" class="btn-xs btn-primary">ELIMINAR</a></div> 
                </div>
                <?php foreach ($job->docs as $doc):?>
                <div class="row">
                    <div class="col-xs-3"><strong> Documento:</strong> <?php echo $doc->tempName;?> - <?php echo $doc->name;?></div>
                    <div class="col-xs-1">STATUS: <?php echo $doc->status;?></div>
                </div>
                
            <?php
                endforeach;
           endforeach;
        }else{
            echo "No hay trabajos en la base de datos";
        }
        ?>
    </div>
</div>


 <div class="panel panel-default">
    <div class="panel-heading">ARCHIVO DE CONFIGURACION</div>
    <div class="panel-body">
        <div class="row"><div class="col-xs-2">Debug Analisis:</div><div class="col-xs-2"><?php  echo (jboConf::$debugAnalysis)?'<span class="label label-success">SI</span>':'<span class="label label-danger">NO</span>';?></div></div>
        <div class="row"><div class="col-xs-2">Debug Borrado Tras Impresión:</div><div class="col-xs-2"><?php  echo (jboConf::$debugDeleteAfterPrint)?'<span class="label label-success">SI</span>':'<span class="label label-danger">NO</span>';?></div></div>
        <div class="row"><div class="col-xs-2">Debug Estatus Tras Impresión:</div><div class="col-xs-2"><?php  echo (jboConf::$debugPostPqStatus)?'<span class="label label-success">SI</span>':'<span class="label label-danger">NO</span>';?></div></div>
        <div class="row"><div class="col-xs-2">Debug Preparadores:</div><div class="col-xs-2"><?php  echo (jboConf::$debugPrepare)?'<span class="label label-success">SI</span>':'<span class="label label-danger">NO</span>';?></div></div>
        <div class="row"><div class="col-xs-2">Debug Previas:</div><div class="col-xs-2"><?php  echo (jboConf::$debugPreview)?'<span class="label label-success">SI</span>':'<span class="label label-danger">NO</span>';?></div></div>
        <div class="row"><div class="col-xs-2">Debug Cola de Impresión:</div><div class="col-xs-2"><?php  echo (jboConf::$debugPrintQueue)?'<span class="label label-success">SI</span>':'<span class="label label-danger">NO</span>';?></div></div>
        <div class="row"><div class="col-xs-2">Debug Transformación:</div><div class="col-xs-2"><?php  echo (jboConf::$debugTransform)?'<span class="label label-success">SI</span>':'<span class="label label-danger">NO</span>';?></div></div>
        <div class="row"><div class="col-xs-2">Métod de embebido de fuentes:</div><div class="col-xs-2"><?php  echo (jboConf::$fontEmbedMethod);?></div></div>
        <div class="row"><div class="col-xs-2">Analisis de fuentes:</div><div class="col-xs-2"><?php  echo (jboConf::$fontLogActive)?'<span class="label label-success">SI</span>':'<span class="label label-danger">NO</span>';?></div></div>
        <div class="row"><div class="col-xs-2">Márgen General:</div><div class="col-xs-2"><?php  echo (jboConf::$margin);?> pt - <?php echo jboTools::pt2cm(jboConf::$margin);?> cm</div></div>
        <div class="row"><div class="col-xs-2">Márgen de error Para detectar Formato:</div><div class="col-xs-2"><?php  echo (jboConf::$analyzeFormatErrorMargin);?> pt - <?php echo jboTools::pt2cm(jboConf::$analyzeFormatErrorMargin);?> cm</div></div>
        <div><h4>EDITOR</h4></div> 
        <div class="row hidden" id="savecancelconfig" >
            <div class="col-xs-1">
                <a id="cancelconfigeditbtn" class="btn btn-xs btn-danger">CANCELAR</a>
            </div>
            <div class="col-xs-1">
                <a  id="saveconfigeditbtn" class="btn btn-xs btn-success">GUARDAR</a>
            </div>
        </div>        

        <div class="row"><textarea id="configFileEditor" rows="30" cols="120"><?php echo $configFile;?></textarea></div>
            
            
    </div>
</div>


</div>

<script>
    
    //fixtooltip
    
    var originalLeave = $.fn.tooltip.Constructor.prototype.leave;
$.fn.tooltip.Constructor.prototype.leave = function(obj){
  var self = obj instanceof this.constructor ?
    obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)
  var container, timeout;

  originalLeave.call(this, obj);

  if(obj.currentTarget) {
    container = $(obj.currentTarget).siblings('.tooltip')
    timeout = self.timeout;
    container.one('mouseenter', function(){
      //We entered the actual popover – call off the dogs
      clearTimeout(timeout);
      //Let's monitor popover content instead
      container.one('mouseleave', function(){
        $.fn.tooltip.Constructor.prototype.leave.call(self, self);
      });
    })
  }
};
    
    
    //lanzador de preparers
    
    $("#launchPreparerBtn").click(function(){
        $.get("./admin.php?func=launchPreparers", function(data, status){ });
  });
    
    
//tooltips
$('body').tooltip(
                    {
                     selector : '[data-toggle]',
                    html:true,
                    trigger: 'click hover', 
                    placement: 'aunto right', 
                    delay: {show: 0, hide: 400}}            
                        );

var reload=true;
$('[data-toggle=tooltip]').on('show.bs.tooltip', function () {
 reload=false;
})
$('[data-toggle=tooltip]').on('hide.bs.tooltip', function () {
 reload=true;
})


//editor eventos
$("#configFileEditor").focus(function(){
    reload=false;
    $("#savecancelconfig").removeClass("hidden");
});

$("#cancelconfigeditbtn").on("click",function(){
    window.location.reload();
});

$("#saveconfigeditbtn").on("click",function(){
    $.post( "admin.php?func=savecfgfile", { filecontent: $("#configFileEditor").val() } ).done(function(data){window.location.href="admin.php"});
});


//recarga
setInterval(function() { 
    if (reload){
        window.location.reload();
    }
},5000);

</script>