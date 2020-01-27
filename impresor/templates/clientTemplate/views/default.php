<?php
defined("NODIRECT") or die;?>
 <div id="homeBody">
 
</div>

<div id="clientName"><?php echo $client['name'];?></div> 



  <div class="modal fade" id="whaitModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Se están realizando tareas de mantenimiento. Espere</h4>
        </div>
      </div>
      
    </div>
  </div>


<script src="<?php echo $this->viewPath . "/" . $this->view . "Controller.js"; ?>"></script>
<script>
    
    window.parent.document.title="<?php echo$this->pageTitle ;?>";
var controller=new jboClientCore(<?php echo $client['id'];?>);
    document.addEventListener("DOMContentLoaded", function(event) { 
        window.ondragover = function(e) { e.preventDefault(); return false };
        window.ondrop = function(e) { e.preventDefault(); return false; };  
    });
</script>