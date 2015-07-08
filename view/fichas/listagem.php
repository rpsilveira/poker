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

    $fichas = $ficha->listar();

    $cont = 0;
?>

  <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="../js/jquery-DT-pagination.js"></script>
  
  <script type="text/javascript">
  /* Table initialisation */
  $(document).ready(function() {
    $('#listagem').dataTable( {
        "bSort": true,          //sorting
        "iDisplayLength": 10,   //records per page
        "sDom": "t<'row'<'col-md-6'i><'col-md-6'p>>",
        "sPaginationType": "bootstrap"
      });
    });
  </script>
  
  <style>
  .pagination {
        margin:0 ! important;
  }</style> 

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

  <legend class="page-header">Listagem de Fichas
    <div class="btn-group pull-right">
      <a class="btn btn-success" title="Novo cadastro" href="cadastro.php"><span class="glyphicon glyphicon-file"></span> Novo</a>
    </div>
  </legend>

  <div class="table">
    <table class="table table-striped table-condensed table-hover" id="listagem">
      <thead>
        <tr>
          <th class="col-sm-3">Nome</th>
          <th class="col-sm-2">Valor</th>
          <th class="col-sm-1">Cor</th>
          <th class="col-sm-3"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($fichas as $row) { ?>
          <tr>
            <td><?php echo $row["NOME"]; ?></td>
            <td><?php echo 'R$ '. number_format($row["VALOR"], 2, ',', '.'); ?></td>
            <td><div class="img-circle" style="width:22px; height:22px; border:1px solid; background-color:<?php echo $row["COR"]; ?>;"></div></td>
            <td>
              <div class="text-center">
                <div class="btn-group btn-group-xs">
                  <a class="btn btn-primary" title="Editar registro" href="cadastro.php?id=<?php echo $row["FICHA_ID"];?>"><span class="glyphicon glyphicon-edit"></span> Editar</a>
                  <a class="btn btn-danger" title="Excluir registro" onclick="javascript: if(confirm('Confirma a exclusão do registro?')) location.href='cadastro.php?acao=excluir&id=<?php echo $row["FICHA_ID"];?>'">
                    <span class="glyphicon glyphicon-trash"></span> Excluir
                  </a>
                </div>
              </div>
            </td>
          </tr>
        <?php $cont++; } ?>
      </tbody>
    </table>
  </div> <!-- /table-responsive --> 
  <div class="alert alert-success">
    <strong><?php echo "$cont Registro(s) cadastrado(s)."; ?></strong>
  </div>
  <br />

</div> <!-- /main -->

<?php include_once("../footer.php"); ?>