<?php defined("NODIRECT") or die; ?>
<div id="head">
    <div class="pull-left">
        <div id="helpicon">
            <span class="glyphicon glyphicon-question-sign"></span>
        </div>
        <div class="btn btn-lg btn-default pull-left" id="openConfigurationButton">
            <span class="glyphicon glyphicon-cog"></span>CONFIGURACIÓN
        </div>
        
    </div>
    <div class="pull-right">
        <div class="btn btn-lg btn-primary pull-right" id="serverShutdownButton">APAGAR</div>
        <div class="btn btn-lg btn-danger pull-right" id="serverRestartButton">REINICIAR</div>
    </div>
    <div id="printerStatus">
         

    </div>

</div>
<div id="contentheader" class="container-full">
    <div class="row">
        <div class="col-xs-1 text-center">Nº Pedido</div>
        <div class="col-xs-2 text-center">Nombre</div>
        <div class="col-xs-1 text-center">Hora</div>
        <div class="col-xs-1 text-center">Nº pág. B&N</div>
        <div class="col-xs-2 text-center">Nº pág. Color</div>
        <div class="col-xs-2 text-right" >Precio</div>
        <div class="col-xs-1 text-center">Estado</div>
    </div>
</div>
<div id="content" class="container-full">

</div>



<div id="summaryModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Pedido:<span id="summaryidjob"></span> - <span id="summaryPrintNumber" class="summarynumberdetail"></span> <span class="summarynumberdetail"> impresiones con </span><span class="summarynumberdetail" id="summaryDocNumer"></span><span class="summarynumberdetail"> archivos</span><button id="summaryModalCloseButton" type="button" class="pull-right btn btn-danger btn-lg">Cerrar</button>
                </h4>
                <h4 id="summaryJobName"></h4>
            </div>
            <div id="summaryModalBody" class="modal-body">
                <div id="summaryModalTableHead" class="container-fluid">
                    <div class='row   text-center'>
                        <div class="col-xs-3"></div>
                        <div class="col-jbo-0_5">Tamaño<br>Nº Copias</div>
                        <div class="col-jbo-0_5">Color<br/>B&N</div>
                        <div class="col-xs-1">Doble Cara</div>
                        <div class="col-xs-2">Rango de Páginas</div>
                        <div class="col-xs-1">Acabado</div>
                        <div class="col-xs-1">Mosaico</div>
                        <div class="col-xs-1">Total Páginas</div>
                        <div class="col-xs-1">Estado</div>
                    </div>
                </div>
                <div id="summaryModalTable" class="container-fluid"></div>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-3">
                        </div>
                        <div class="col-xs-6 text-center">
                            <div id="summaryModalPrice"></div><span>€</span>
                        </div>
                        <div class="col-xs-3">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div id="previewModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="previewTitle">
                    Vista Previa de <span id="previewdocname"></span> 
                    <button id="closePreviewButton" type="button" class="btn btn-danger btn-lg pull-right">Cerrar</button>
                </h4>
            </div>
            <div id="previewModalBody" class="modal-body">
                <iframe id="previewer"></iframe>

            </div>
        </div>
    </div>
</div>




<div id="configurationModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >
                    Configuración
                </h4>
            </div>
            <div id="configurationModalBody" class="modal-body">
                  <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#configurationtab_format">Configuración de Papel</a></li>
                    <li><a data-toggle="tab" href="#configurationtab_binding">Configuración de Acabados</a></li>

                </ul>
                
                <div class="tab-content configurationtabfull">
                    <!-- inici paper tab-->
                    <div id="configurationtab_format" class="tab-pane active fillparent">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4> PAPEL
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body"> 
                                <div class="container fillparent">
                                    <div class="row fillparent">
                                        <div class="fillparent col-menu">
                                                    <div id="formatsContainer" class="fillparent menucolumn">
                                                    </div>
                                        </div>
                                        <div class="fillparent col-values">
                                            <div id="formatsPriceContainer" class="fillparent">
                                                
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--fin tab paper-->
                    <div id="configurationtab_binding" class="tab-pane fade fillparent">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4> ACABADOS
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body"> 
                                <div class="container fillparent">
                                    <div class="row fillparent">
                                        <div class="fillparent col-menu menucolumn">
                                            <div class="row">
                                                <div class="col-xs-12 buffer-top-5">
                                                    <div id="addNewBindingButton" class="btn btn-success pull-left"><span class="glyphicon glyphicon-plus"></span>Añadir Acabado</div>
                                                </div>
                                            </div>
                                            <div id="bindingsContainer" class=""></div>
                                            
              
                                        </div>
                                        <div class="fillparent col-values">
                                            <div id="bindingsPriceContainer" class="fillparent">
                                                
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--fin tab binding-->
                </div><!--finmodal configuracion tab content-->
            </div><!--finmodal configuracion body-->
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-3">
                            <button id="configurationModalAcceptButton" type="button" class="btn btn-success btn-lg  pull-left">Guardar</button>
                        </div>
                        <div class="col-xs-6 text-center">

                        </div>
                        <div class="col-xs-3">
                            <button id="configurationModalCancelButton" type="button" class="btn btn-danger btn-lg pull-right ">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--finmodal configuracion content-->
    </div><!--finmodal foreground configuracion-->
