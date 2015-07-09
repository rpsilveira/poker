<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    include_once("../header.php");
    
    require "../verifica.php";

    include_once("../../model/class.jogos.php");
    include_once("../../controller/class.jogos.php");
    
    include_once("../../model/class.local.php");
    include_once("../../controller/class.local.php");
    
    include_once("../../model/class.jogador.php");
    include_once("../../controller/class.jogador.php");
    
    include_once("../../model/class.tickets.php");
    include_once("../../controller/class.tickets.php");
    
    $acao   = isset($_GET["acao"]) ? $_GET["acao"] : "";
    $codigo = isset($_GET["id"]) ? $_GET["id"] : 0;
    $target = isset($_GET["target"]) ? $_GET["target"] : '';

    $jogo = new Jogo();
    $local = new Local();
    $jogador = new Jogador();
    $ticket = new Ticket();
    
    $locais = $local->listar();
    $jogadores = $jogador->listarAtivos();
    $tickets = $ticket->listar($codigo);

    if ($codigo > 0) {
      
        if (! $jogo->buscar($codigo)) {
            echo("<script>
                    alert('Registro não encontrado!');
                    window.location = 'listagem.php';
                  </script>");
            exit();
        }
    }

    if (($acao == "excluir")&&($codigo > 0)&&($jogo->getFinalizado() == 0)) {
  
        if ($jogo->excluir($codigo)) {
            echo "<script>window.location = 'listagem.php';</script>";
        }
        else {
            echo("<script>
                    alert('Erro ao excluir o cadastro. Verifique as dependências e tente novamente.');
                    window.location = 'listagem.php';
                  </script>");
        }
    }

    if ($_POST) {

      if ($codigo == 0) {
        if ($jogo->incluir()) {
          $codigo = $jogo->getJogoId();
          
          echo "<script>window.location = 'cadastro.php?id=$codigo&target=tickets';</script>";
        }
        else
          echo "<script>alert('Erro ao incluir!')</script>";
      }
      elseif ($jogo->getFinalizado() == 0) {
        if ($jogo->alterar())
          echo "<script>window.location = 'cadastro.php?id=$codigo&target=tickets';</script>";
        else
          echo "<script>alert('Erro ao alterar!')</script>";
      }
      else
        echo "<script>alert('O jogo já foi finalizado. Não é permitido alterar!')</script>";      
    }

?>

  <link rel="stylesheet" href="../css/bootstrapValidator.min.css"/>
  <link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
  
  <script src="../js/moment.js"></script>
  <script src="../js/bootstrap-datetimepicker.js"></script>
  <script src="../js/bootstrap-datepicker.pt-BR.js"></script>
  <script src="../js/jquery.mask.min.js"></script>  
  <script src="../js/bootstrapValidator.min.js"></script>  
  
  <script type="text/javascript">
    $(function () {
        $('#datetimepicker1').datetimepicker({
            language: 'pt-br',
            pickTime: false
        })
    });
      
    $(document).ready(function() {
        $('#form_cad').find('[name="dt_jogo"]').mask('99/99/9999');
    });
    
    function removeItem(obj) {
        var objTR = obj.parentNode.parentNode.parentNode.parentNode;
        document.getElementById('tbjogadores').deleteRow(objTR.rowIndex);
    };
    
    function addItem() {
      
        var x = document.getElementById('novo_jogador');

        var fields = document.getElementsByName("jogador_id[]");
        
        for(var i = 0; i < fields.length; i++) {
          if (fields[i].value == x.options[x.selectedIndex].value) {
            alert('Jogador já incluído. Favor verificar!');
            return;
          }
        }

        var tab  = document.getElementById('tbjogadores');
        var body = tab.tBodies[0];
        var row  = body.insertRow(-1);
        
        var id = x.options[x.selectedIndex].value;
        var nome = x.options[x.selectedIndex].text;
        
        var newCell0 = row.insertCell(0);
        newCell0.innerHTML = '<td><div class="text-center"> - </div></td>';
        
        var newCell1 = row.insertCell(1);
        newCell1.innerHTML = '<td>'+ nome +'</td>';
        
        var newCell2 = row.insertCell(2);
        newCell2.innerHTML = '<td><div class="text-center"><span class="glyphicon glyphicon-unchecked"></span></div></td>';
        
        var newCell3 = row.insertCell(3);
        newCell3.innerHTML = '<td><div class="text-center"><span class="glyphicon glyphicon-unchecked"></span></div></td>';

        var newCell4 = row.insertCell(4);
        newCell4.innerHTML = '<td>'+
                             '<div class="text-center">'+
                             '<div class="btn-group btn-group-sm">'+
                             '<a class="btn btn-danger" title="Remover jogador" onClick="removeItem(this);"><span class="glyphicon glyphicon-trash"></span> Excluir</a>'+
                             '</div>'+
                             '</div>'+
                             '<input type="hidden" name="ticket_id[]" value="" />'+
                             '<input type="hidden" name="jogador_id[]" value="'+ id +'" />'+
                             '</td>';
    };
  </script>
  
  <script>
  $(document).ready(function() {
      $('#form_cad')
          .bootstrapValidator({
              // Only disabled elements are excluded
              // The invisible elements belonging to inactive tabs must be validated
              excluded: [':disabled'],
              feedbackIcons: {
                  valid: 'glyphicon glyphicon-ok',
                  invalid: 'glyphicon glyphicon-remove',
                  validating: 'glyphicon glyphicon-refresh'
              }
          });
  });
  </script>
  
  <?php if ($jogo->getFinalizado() == 0){ ?>
  <!-- Modal - add jogador -->
  <div class="modal fade bs-example-modal-sm" id="modalAddJogador" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <form id="form_status" class="form" role="form" method="post">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Adicionar jogador</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="form-group col-lg-12">
                <span><strong>Nome:</strong></span>
                <select class="form-control" name="novo_jogador" id="novo_jogador" required autofocus/>
                  <?php foreach ($jogadores as $row) { ?>
                    <option value="<?php echo $row['JOGADOR_ID']; ?>"><?php echo $row["NOME"]; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="addItem();">Confirmar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /add jogador --> 
  <?php } ?>

  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <legend class="page-header">Cadastro de Jogos <?php if ($jogo->getFinalizado() == 0) { echo ($codigo == 0) ? "- incluir" : "- editar"; } ?>
      <div class="btn-group pull-right">
        <?php if ($codigo > 0){ ?>
          <a class="btn btn-success" title="Novo jogo" href="cadastro.php"><span class="glyphicon glyphicon-file"></span> Novo jogo</a>
        <?php } ?>
        <a class="btn btn-info" title="Buscar jogo" href="busca.php"><span class="glyphicon glyphicon-search"></span> Buscar jogo</a>
      </div>
    </legend>
  </div>
  
  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main-med">

    <form id="form_cad" class="form" role="form" method="post">
    
      <ul id="tabs_jogo" class="nav nav-tabs" data-tabs="tabs" style="border:0px;">
        <li class="<?php echo ($target != 'tickets') ? 'active' : '';?>"><a href="#tab_dados" data-toggle="tab">Dados</a></li>
        <li class="<?php echo ($target == 'tickets') ? 'active' : '';?>"><a href="#tab_tickets" data-toggle="tab">Tickets</a></li>
      </ul>
      
      <div id="tab-content-jogo" class="tab-content">
        <div class="tab-pane <?php echo ($target != 'tickets') ? 'active' : '';?>" id="tab_dados">

          <div class="row">
            <div class="form-group col-lg-4">
              <span>Data:</span>
              <div class="input-group">
                <div class='input-group date' id='datetimepicker1' data-date-format="DD/MM/YYYY">
                  <input type='text' class="form-control" id="dt_jogo" name="dt_jogo" value="<?php echo $codigo == 0 ? date('d/m/Y') : date('d/m/Y', strtotime($jogo->getDtJogo())); ?>" required autofocus/>
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>
            </div>
            
            <div class="form-group col-lg-6">
              <span>Local:</span>
              <select class="form-control" name="local_id" required>
                <?php foreach ($locais as $row) { ?>
                  <option value="<?php echo $row['LOCAL_ID']; ?>" <?php if ($row["LOCAL_ID"] == $jogo->getLocalId()) { ?> selected <?php } ?> > <?php echo $row["DSC_LOCAL"]; ?> </option>
                <?php } ?>
              </select>
            </div>
          </div>
          
          <div class="row">
            <div class="form-group col-lg-10">
              <span>Observação:</span>
              <textarea name="obs" class="form-control" rows="4" maxlength="200"><?php echo $jogo->getObs(); ?></textarea>
            </div>
          </div>
          
          <div class="row">
            <div class="form-group col-lg-4">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="finalizado" value="<?php echo $codigo == 0 ? '0' : $jogo->getFinalizado(); ?>"
                  <?php if ($jogo->getFinalizado()==1){ ?> checked="checked" <?php } ?> disabled><strong>Finalizado</strong></input>
                </label>
              </div>
            </div>
          </div>
      
        </div> <!-- /tab dados -->      
        
        <div class="tab-pane <?php echo ($target == 'tickets') ? 'active' : '';?>" id="tab_tickets">
          <?php if ($jogo->getFinalizado() == 0){ ?>
            <div class="btn-group pull-right">
              <a class="btn btn-primary" title="Adicionar jogador" href="" data-toggle="modal" data-target="#modalAddJogador" id="btnAddJogador">
                <span class="glyphicon glyphicon-plus"></span> Jogador
              </a>
            </div>
            <br><br>
          <?php } ?>
          
          <div class="panel panel-default">
            <div class="panel-heading text-center"><strong>Jogadores</strong></div>
            <table class="table table-striped table-condensed table-hover table-bordered" id="tbjogadores">
              <thead>
                <tr>
                  <th class="col-sm-1 text-center">#</th>
                  <th class="col-sm-3 text-center">Nome</th>
                  <th class="col-sm-1 text-center">Lançado</th>
                  <th class="col-sm-1 text-center">Baixado</th>
                  <th class="col-sm-2 text-center">Opções</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($tickets as $row) { ?>
                  <tr>
                    <td class="text-center"><?php echo $row["TICKET_ID"]; ?></td>
                    <td><?php echo $row["JOGADOR"]; ?></td>
                    <td class="text-center"><span class="glyphicon glyphicon-<?php echo $row["EXISTE_LAN"] > 0 ? 'check' : 'unchecked'; ?>"></span></td>
                    <td class="text-center"><span class="glyphicon glyphicon-<?php echo $row["BAIXADO"] == 1 ? 'check' : 'unchecked'; ?>"></span></td>
                    <td>
                      <div class="text-center">
                        <div class="btn-group btn-group-sm">
                        <?php if ($row["BAIXADO"] == 0){ ?>
                          <a class="btn btn-danger" title="Remover jogador" onClick="removeItem(this);"><span class="glyphicon glyphicon-trash"></span></a>
                          <a class="btn btn-info" title="Lançamentos" href="lancamentos.php?id=<?php echo $row["TICKET_ID"]; ?>&jogo=<?php echo $jogo->getJogoId(); ?>"><span class="glyphicon glyphicon-th-list"></span></a>
                          <a class="btn btn-success" title="Finalizar ticket" href="finaliza.php?id=<?php echo $row["TICKET_ID"]; ?>&jogo=<?php echo $jogo->getJogoId(); ?>"><span class="glyphicon glyphicon-ok"></span></a>
                        <?php } else { ?>
                          <a class="btn btn-info" title="lançamentos" href="lancamentos.php?id=<?php echo $row["TICKET_ID"]; ?>&jogo=<?php echo $jogo->getJogoId(); ?>"><span class="glyphicon glyphicon-th-list"></span></a>
                          <a class="btn btn-warning" title="Visualizar fechamento" href="finaliza.php?id=<?php echo $row["TICKET_ID"]; ?>&jogo=<?php echo $jogo->getJogoId(); ?>"><span class="glyphicon glyphicon-usd"></span></a>
                        <?php } ?>
                        </div>
                      </div>
                      <input type="hidden" name="ticket_id[]" value="<?php echo $row["TICKET_ID"]; ?>" />
                      <input type="hidden" name="jogador_id[]" value="<?php echo $row["JOGADOR_ID"]; ?>" />
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        
        </div> <!-- /tab tickets -->
      
      </div> <!-- /tab-content -->

      <div class="btn-group">
        <?php if ($jogo->getFinalizado() == 0){ ?>      
          <button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-ok"></span> Gravar</button>
          <a class="btn btn-warning" href="<?php echo ($codigo == 0) ? 'busca.php' : 'listagem.php'; ?>"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
        <?php } else { ?>
          <a class="btn btn-default" href="<?php echo ($codigo == 0) ? 'busca.php' : 'listagem.php'; ?>"><span class="glyphicon glyphicon-chevron-left"></span> Voltar</a>
        <?php } ?>
      </div>      
    </form>

  </div> <!-- /main -->
  
  <script src="../js/bootstrapValidator.min.js"></script>

<?php include_once("../footer.php"); ?>