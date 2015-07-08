<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    include_once("../header.php");
    
    require "../verifica.php";

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
        }),
        $('#datetimepicker2').datetimepicker({
            language: 'pt-br',
            pickTime: false
        });
    });
      
    $(document).ready(function() {
        $('#form_busca').find('[name="data_ini"]').mask('99/99/9999');
        $('#form_busca').find('[name="data_fin"]').mask('99/99/9999');
    });
  </script> 

  <script>
  $(document).ready(function() {
      $('#form_busca')
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
    <legend class="page-header">Pesquisa de Jogos
      <div class="btn-group pull-right">
        <a class="btn btn-success" title="Novo jogo" href="cadastro.php"><span class="glyphicon glyphicon-file"></span> Novo jogo</a>        
      </div>
    </legend>
  </div>

  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main-med">

    <form id="form_busca" class="form" role="form" method="post" action="listagem.php">

      <div class="row">
        <div class="form-group col-lg-4 col-xs-7">
          <span>Data inicial:</span>
          <div class="input-group">
            <div class='input-group date' id='datetimepicker1' data-date-format="DD/MM/YYYY">
              <input type='text' class="form-control" name="data_ini" value="<?php echo isset($_SESSION["jg_busca1"]) ? $_SESSION["jg_busca1"] : date('d/m/Y', strtotime('-7 days')); ?>" required autofocus/>
              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
        </div>
          
        <div class="form-group col-lg-4 col-xs-7">
          <span>Data final:</span>
          <div class="input-group">
            <div class='input-group date' id='datetimepicker2' data-date-format="DD/MM/YYYY">
              <input type='text' class="form-control" name="data_fin" value="<?php echo isset($_SESSION["jg_busca2"]) ? $_SESSION["jg_busca2"] : date("d/m/Y"); ?>" required/>
              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="form-group col-lg-8 col-xs-12">
          <span>Status:</span>
          <div class="input-group">
            <select class="form-control" name="status" required>
              <option value="T" <?php if ((isset($_SESSION["jg_busca3"]))&&($_SESSION["jg_busca3"])=='T'){ ?> selected <?php } ?> >-- AMBOS --</option>
              <option value="S" <?php if ((isset($_SESSION["jg_busca3"]))&&($_SESSION["jg_busca3"])=='S'){ ?> selected <?php } ?> >Finalizado</option>
              <option value="N" <?php if ((isset($_SESSION["jg_busca3"]))&&($_SESSION["jg_busca3"])=='N'){ ?> selected <?php } ?> >Em Aberto</option>
            </select>
            <span class="input-group-btn">
              <button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search"></span> Buscar</button>
            </span>
          </div>
        </div>        
      </div>
      
    </form>

  </div> <!-- /main -->
  
  <script src="../js/bootstrapValidator.min.js"></script>

<?php include_once("../footer.php"); ?>