</div><!--finmodal configuracion-->      

<div id="helpModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="previewTitle">
                    Ayuda
                </h4>
            </div>
            <div id="helpBody" class="modal-body">
                <iframe id="helpIframe"></iframe>
            </div>
            <div class="modal-footer">
                <div class="container text-center">
                    <button id="closeHelpModalButton"  type="button" class="btn btn-danger btn-lg">Cerrar</button>
                </div>
            </div>            
        </div>
    </div>
</div>



<!--plantillas -->
<div id="templates" style="visibility: hidden">


    <!--formatrowtemplate init-->
    <div id="templateFormatRow" class="hidden">
        <div class="extrapadding-5 menuSection no-margin-right" id="formatrowjbocode" formatid="jbocode">
            <div>
                <div class="" formatid="jbocode">
                    <div class="">
                        <h3 class="menuSectionHeader">jboname</h3>
                    </div>
                </div>
                <div class="">
                    <div class="col-xs-12" id="subformatContainerjbocode">
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--subformatrowtemplate init-->
    <div id="templateSubformatRow" class="hidden">
        <div class="row extrapadding-5 menurow no-margin-right" jbocategorias id="subformatrowjbocode" formatid="jbocode">
            <div class="col-xs-10">
                jboname
            </div>
            <div class="col-xs-1 pointer">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </div>
            
            
        </div>
    </div>
    <!--bindingrowtemplate init-->
    <div id="templateBindingRow" class="hidden">
        <div class="row extrapadding-5 menurow no-margin-right" id="bindingrowjbocode" bindingid="jbocode">
            <div class="col-xs-8">
                jboname
            </div>
            <div class="col-xs-2">
                <div bindingid="jbocode" id="deletebindingjbocode" class="btn btn-xs btn-danger deletebutton"><span class="glyphicon glyphicon-remove"></span></div>
            </div>
            <div class="col-xs-1 pointer">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </div>
            
            
        </div>
    </div>
    <!--template pricespaneltemplate init-->
    <div id="templatePricePanel" >
        <div id="pricepaneljbopricepanelid" class="pricepanel panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12">
                        <h4>PRECIOS PARA jbopricepanelname
                            <div id="addPricejbopricepanelid"  jbocategorias  panelid="jbopricepanelid" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span>Añadir Rango de Precio</div>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="panel-body"> 
                <div class="row">
                    <div class="col-xs-3 text-center">PÁGINAS MÍNIMAS</div><div class="col-xs-3 text-center">PÁGINAS MÁXIMAS</div><div class="col-xs-3 text-center">PRECIO</div>
                </div>
                <div id="pricesContainerjbopricepanelid" class="pricescontainer">
                </div>
            </div>
        </div>
    </div>
    <!--pricerowtemplate init-->
    <div id="templatePriceRow">
        <div class="row" id="pricerowjbogroupcodejboindex">
            <div class="col-xs-1 text-right">Desde:</div>
            <div class="col-xs-2" id="mincelljbogroupcodejboindex">
                <input id="minjbogroupcodejboindex" groupcode="jbogroupcode" ismin="1" jbocategorias catlenght="jbocatlength" index="jboindex" class="pricespinner jbospinnerclass1" type="text" value="jbomin">
                <span>jbolabelcontent1</span>
            </div>
            <div class="col-xs-1 text-right">Hasta:</div>
            <div class="col-xs-2" id="maxcelljbogroupcodejboindex">
                <div id="spinnermaxcontainerjbogroupcodejboindex" class="jbospinnerclass2">
                    <input id="maxjbogroupcodejboindex" groupcode="jbogroupcode" ismin="0" jbocategorias catlenght="jbocatlength" index="jboindex" class="pricespinner" type="text" value="jbomax">
                </div>
                <span>jbolabelcontent2</span>
            </div>
            <div class="col-xs-1 text-right">€/Página:</div>
            <div class="col-xs-2">
                <input id="pricejbogroupcodejboindex" class="form-control" jbocategorias groupcode="jbogroupcode" ismin="0" index="jboindex" type="text" value="jboprice">
            </div>
            <div class="col-xs-1">
                <div id="deletejbogroupcodejboindex" jbocategorias catlenght="jbocatlength" class="btn btn-danger btn-lg jbobuttonclass">
                    <span class="glyphicon glyphicon-remove" ></span>
                </div>
            </div>
        </div>
    </div>

    
        <!-- template summaryrow-->
       <div id="templatesummaryrow">
           <div class='row summaryrow text-center' id='jbotempName'>
 
               <div class="col-xs-3 text-left minicelda semicell">
                                 <div class="ordercell">jboorder</div>
                                 <div class="iconcell" class="col-sm-1">
                       <div class="icon" previewIndex="jboindex" jboiconStyle ></div>

                   </div>
                <div class="docname" data-toggle="tooltip" data-placement="right" title="jbocleanname">jboname</div>
               </div>
               <div class="col-jbo-0_5 minicelda">jbonCopies</div>
               <div class="col-jbo-0_5 minicelda hiddenfornotfirstingroup"><span>jbocolorText</span></div>
               <div class="col-xs-1 minicelda hiddenfornotfirstingroup"><span>jbodoubleSidedText</span></div>
               <div class="col-xs-2 minicelda hiddenfornotfirstingroup"><span>jbopageRange</span></div>
               <div class="col-xs-1 minicelda hiddenfornotfirstingroup"><span>jbobindingName</span></div>
               <div class="col-xs-1 semicell hiddenfornotfirstingroup"><span>jbomosaicText</span></div>
               <div class="col-xs-1 minicelda hiddenfornotfirstingroup"><span>jboprintingPages</span></div>
               <div class="col-xs-1 hiddenfornotfirstingroup" id="statusjbotempName" >
                   <span class="statusicon statusjbostatus"></span>
               </div>
           </div>
       </div>
        
        
                   <!-- TEMPLATE group -->
    <div id="templateGroup">
        <div class="group" id="groupjbogroupname">
            <div class="grouphead">
                GRUPO
            </div>
            <div class="groupbody">
            </div>
        </div>
    </div><!-- fin tempaltegroup-->  
         <!--documentrowtemplate init-->
    <div id="templateJobRow">
        <div class="row documentrow closed" id="jboid">
            <div class="col-xs-1 text-center" id="idjboid">jboid</div>
            <div class="col-xs-2 text-center" id="namejboid">jboname</div>
            <div class="col-xs-1 text-center" id="datejboid">jbodate</div>
            <div class="col-xs-1 text-center" data-delay="1000" data-placement="bottom" data-html="true" data-toggle="tooltip" title="jbodetailpagesbn" id="bnjboid">
                jbopagesbn
            </div>
            <div class="col-xs-2 text-center" data-delay="1000" data-placement="bottom" data-html="true" data-toggle="tooltip" title="jbodetailpagescolor" id="colorjboid">
                jbopagescolor
            </div>
            <div class="col-xs-2 text-right" id="totalPricejboid">jbototalPrice<span> €</span></div>
            <div class="col-xs-1 text-center" idJob="jboid" id="statusjboid"><span class="statusicon statusjbostatus"></span></div>
            <div class="col-xs-1 text-center"><span class="btn btn-danger glyphicon glyphicon-remove jbocancelButtonClass" idJob="jboid" id="canceljboid"></span></div>

        </div>
    </div>
    <!-- fin pricerowtemplate-->
</div><!-- fin templates-->
<script src="<?php echo $this->viewPath . "/" . $this->view . "Core.js"; ?>"></script>
<script src="<?php echo $this->viewPath . "/" . $this->view . "UIController.js"; ?>"></script>
<script src="<?php echo $this->viewPath . "/" . "managerTools.js"; ?>"></script>
<script>
    var controller = new managerAutoCore();
</script>
<script>
    document.addEventListener("DOMContentLoaded", function(event) { 
        window.ondragover = function(e) { e.preventDefault(); return false };
        window.ondrop = function(e) { e.preventDefault(); return false; };  
    });
</script>