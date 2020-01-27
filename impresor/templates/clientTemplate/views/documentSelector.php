<?php defined("NODIRECT") or die; ?>
<div id="appHeader" >
    <div id="backBtn" class="headerbutton pull-left">
        <span class="glyphicon glyphicon glyphicon-repeat"></span> 
        LIMPIAR PEDIDO
    </div>
    <div id="helpicon" class="headerbutton pull-right">
        AYUDA <span class="glyphicon glyphicon-question-sign"></span>
    </div>
    
</div>
<div id="leftToolBar">
    <div id="leftToolBarTop" class="toolbarsection">
        <div id="impresorIcon" class="sidebtn">
            <div class="inner">
                <div class="btnicon">
                </div>
            </div>
        </div>
        <div id="addButton" class="sidebtn btn-success">
            <div class="inner">
                <div class="btnicon"><span class="glyphicon glyphicon-plus"></span></div>
                <div class="btntitle">AÑADIR</div>
            </div>
        </div>
        <div id="printButton" class="sidebtn ">
            <div class="inner">
                <div class="btnicon">
                </div>
                <div class="btntitle">IMPRIMIR</div>
            </div>
        </div>
    </div>
    <div id="leftToolBarMiddle" class="toolbarsection">
        <div class="toolbarheader">EDITAR</div>
        <div id="groupDocEditButton" class="sidebtn ">
            <div class="inner">
                <div class="btnicon">
                    <div class="btnicon"><span class="glyphicon glyphicon-th-list"></span></div>
                </div>
                <div class="btntitle">DOC</div>
            </div>
        </div>
        <div id="groupImgEditButton" class="sidebtn ">
            <div class="inner">
                <div class="btnicon">
                    <div class="btnicon"><span class="glyphicon glyphicon-picture"></span></div>
                </div>
                <div class="btntitle">FOTO</div> 
            </div>
        </div>
    </div>
    <div id="leftToolBarBottom" class="toolbarsection">
        <?php if ($_SESSION['client']['whatsapp'] == 1): ?>
            <div id="openWhatsappModalButton" class="sidebtn btn-success">
                <div class="inner">
                    <div class="btnicon">
                    </div>
                </div>
            </div>
        <?php endif;?>
        <?php if ($_SESSION['client']['telegram']==1):?>
            <div id="openTelegramModalButton" class="sidebtn btn-primary">
                <div class="inner">
                    <div class="btnicon">
                    </div>
                </div>
            </div>
        <?php endif;?>
    </div>
</div>

<div id="docMainPanelHeader" class="container container-full">
    <div class="row rowmini documentrow">
        <div class="col-xs-3   text-center">
        </div>
        <div class="col-jbo-0_5 text-center">
            <span>PAG.</span>
        </div>
        <div class="col-xs-1  text-center">
            <span>COPIAS</span>
        </div>
        <div class="col-xs-1  text-center">
            <span>TAMAÑO PAPEL</span>
        </div>
        <div class="col-xs-1 text-center">
            <span>COLOR</span>
        </div>
        <div class="col-xs-1  text-center">
            <span>DUPLEX</span>
        </div>
        <div class="col-xs-1 col-jbo-1_5  text-center">
            <span>MOSAICO</span>
        </div>
        <div class="col-xs-1 col-jbo-1_5  text-center">
            <span>ACABADO</span>
        </div>
        <div class="col-jbo-0_5 text-center">
            <span>TOTAL</span>
        </div>
        <div class="col-jbo-0_5 text-center">
            <span>OPCIONES</span>
        </div>
        <div class="col-jbo-0_5  text-center">
            <span>BORRAR</span>
        </div>
    </div>

</div>

<div id="docMainPanel" class="container container-full"></div>
<div id="docFooter">
    <div id="miniSummary">
        <div id="pricedisplay">
            <div class="pricelabel">Precio:</div>
            <div id="miniSummaryTotalPrice">
                <span class="pricebig">0</span><span class="pricepoint">.</span><span class="pricesmall">00</span>
            </div><span>€</span>
        </div>
        <div id="pagesdisplay">

        </div>
 
    </div>
    <div id="footerLegal">
        <p class="marquee">
            <span>
                Por favor no imprima documentos sin permiso del propietario de los derechos de autor.
            </span>
        <p/>  
    </div>
