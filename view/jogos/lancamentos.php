<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    include_once("../header.php");
    
    require "../verifica.php";
    
    include_once("../../model/class.tickets.php");
    include_once("../../controller/class.tickets.php");
    
    include_once("../../model/class.ticketslan.php");
    include_once("../../controller/class.ticketslan.php");
    
    include_once("../../model/class.jogador.php");
    include_once("../../controller/class.jogador.php");
    
    include_once("../../model/class.jogos.php");
    include_once("../../controller/class.jogos.php");
    
    include_once("../../model/class.local.php");
    include_once("../../controller/class.local.php");
    
    include_once("../../model/class.ficha.php");
    include_once("../../controller/class.ficha.php");
    
    $ticket_id = isset($_GET["id"]) ? $_GET["id"] : 0;
    $jogo_id = isset($_GET["jogo"]) ? $_GET["jogo"] : 0;
    
    $ticket = new Ticket();
    $lan = new Ticketlan();
    $jogador = new Jogador();
    $jogo = new Jogo();
    $local = new Local();
    $ficha = new Ficha();
    
    if (($jogo_id == 0) || (! $jogo->buscar($jogo_id))) {
        echo("<script>
                alert('Registro não encontrado!');
                window.location = 'listagem.php';
              </script>");
        exit();
    }
    
    if (($ticket_id == 0) || (! $ticket->buscar($ticket_id))) {
        echo("<script>
                alert('Registro não encontrado!');
                window.location = 'cadastro.php?id=$jogo_id';
              </script>");
        exit();
    }
    
    if ($ticket->getJogoId() != $jogo_id) {
        echo("<script>
                alert('Registro não encontrado!');
                window.location = 'listagem.php';
              </script>");
        exit();
    }
    
    $jogador->buscar($ticket->getJogadorId());
    $local->buscar($jogo->getLocalId());
    
    $lanctos = $lan->listar($ticket_id);
    $fichas = $ficha->listar();
    
    $cont = 0;
    
    if ($_POST) {
      
      if (isset($_POST["nova_quant"])) {
          if ($lan->alterar())
            echo "<script>window.location = 'lancamentos.php?id=$ticket_id&jogo=$jogo_id';</script>";
          else
            echo "<script>alert('Erro ao alterar. Por favor, tente novamente.')</script>";
      }
      else {
          if ($lan->incluir($ticket_id))
            echo "<script>window.location = 'lancamentos.php?id=$ticket_id&jogo=$jogo_id';</script>";
          else
            echo "<script>alert('Erro ao incluir. Por favor, tente novamente.')</script>";
      }
    }
?>

  <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="../js/jquery-DT-pagination.js"></script>
  
  <script type="text/javascript">
  /* Table initialisation */
  $(document).ready(function() {
    $('#listagem').dataTable( {
        "bSort": false,          //sorting
        "iDisplayLength": 10,   //records per page
        "sDom": "t<'row'<'col-md-6'i><'col-md-6'p>>",
        "sPaginationType": "bootstrap"
      });
    });
    
    function setTipoLan(obj) {
      var tpLan = obj.innerHTML;
      document.getElementById('lblTipoLan').innerHTML = '<strong>'+ tpLan +'</strong>';
      document.getElementById('tipolan').value = tpLan.substring(0,1);
    };
    
    function atualizaTotal(obj) {
      var quant;
      var valor;
      var total = 0;
      var fields = document.getElementsByName("qtd_ficha[]");
      for(var i = 0; i < fields.length; i++) {
        quant = fields[i].value;
        valor = fields[i].id;
        total += (quant * valor);
      }
      document.getElementById('lblTotal').innerHTML = '<strong>Total: R$ '+ total.toFixed(2).replace('.', ',') +'</strong>';
    };
    
    function alteraQuant(obj) {
      document.getElementById("ticketlan_id").value = obj.id;
      document.getElementById("nova_quant").value = obj.name
    }
  </script>
  
  <style>
  .pagination {
        margin:0 ! important;
  }</style> 
  
  <?php if ($ticket->getBaixado() == 0){ ?>
  <!-- Modal - add ficha -->
  <div class="modal fade bs-example-modal-sm" id="modalAddFichas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <form id="form_ficha" class="form" role="form" method="post">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Quantidade de fichas</h4>
            <h4 class="modal-title text-danger" id="lblTipoLan"></h4>
          </div>
          <div class="modal-body col-md-offset-2">
            <div class="row">
              <div class="form-group col-lg-10 col-md-10 col-sm-8 col-xs-6">
                <?php foreach ($fichas as $row) { ?>
                  <div class="input-group">
                    <span class="input-group-addon"><div class="img-circle" style="width:20px; height:20px; border:1px solid; background-color:<?php echo $row["COR"]; ?>;"></div></span>
                    <input type="number" min="0" max="100" class="form-control" name="qtd_ficha[]" placeholder="0" id="<?php echo $row["VALOR"]; ?>" onChange="atualizaTotal(this);" />
                    <input type="hidden" name="ficha_id[]" value="<?php echo $row["FICHA_ID"]; ?>" />
                    <input type="hidden" name="val_ficha[]" value="<?php echo $row["VALOR"]; ?>" />
                  </div>
                <?php } ?>
                <input type="hidden" name="tipolan" id="tipolan" value="" />
              </div>
              <h4 class="text-success" id="lblTotal"><strong>Total: R$ 0,00</strong></h4>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Gravar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /add ficha -->
  
  <!-- Modal - altera quant -->
  <div class="modal fade bs-example-modal-sm" id="modalAlteraQuant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <form id="form_quant" class="form" role="form" method="post">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Alterar quantidade</h4>
          </div>
          <div class="modal-body col-md-offset-2">
            <div class="row">
              <div class="form-group col-lg-10 col-md-10 col-sm-8 col-xs-6">
                <span>Quantidade:</span>
                <input type="number" min="0" max="100" class="form-control" name="nova_quant" id="nova_quant" placeholder="0" value="" required autofocus />
                <input type="hidden" name="ticketlan_id" id="ticketlan_id" value="">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Gravar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /altera quant -->
  <?php } ?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

  <legend class="page-header">Jogo: <?php echo date('d/m/Y', strtotime($jogo->getDtJogo())) .' - '. $local->getDscLocal(); ?>
    <?php if ($ticket->getBaixado() == 0){ ?>
    <div class="btn-group pull-right">    
      <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Adicionar fichas">
        <span class="glyphicon glyphicon-plus"></span> Fichas <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="" data-toggle="modal" data-target="#modalAddFichas" onClick="setTipoLan(this);">Compra</a></li>
        <li class="divider"></li>
        <li><a href="" data-toggle="modal" data-target="#modalAddFichas" onClick="setTipoLan(this);">Devolução</a></li>
      </ul>
    </div>
    <?php } ?>
  </legend>
  
  <h4 class="text-info">Lançamentos do jogador: <strong><?php echo $jogador->getNome(); ?></strong></h4>
  <h4>
    <span class="text-success">Total compras: <strong>R$ <?php echo number_format($ticket->getTotCompra($ticket_id), 2, ',', '.'); ?></strong></span>
    <span class="text-danger">Total devoluções: <strong>R$ <?php echo number_format($ticket->getTotDevolucao($ticket_id), 2, ',', '.'); ?></strong></span>
  </h4>
  

  <div class="table">
    <table class="table table-condensed table-hover" id="listagem">
      <thead>
        <tr>
          <th class="col-sm-1">Tipo</th>
          <th class="col-sm-1 text-center">Ficha</th>
          <th class="col-sm-1 text-center">Quant.</th>
          <th class="col-sm-1 text-center">Valor</th>
          <th class="col-sm-1 text-center">Total</th>
          <th class="col-sm-3"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($lanctos as $row) { ?>
          <tr class="<?php echo (trim($row["TIPOLAN"]) == 'C') ? 'info' : 'warning'; ?>">
            <td><?php echo $row["DSC_TIPOLAN"]; ?></td>
            <td><div class="img-circle center-block" style="width:22px; height:22px; border:1px solid; background-color:<?php echo $row["COR_FICHA"]; ?>;"></div></td>
            <td class="text-center"><?php echo $row["QTD_FICHAS"]; ?></td>
            <td class="text-center"><?php echo 'R$ '. number_format($row["VAL_FICHA"], 2, ',', '.'); ?></td>
            <td class="text-center"><?php echo 'R$ '. number_format($row["VAL_APURADO"], 2, ',', '.'); ?></td>
            <td>
              <?php if ($ticket->getBaixado() == 0){ ?>
                <div class="text-center">
                  <div class="btn-group btn-group-xs">
                    <a class="btn btn-primary" title="Editar registro" href="" data-toggle="modal" data-target="#modalAlteraQuant" id="<?php echo $row["TICKETLAN_ID"]; ?>" name="<?php echo $row["QTD_FICHAS"]; ?>" onClick="alteraQuant(this);">
                      <span class="glyphicon glyphicon-edit"></span> Editar
                    </a>
                    <a class="btn btn-danger" title="Excluir registro" onclick="javascript: if(confirm('Confirma a exclusão do registro?')) location.href='manutencao_lanc.php?id=<?php echo $row["TICKETLAN_ID"]?>&ticket=<?php echo $row["TICKET_ID"]?>&jogo=<?php echo $jogo_id;?>&acao=excluir'">
                      <span class="glyphicon glyphicon-trash"></span> Excluir
                    </a>
                  </div>
                </div>
              <?php } ?>
            </td>
          </tr>
        <?php $cont++; } ?>
      </tbody>
    </table>
  </div> <!-- /table-responsive --> 
  <div class="alert alert-success">
    <strong><?php echo "$cont Lançamentos(s) registrado(s)."; ?></strong>
  </div>
  <div class="btn-group">
    <a class="btn btn-default" href="cadastro.php?id=<?php echo $jogo->getJogoId(); ?>&target=tickets"><span class="glyphicon glyphicon-chevron-left"></span> Voltar</a>
  </div>
  <br />

</div> <!-- /main -->

<?php include_once("../footer.php")?>