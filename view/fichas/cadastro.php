<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    include_once("../header.php");
    
    require "../verifica.php";

    include_once("../../model/class.ficha.php");
    include_once("../../controller/class.ficha.php");

    $ficha = new Ficha();

    $acao   = isset($_GET["acao"]) ? $_GET["acao"] : "";
    $codigo = isset($_GET["id"]) ? $_GET["id"] : 0;

    if ($codigo > 0) {
      
        if (! $ficha->buscar($codigo)) {
            echo("<script>
                    alert('Registro não encontrado!');
                    window.location = 'listagem.php';
                  </script>");
            exit();
        }
    }

    if (($acao == "excluir")&&($codigo > 0)) {
  
        if ($ficha->excluir($codigo)) {
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
      if ($ficha->incluir())
        echo "<script>window.location = 'listagem.php';</script>";
      else
        echo "<script>alert('Erro ao incluir!')</script>";
    }
    else {
      if ($ficha->alterar())
        echo "<script>window.location = 'listagem.php';</script>";
      else
        echo "<script>alert('Erro ao alterar!')</script>";
    }
  }

?>

  <link rel="stylesheet" href="../css/bootstrapValidator.min.css"/>
  
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
    <legend class="page-header">Cadastro de Fichas - <?php echo ($codigo == 0) ? "incluir" : "editar" ?>
    <?php if ($codigo > 0){ ?>
      <div class="btn-group pull-right">
        <a class="btn btn-success" title="Novo cadastro" href="cadastro.php"><span class="glyphicon glyphicon-file"></span> Novo</a>
      </div>
    <?php } ?>
    </legend>
  </div>

  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main-med">

    <form id="form_cad" class="form" role="form" method="post">

      <div class="row">
        <div class="form-group col-lg-3 col-xs-4">
          <span>Código:</span>
          <input type="text" name="ficha_id" class="form-control" value="<?php echo $ficha->getFichaId(); ?>" disabled />
        </div>
      </div>

      <div class="row">
        <div class="form-group col-lg-8 col-xs-12">
          <span>Nome:</span>
          <input type="text" name="nome" class="form-control" maxlength="30" value="<?php echo $ficha->getNome(); ?>" required autofocus />
        </div>
      </div>
      
      <div class="row">
        <div class="form-group col-lg-5 col-xs-7">
          <span>Cor:</span>
          <input type="color" name="cor" class="form-control" maxlength="30" value="<?php echo $ficha->getCor(); ?>" required />
        </div>
        
        <div class="form-group col-lg-3 col-xs-5">
          <span>Valor R$:</span>
          <input type="text" name="valor" class="form-control" maxlength="30" value="<?php echo number_format($ficha->getValor(), 2, ',', '.'); ?>" required/>
        </div>
      </div>

      <div class="btn-group">
        <button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-ok"></span> Gravar</button>
        <a class="btn btn-warning" href="listagem.php"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
      </div>
    </form>
  </div> <!-- /main -->
  
  <script src="../js/bootstrapValidator.min.js"></script>

<?php include_once("../footer.php"); ?>