</div>

<!--modal de sumario de trabajo -->
<div id="summaryModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Pedido: <span id="summaryidjob"></span> - <span id="summaryPrintNumber" class="summarynumberdetail"></span> <span class="summarynumberdetail"> impresiones con </span><span class="summarynumberdetail" id="summaryDocNumer"></span><span class="summarynumberdetail"> archivos</span>
                </h4>
            </div>
            <div id="summaryModalBody" class="modal-body">
                <div id="summaryModalTableHead" class="container-fluid">
                    <div class='row   text-center'>
                        <div class="col-xs-3"></div>
                        <div class="col-xs-1">Tamaño Papel</div>
                        <div class="col-xs-1">Nº Copias</div>
                        <div class="col-xs-1">Color<br/>B&N</div>
                        <div class="col-xs-2">Doble Cara</div>
                        <div class="col-xs-1">Rango de Páginas</div>
                        <div class="col-xs-1">Acabado</div>
                        <div class="col-xs-1">Mosaico</div>
                        <div class="col-xs-1">Total Páginas</div>
                    </div>
                </div>
                <div id="summaryModalTable" class="container-fluid"></div>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-4">
                            <button id="summaryModalCancelButton" type="button" class="btn btn-danger pull-left btn-lg" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-xs-4 text-center">
                            <div id="summaryModalPrice"></div><span>€</span>
                        </div>
                        <div class="col-xs-4">
                            <label for="jobNameInput">Nombre:</label><input type="text" id="jobNameInput" name="jobNameInput" placeholder="Asigne un nombre">
                            <button id="summaryModalAcceptButton" type="button" class="btn btn-success btn-lg">Imprimir</button>
                            <div class="checkbox redtooltip">
                                <input id="summaryModalPrintCondCheckbox" name="summaryModalPrintCondCheckbox" class="bigcheckbox" type="checkbox" value="">
                                <label id="summaryModalPrintCondCheckboxLabel" for="summaryModalPrintCondCheckbox" title="PARA IMPRIMIR DEBE ACEPTAR LAS CONDICIONES"><span>Acepto las condiciones</span></label>
                                <label style="padding-left: 5px;" id="summaryModalViewConditionsButton">(Ver Condiciones)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal de edicion de documentos en grupo -->

<div id="groupDocEditModal" class="modal modalhfull fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    EDICIÓN MÚLTIPLE DE DOCUMENTOS
                </h4>
            </div>
            <div id=groupDocEditBody" class="modal-body">
                <div class="midleft">
                    <div>
                        <input id="groupDocEditSelectAllButton" class="mediumcheckbox" type="checkbox"/>
                        <div id="groupDocEditDeleteButton" class="btn btn-danger pull-right">
                                <span class="glyphicon glyphicon-remove">  </span> Eliminar
                        </div>
                    </div>
                    <div  id="groupDocEditList" class="forceverticalscroll"></div>
                </div>
                <div class="midright">
                    <div id="groupDocEditOptions"></div>
                    <div class="text-center top-buffer">
                            <div id="groupDocEditApplyChangesButton" class="btn btn-success" >
                                Aplicar Cambios
                            </div>
                    </div>                    
                </div>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-4">
                            <button id="groupDocEditCancelButton" type="button" class="btn btn-danger pull-left btn-lg" data-dismiss="modal">Deshacer todos los cambios</button>
                        </div>
                        <div class="col-xs-4 text-center">
                
                        </div>
                        <div class="col-xs-4">
                             <button id="groupDocEditAcceptButton" type="button" class="btn btn-success btn-lg">Aplicar Cambios y Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal de edicion de imagenes en grupo -->

