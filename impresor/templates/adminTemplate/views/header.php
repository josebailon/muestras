<?php defined("NODIRECT") or die; ?>

<nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Menú</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
              <span class="navbar-brand" >Administrador de Impresor </span>
          </div>
          <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
            <ul class="nav navbar-nav">
              <li class="<?php echo ($menuactive=="info"?"active":"");?>"><a href="admin.php">Información</a></li>
            <li class="<?php echo ($menuactive=="clients"?"active":"");?>"><a href="admin.php?sec=clients">Clientes</a></li>
            <li class="<?php echo ($menuactive=="defaults"?"active":"");?>"><a href="admin.php?sec=defaults">Valores por Defecto</a></li>
            <li class="<?php echo ($menuactive=="printers"?"active":"");?>"><a href="admin.php?sec=printers">Impresoras y Papel</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="admin.php?sec=user&func=logout"><i class="glyphicon glyphicon-off"></i> Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
</nav>


