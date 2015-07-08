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
    
    include_once("../../model/class.tickets.php");
    include_once("../../controller/class.tickets.php");
    
    include_once("../../model/class.jogador.php");
    include_once("../../controller/class.jogador.php");
    
    include_once("../../model/class.caixa.php");
    include_once("../../controller/class.caixa.php");
    
    $ticket_id = isset($_GET["id"]) ? $_GET["id"] : 0;
    $jogo_id = isset($_GET["jogo"]) ? $_GET["jogo"] : 0;
    
    $jogo = new Jogo();
    $local = new Local();
    $jogador = new Jogador();
    $ticket = new Ticket();
    $caixa = new Caixa();
    
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
    
    $local->buscar($jogo->getLocalId());    
    $jogador->buscar($ticket->getJogadorId());
    $caixa->buscar($ticket->getTicketId());
    
    $tot_ticket = $ticket->getTotais($ticket->getTicketId());
    
    if ($_POST) {
    
        if ($ticket->getBaixado() == 0) {
        
            if ($ticket->finaliza())
                echo "<script>window.location = 'cadastro.php?id=$jogo_id&target=tickets';</script>";
            else
                echo "<script>alert('Erro ao finalizar o ticket!')</script>";
        }
        else
            echo "<script>alert('O ticket já foi finalizado anteriormente. Favor verificar!')</script>";
    }

?>

  <link rel="stylesheet" href="../css/bootstrapValidator.min.css"/>
  
  <script type="text/javascript">
    var calculado = 0.00;
    var acerto = 0.00;
    var troco = 0.00;

    function calculaTotal() {
      calculado = parseFloat(document.getElementById('vr_calculado').value.replace(",", "."));
      acerto = parseFloat(document.getElementById('vr_acerto').value.replace(",", "."));
      
      calculado = ((calculado < 0)||(calculado == 0)||(calculado > 0)) ? calculado : 0;
      acerto = ((acerto < 0)||(acerto == 0)||(acerto > 0)) ? acerto : 0;
      
      if (calculado < 0)
        troco = acerto + calculado;
      else
        troco = acerto - calculado;
    
      document.getElementById('vr_acerto').value = acerto.toFixed(2).replace(".", ",");
      document.getElementById("vr_troco").value = troco.toFixed(2).replace(".", ",");
    };
    
    function zeraTotais() {
      document.getElementById('vr_acerto').value = '';
      document.getElementById("vr_troco").value = '0,00';
      
      document.getElementById('vr_acerto').focus();
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
  
  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <legend class="page-header">Jogo: <?php echo date('d/m/Y', strtotime($jogo->getDtJogo())) .' - '. $local->getDscLocal(); ?></legend>
  
    <h4 class="text-info">Fechamento de ticket <strong>#<?php echo $ticket_id; ?></strong> do jogador: <strong><?php echo $jogador->getNome(); ?></strong></h4><br>
  
    <form id="form_cad" class="form" role="form" method="post">    

      <div class="row">
        <div class="form-group col-lg-2 col-md-3 col-sm-3 col-xs-4">
          <span>(-) Total Débitos:</span>
          <input type="text" name="vr_deb" class="form-control text-right" value="<?php echo number_format(($ticket->getBaixado() == 1) ? $caixa->getVrDeb() : $tot_ticket["VR_DEB"], 2, ',', '.'); ?>" readonly />
        </div>
        
        <div class="form-group col-lg-2 col-md-3 col-sm-3 col-xs-4">
          <span>(+) Total Créditos:</span>
          <input type="text" name="vr_cred" class="form-control text-right" value="<?php echo number_format(($ticket->getBaixado() == 1) ? $caixa->getVrCred() : $tot_ticket["VR_CRED"], 2, ',', '.'); ?>" readonly />
        </div>
      </div>
      
      <div class="row">
        <div class="form-group col-lg-2 col-md-3 col-sm-3 col-xs-4">
          <span>(=) Valor Calculado:</span>
          <input type="text" name="vr_calculado" id="vr_calculado" class="form-control text-right" value="<?php echo number_format(($ticket->getBaixado() == 1) ? $caixa->getVrCalculado() : $tot_ticket["VR_CALCULADO"], 2, ',', '.'); ?>" readonly />
        </div>
      </div>
      
      <div class="row">
        <div class="form-group col-lg-2 col-md-3 col-sm-3 col-xs-4">
          <span><strong>(-) Valor Acerto:</strong></span>
          <?php if ($ticket->getBaixado() == 0){ ?>
            <input type="text" name="vr_acerto" id="vr_acerto" class="form-control text-right" value="" onBlur="calculaTotal();" autofocus />
          <?php } else { ?>
            <input type="text" name="vr_acerto" id="vr_acerto" class="form-control text-right" value="<?php echo number_format($caixa->getVrAcerto(), 2, ',', '.'); ?>" readonly />
          <?php } ?>
        </div>
        
        <div class="form-group col-lg-2 col-md-3 col-sm-3 col-xs-4">
          <span>(=) Valor Troco:</span>
          <input type="text" name="vr_troco" id="vr_troco" class="form-control text-right" value="<?php echo ($ticket->getBaixado() == 1) ? number_format($caixa->getVrTroco(), 2, ',', '.') : '0,00'; ?>" readonly />
        </div>
      </div>
      
      <div class="btn-group">
        <?php if ($ticket->getBaixado() == 0){ ?>
          <button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-ok"></span> Finalizar</button>
          <a class="btn btn-warning" href="cadastro.php?id=<?php echo $jogo_id; ?>&target=tickets"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
          <a class="btn btn-default" title="Zerar totais" onClick="zeraTotais();"><span class="glyphicon glyphicon-refresh"></span></a>
        <?php } else { ?>
          <a class="btn btn-default" href="cadastro.php?id=<?php echo $jogo_id; ?>&target=tickets"><span class="glyphicon glyphicon-chevron-left"></span> Voltar</a>        
        <?php } ?>
      </div>      
    </form>

  </div> <!-- /main -->
  
  <script src="../js/bootstrapValidator.min.js"></script>

<?php include_once("../footer.php"); ?>