<div id="groupImgEditModal" class="modal modalhfull fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    EDICIÓN MÚLTIPLE DE IMÁGENES
                </h4>
            </div>
            <div id=groupImgEditBody" class="modal-body">
                <div class="midleft">
                    <div class="groupEditListToolbar">
                        <input id="groupImgEditSelectAllButton" class="mediumcheckbox" type="checkbox"/>
                        <div id="groupImgEditDeleteButton" class="btn btn-danger pull-right">
                                <span class="glyphicon glyphicon-remove">  </span> Eliminar
                        </div>
                        <div id="groupImgEditUnmakeGroupButton" class="btn btn-warning pull-right">
                                <span class="glyphicon glyphicon-fullscreen">  </span> Desagrupar
                        </div>
                        <div id="groupImgEditMakeGroupButton" class="btn btn-primary pull-right">
                                <span class="glyphicon glyphicon-link">  </span> Agrupar
                        </div>
                    </div>
                    <div  id="groupImgEditList" class="forceverticalscroll"></div>
                </div>
                <div class="midright">
                    <div id="groupImgEditOptions"></div>
                    <div class="text-center top-buffer">
                            <div id="groupImgEditApplyChangesButton" class="btn btn-success" >
                                Aplicar Cambios
                            </div>
                    </div>                    
                </div>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-4">
                            <button id="groupImgEditCancelButton" type="button" class="btn btn-danger pull-left btn-lg" data-dismiss="modal">Deshacer todos los cambios</button>
                        </div>
                        <div class="col-xs-4 text-center">
                
                        </div>
                        <div class="col-xs-4">
                             <button id="groupImgEditAcceptButton" type="button" class="btn btn-success btn-lg">Aplicar Cambios y Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- modal de cola de seleccion multiple -->
<div id="selectedQueueModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    ¿Desea añadir estos documentos?:
                </h4>
            </div>
            <div id="selectedQueueModalBody" class="modal-body">

                <div id="selectedQueueModalTable" class="container-fluid"></div>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-3">                        
                        </div>
                        <div class="col-xs-3">
                            <button id="selectedQueueModalCancelButton" type="button" class="btn btn-danger pull-left btn-lg" data-dismiss="modal">Cancelar</button>
                        </div>                     
                        <div class="col-xs-3">
                            <button id="selectedQueueModalAcceptButton" type="button" class="btn btn-success btn-lg">Añadir</button>
                        </div>
                        <div class="col-xs-3">                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="selectModal" class="modal modalhfull fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Agregar Documentos 
                </h4>
            </div>
            <div id="selectModalBody" class="modal-body">
                <p>
                    <span id="selectModalCurrentAction">ACCEDIENDO A LOS DOCUMENTOS</span>
                </p>
                <p>
                    <span id="selectModalCurrentItem"></span>
                </p>
                <div id="selectProgress" class="progress">
                    <div id="selectModalPB" class="progress-bar  progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                        <span  class="sr-only">0%</span>
                    </div>
                </div>
                
                    <div id="selecWarningsPanel" class="panel panel-warning  hidden">
                       <div class="panel-heading">Avisos</div>
                       <div id="selectWarnings" class="panel-body container-fluid"></div>
                    </div>
                   <div id="selecErrorsPanel" class="panel panel-danger hidden">
                       <div class="panel-heading">Errores encontrados</div>
                       <div id="selectErrors" class="panel-body container-fluid"></div>
                   </div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-12  text-center">
                    <button id="selectFilesAcceptButton" type="button" class="btn btn-success btn-lg hidden" >Aceptar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="previewModal" class="modal modalhfull fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="previewTitle">
                    Vista Previa de <span id="previewdocname"></span> 
                    <button id="closePreviewButton"  type="button" style="display:none;" class="btn btn-danger btn-lg pull-right">Cerrar</button>
                </h4>
            </div>
            <div id="previewModalBody" class="modal-body">
                <iframe id="previewer"></iframe>
                        <div id="previewiniloading"><div><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span></div></div>
            </div>
        </div>
    </div>
</div>



<div id="pagerangeModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >
                    Rango de página de <span id="pagerangedocname"></span> 
                </h4>
            </div>
            <div id="pagerangeModalBody" class="modal-body">
                <input id="pagerangeDocCode" type="hidden">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="radio">
                            <label><input id="pagerangeModalRadioAll" type="radio" name="optradio" value="Todo">Todas las páginas (<span id="totalpagesPageRangelabel"></span>)</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="radio">
                            <label><input id="pagerangeModalRadioRange" type="radio" name="optradio" value="Range">Seleccionar Rango</label>  
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="radio">
                            <input id="pagerangeModalRange" type="text" name="optradio">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="radio">
                            <span id="pagerangeModalErrorMessageContainer" class="label label-warning"></span>
                        </div>
                    </div>
                </div>
                        
            </div>
            <div class="modal-footer">
                <button id="cancelPagerangeButton" type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                <button id="acceptPagerangeButton" type="button" class="btn btn-success">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="printJobModal" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"  class="text-center">
                <h4 id="printJobModalTitle"></h4>
            </div>
            <div class="modal-body" id="printJobModalBody">
                <div id="printJobModalSending" class="text-center"><div>Esperere un momento</div><div><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span></div></div>
                <div id="printJobModalSent">
                    <div class="text-center">Su pedido ha sido enviado.<br/>Número de pedido:</div>
                    <div id="printJobModalSentIdJob" class="text-center"></div>
                </div>
                <div id="printJobModalSentFail">
                    <div class="text-center">Ha ocurrido un error al enviar su pedido</div>
                </div>
            </div>
 
        </div>
    </div>
</div>
<div id="legalConditionsModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="previewTitle">
                    Condiciones
                </h4>
            </div>
            <div id="legalConditionsBody" class="modal-body">
                <iframe id="legalConditionsIframe"></iframe>
            </div>
            <div class="modal-footer">
                <div class="container text-center">
                    <button id="closeConditionsModalButton"  type="button" class="btn btn-danger btn-lg">Cerrar</button>
                </div>
            </div>            
        </div>
    </div>
</div>
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



<div id="iniloading"><div><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span></div></div>
<div id="dropZone"><div id="dropMessage">Añade documentos arrastrandolos a la ventana</div><div id="dropTrigger"></div></div>

<input   id="fileDialog" class="hidden" type="file" nwworkingdir="<?php echo addslashes(str_replace('\\', '/', $addFileDefaultPath)) ?>" multiple accept="<?php echo $allowedDocExtensions; ?>"/>

<!--templaates-->
<div id="templates" style="display: none">
    <!-- TEMPLATE GROUPDOCEDITROW -->
    <div id="templateGroupDocEditRow">
        <div class="groupDocEditRow" id="groupDocEditRowjbotempname">
            <div>
               <input id="groupdoceditselectjbotempname" class="mediumcheckbox" type="checkbox"/> 
            </div>
            <div>
                <img class="groupEditIcon" src="jboiconUrl"/>
            </div>
            <div>
                jboname
            </div>
        </div>
    </div>
    <!-- fin template groupdoceditrow -->
    
    
    <!--TEMPLATE groupdocedit opciones-->
    <div id="templateGroupDocEditOptions">
            <div class="row top-buffer-big">
                <div class="col-xs-6">Copias(0 para no modificar):</div>
                <div class="col-xs-6">
                    <input class=" autospin" id="groupDocEditnCopies"  type="text" value="0">
                </div>
            </div>
            <div class="row top-buffer-big">
                <div class="col-xs-6">Tamaño:</div>
                <div class="col-xs-6">
                    <select class="form-control" id="groupDocEditPrintFormat">
                        <option value="-3">No Modificar</option>
                        <option value="-1">Tamaño Original</option>
                        jboprintFormatoptions
                    </select>
                </div>
            </div>        
            <div class="row top-buffer-big">
                <div class="col-xs-6">B&N/Color:</div>
                <div class="col-xs-6">
                    <select class="form-control" id="groupDocEditColor">
                        <option value="-3">No Modificar</option>
                        <option value="1">Color</option>
                        <option value="0">B&N</option>
                    </select>
                </div>
            </div>
        
            <div class="row top-buffer-big">
                <div class="col-xs-6">Doble Cara:</div>
                <div class="col-xs-6">
                    <select class="form-control" id="groupDocEditdoubleSided">
                        <option value="-3">No Modificar</option>
                        <option value="1">2 Caras</option>
                        <option value="0">1 Cara</option>
                    </select>
                </div>
            </div>
        
            <div class="row top-buffer-big">
                <div class="col-xs-6">Mosaico:</div>
                <div class="col-xs-6">
                <select class="form-control" id="groupDocEditmosaic">
                    <option value="-3">No Modificar</option>
                    <option value="1">Sin Mosaico</option>
                    <option value="2">2 por Pág.</option>
                    <option value="4">4 por Pág.</option>
                </select>
                </div>
            </div>

            <div class="row top-buffer-big">
                <div class="col-xs-6">Acabado:</div>
                <div class="col-xs-6">
                    <select class="form-control" id="groupDocEditbinding">
                        <option value="-3">No Modificar</option>
                        <option value="-1">Ninguno</option>
                        jbobindingsoptions
                    </select>
                </div>
            </div>

            <div class="row top-buffer-big">
                <div class="col-xs-6">Orientación de papel:</div>
                <div class="col-xs-6">
                    <select class="form-control" id="groupDocEditpaperOrientation">
                        <option value="-3">No Modificar</option>
                        <option value="0">Automático</option>
                        <option value="1">Vertical</option>
                        <option value="2">Apaisado</option>
                    </select>
                </div>
            </div>
            
            <div id="shortBoundRowjbocode" class="row top-buffer-big" >
                <div class="col-xs-6">Encuadernado en:<br> Borde Largo/ Borde Corto:</div>
                <div class="col-xs-6">
                     <select class="form-control" id="groupDocEditshortBound">
                        <option value="-3">No Modificar</option>
                        <option value="0">Borde Largo</option>
                        <option value="1">Borde Corto</option>
                    </select>
                </div>
            </div>

            <div class="row top-buffer-big">
                <div class="col-xs-6">Rotar páginas de diferentes tamaños:</div>
                <div class="col-xs-6">
                    <select class="form-control" id="groupDocEditforceRotation">
                        <option value="-3">No Modificar</option>
                        <option value="0">NO</option>
                        <option value="1">SI</option>
                    </select>
                </div>
            </div>
    </div>  <!--FIN template groupdocedit opcione-->

    
    
        <!-- TEMPLATE groupIMGeditrow -->
    <div id="templateGroupImgEditRow">
        <div class="groupImgEditRow" id="groupImgEditRowjbotempname">
            <div>
                <img class="groupEditIcon" src="jboiconUrl"/>
            </div>
            <div>
                jboname
            </div>
        </div>
    </div>
    <!-- fin template groupimgeditrow --
            <!-- TEMPLATE groupIMGeditgrouprow -->
    <div id="templateGroupImgEditGroupRow">
        <div class="group groupImgEdit" id="groupImgEditGroupjbogroupname">
            <div class="groupedtigroupselectcheckbox">
                <input id="groupimgeditgroupselectjbogroupname" class="mediumcheckbox" type="checkbox"/> 
            </div>
            <div class="grouphead">
                <span>GRUPO</span>
            </div>
            <div class="groupbody">
            </div>
        </div>
    </div><!-- fin tempaltegroup-->  
    
      <!--TEMPLATE groupdIMGedit opciones-->
    <div id="templateGroupImgEditOptions">
            <div class="row top-buffer-big">
                <div class="col-xs-6">Copias(0 para no modificar):</div>
                <div class="col-xs-6">
                    <input class=" autospin" id="groupImgEditnCopies"  type="text" value="0">
                </div>
            </div>
            <div class="row top-buffer-big">
                <div class="col-xs-6">Tamaño de papel:</div>
                <div class="col-xs-6">
                    <select class="form-control" id="groupImgEditPrintFormat">
                        <option value="-3">No Modificar</option>
                        jboprintFormatoptions
                    </select>
                </div>
            </div>          
            <div class="row top-buffer-big">
                <div class="col-xs-6">B&N/Color:</div>
                <div class="col-xs-6">
                    <select class="form-control" id="groupImgEditColor">
                        <option value="-3">No Modificar</option>
                        <option value="1">Color</option>
                        <option value="0">B&N</option>
                    </select>
                </div>
            </div>
        
            <div class="row top-buffer-big">
                <div class="col-xs-6">Doble Cara:</div>
                <div class="col-xs-6">
                    <select class="form-control" id="groupImgEditdoubleSided">
                        <option value="-3">No Modificar</option>
                        <option value="1">2 Caras</option>
                        <option value="0">1 Cara</option>
                    </select>
                </div>
            </div>
        
            <div class="row top-buffer-big">
                <div class="col-xs-6">Mosaico:</div>
                <div class="col-xs-6">
                <select class="form-control" id="groupImgEditmosaic">
                    <option value="-3">No Modificar</option>
                        jbomosaicoptions
                </select>
                </div>
            </div>

            <div class="row top-buffer-big">
                <div class="col-xs-6">Acabado:</div>
                <div class="col-xs-6">
                    <select class="form-control" id="groupImgEditbinding">
                        <option value="-3">No Modificar</option>
                        <option value="-1">Ninguno</option>
                        jbobindingsoptions
                    </select>
                </div>
            </div>

            <div class="row top-buffer-big hidden">
                <div class="col-xs-6">Orientación de papel:</div>
                <div class="col-xs-6">
                    <select class="form-control" id="groupImgEditpaperOrientation">
                        <option value="-3">No Modificar</option>
                        <option value="0">Automático</option>
                        <option value="1">Vertical</option>
                        <option value="2">Apaisado</option>
                    </select>
                </div>
            </div>
            
            <div id="shortBoundRowjbocode" class="row top-buffer-big" >
                <div class="col-xs-6">Encuadernado en:<br> Borde Largo/ Borde Corto:</div>
                <div class="col-xs-6">
                     <select class="form-control" id="groupImgEditshortBound">
                        <option value="-3">No Modificar</option>
                        <option value="0">Borde Largo</option>
                        <option value="1">Borde Corto</option>
                    </select>
                </div>
            </div>

            <div class="row top-buffer-big hidden">
                <div class="col-xs-6">Rotar páginas de diferentes tamaños:</div>
                <div class="col-xs-6">
                    <select class="form-control" id="groupImgEditforceRotation">
                        <option value="-3">No Modificar</option>
                        <option value="0">NO</option>
                        <option value="1">SI</option>
                    </select>
                </div>
            </div>
    </div>  <!--FIN template groupdImgedit opcione-->

    
     
    
    

    <!--TEMPLATE DOCUMENT ROW-->
    <div id="templateDocumentRow">
        <div id='rowjbocode' class='row documentrow rowmini jbotopdragableclass' filecode="jbocode">
            <div class="col-xs-3 minicelda">
                <div class="grabable">  <span class="glyphicon glyphicon-option-vertical"></span></div>
                <div class="ingroupgrabable">  <span class="glyphicon glyphicon-option-vertical"></span></div>
                <div class="iconcell" class="col-sm-1">
                    <div id="iconjbocode" class="icon" filecode="jbocode">
                        <span class="glyphicon glyphicon-warning-sign jbowarningiconhiddenclass warningpages" data-toggle="tooltip" title="Contiene diferentes tamaños y/o rotaciónes en las páginas"></span>
                        <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate waiticon"></span>
                    </div>
                </div>
                <span class="docname" data-delay="1000" data-placement="right" data-toggle="tooltip" title="jbodocscapedname">jbodocname</span>
            </div>
            <div class="col-jbo-0_5 minicelda hiddenfornotfirstingroup">
                <span>jbodocnpages</span>
            </div>
            <div class="col-xs-1 minicelda paddedcell">
                <input filecode="jbocode" class="form-control autospin" id="nCopiesjbocode"  type="text" value="jbodocncopies">
            </div>
            <div class="col-xs-1 minicelda paddedcell hiddenfornotfirstingroup">
                <select filecode="jbocode" class="form-control" id="printFormatjbocode">
                    jboprintFormatoptions
                </select>
            </div>
            <div class="col-xs-1 minicelda  hiddenfornotfirstingroup">
                <input filecode="jbocode" id="colorjbocode" class="autoswitch" type="checkbox" data-on="Color" data-off="B&N" data-toggle="toggle">
            </div>
            <div class="col-xs-1 minicelda  hiddenfornotfirstingroup">
                <input filecode="jbocode" id="doubleSidedjbocode" class="autoswitch" type="checkbox" data-on="2 Caras" data-off="1 Cara" data-toggle="toggle" jbodoublesideddefaultstate>
            </div>
            <div class="col-xs-1 col-jbo-1_5 paddedcell hiddenfornotfirstingroup">
                <select filecode="jbocode" class="form-control" id="mosaicjbocode">
                    jbomosaicoptions
                </select>
            </div>            
            <div class="col-xs-1 col-jbo-1_5 paddedcell hiddenfornotfirstingroup">
                <select filecode="jbocode" class="form-control" id="bindingjbocode">
                    <option value="-1">Ninguno</option>
                    jbobindingsoptions
                </select>
            </div>

            <div class="col-xs-1 col-jbo-0_5 minicelda hiddenfornotfirstingroup">
                <span filecode="jbocode" id="printingPagesjbocode">jbodocprintingpages</span>
            </div>
            <div class="col-xs-1 col-jbo-0_5  moreoptions text-center hiddenfornotfirstingroup" id="moreoptionsjbocode">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </div>
            <!-- selector
            <div class="col-xs-1 col-jbo-1_5  minicelda paddedcell text-center">
                <input id="selectjbocode" class="bigcheckbox" type="checkbox"/> 
                <label data-toggle="tooltip" title="Seleccionar documento para editar en grupo" data-delay='{"show":"2500"}' for="selectjbocode"></label>
            </div>
            -->
            <div class="col-xs-1 col-jbo-0_5 minicelda   text-center">
                <div filecode="jbocode" id="deletejbocode" class="btn btn-danger" data-toggle="tooltip" title="Borrar Documento" data-delay='{"show":"2500"}'>
                    <span class="glyphicon glyphicon-remove"> </span>
                </div>
            </div>

        <!--template modal mas opciones-->
        <div class="modal fade" id="moreOptionsModaljbocode" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Más opciones para: jbodocname</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row jbohiddenforimg">
                            <div class="col-xs-6">Rango de páginas:</div>
                            <div class="col-xs-6">
                                <input filecode="jbocode" class="form-control pagerangecontrol" id="pageRangejbocode" type="text" value="jbodocpagerange" readonly>
                            </div>
                        </div>
                        <div class="row top-buffer-big jbohiddenforimg">
                            <div class="col-xs-6">Orientación de papel:</div>
                            <div class="col-xs-6">
                                <select filecode="jbocode" class="form-control" id="paperOrientationjbocode">
                                    <option value="0">Automático</option>
                                    <option value="1">Vertical</option>
                                    <option value="2">Apaisado</option>
                                </select>
                            </div>
                        </div>
                        <div class="row top-buffer-big jbohiddenforimg">
                            <div class="col-xs-6">Rotar páginas de diferentes tamaños:</div>
                            <div class="col-xs-6">
                                <input  filecode="jbocode" id="forceRotationjbocode" class="autoswitch" type="checkbox" data-off='NO' data-on='SI' data-width="200" data-toggle="toggle" jboforcerotationdefaultstate>
                            </div>
                        </div>
                        <div id="shortBoundRowjbocode" class="row top-buffer-big hidden" >
                            <div class="col-xs-6">Encuadernado en:<br> Borde Largo/ Borde Corto:</div>
                            <div class="col-xs-6">
                                <input  filecode="jbocode" id="shortBoundjbocode" class="autoswitch" type="checkbox" data-off='<div class="longboundicon"></div><span class="boundlabel">Borde Largo</span>' data-on='<div class="shortboundicon"></div><span class="boundlabel">Borde Corto</span>' data-width="200" data-onstyle="default" data-offstyle="default" data-toggle="toggle">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="moreOptionsModalConfirmjbocode"  class="btn btn-success">Aceptar</button>
                    </div>
                </div>
            </div>
        </div><!--fin de modal de mas opciones-->
        </div><!--fin de row-->

    </div>  <!--FIN template de row-->
    
    <!-- template de opciones de mosaico para documentos-->
    <div id="templateDocMosaicOptions">
        <option value="1">Sin Mosaico</option>
        <option value="2">2 por Pag.</option>
        <option value="4">4 por Pág.</option>
    </div><!-- fin templatedocmosaicoptions--> 
    
    <!-- template de opciones de mosaico para imagenes-->
    <div id="templateImgMosaicOptions">
        <option value="jboimagemosaiccode">jboimagemosaiclabel</option>
    </div><!-- fin templatedocmosaicoptions--> 
    <!-- template de opciones de formato de papel-->
    <div id="templatePrintFormatOptions">
        <option value="jboprintFormatvalue" jboprintformatdefault>jboprintFormatname</option>
    </div><!-- fin templatePrintFormatOptions--> 
    
        <!-- TEMPLATE group -->
    <div id="templateGroup">
        <div class="group topdragable" id="groupjbogroupname">
            <div class="grouphead grabable">
                GRUPO  <span class="glyphicon glyphicon-option-horizontal"></span>
            </div>
            <div class="groupbody">
            </div>
        </div>
    </div><!-- fin tempaltegroup-->    
    
    <!--TEMPLATE miniSummaryCell ROW-->
    <div id="templateMiniSummaryCell">
        <div class="pull-left">
            <div class="miniSummaryCellRow">Páginas jboprintformatname</div>
            <div class="miniSummaryCellRow">Color: jboprintformatcolorpagevalue</div>
            <div class="miniSummaryCellRow">BN: jboprintformatbnpagevalue</div>
        </div>
    </div>
        <!-- fin miniSummaryCell-->  
    
    <!--TEMPLATE summary ROW-->
    <div id="templateDocumentSummaryRow">
        <div id="summaryRowjbotempname" class='row summaryrow text-center'>
            <div class="col-xs-3 text-left miniceldasummary">
                <div class="iconcell" class="col-sm-1">
                    <div class="icon" style="background-image: url('jboiconUrl');">
                    </div> 
                </div>
                <span class="docname">jboname</span>
            </div>
            <div class="col-xs-1 hiddenfornotfirstingroup"><span>jboprintFormat<span></div>
            <div class="col-xs-1 miniceldasummary">jbonCopies</div>
            <div class="col-xs-1 miniceldasummary hiddenfornotfirstingroup"><span>jbocolorText</span></div>
            <div class="col-xs-2 miniceldasummary hiddenfornotfirstingroup"><span>jbodoubleSidedText</span></div>
            <div class="col-xs-1 miniceldasummary hiddenfornotfirstingroup"><span>jbopageRange</span></div>
            <div class="col-xs-1 miniceldasummary hiddenfornotfirstingroup"><span>jbobindingName</span></div>
            <div class="col-xs-1 miniceldasummary hiddenfornotfirstingroup"><span>jbomosaicText</span></div>
            <div class="col-xs-1 miniceldasummary hiddenfornotfirstingroup"><span>jboprintingPages</span></div>
        </div>
    </div>  <!--FIN TEMPLATE summary ROW-->
<!--TEMPLATE templateSelectedQueueItemRow -->
    <div id="templateSelectedQueueItemRow">
        <div id="rowjboitemcode" class='row summaryrow'>
            <div class="col-xs-1 text-left miniceldavariable">
                <div id="removebuttonjboitemcode" code="jboitemcode" class="btn btn-danger">
                    <span class="glyphicon glyphicon-remove"> </span>
                </div>
            </div>
            <div class="col-xs-11 miniceldavariable">jboPath</div>
        </div>
    </div>  <!--FIN TEMPLATE templateSelectedQueueItemRow-->
</div>
<script src="<?php echo $this->viewPath . "/" . $this->view . ".js"; ?>"></script>
<script src="<?php echo $this->viewPath . "/" . $this->view . "Core.js"; ?>"></script>
<script src="<?php echo $this->viewPath . "/" . $this->view . "UIController.js"; ?>"></script>
<script src="<?php echo $this->viewPath . "/" . $this->view . "Tools.js"; ?>"></script>
<script>
    var controller = new jboClientCore(<?php echo $_SESSION['client']['id']; ?>);
</script>