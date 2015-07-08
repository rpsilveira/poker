<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    include_once($_SERVER['DOCUMENT_ROOT']."../model/class.usuario.php");
    include_once($_SERVER['DOCUMENT_ROOT']."../controller/class.usuario.php");

    if ($_POST) {

        $login = $_POST["login"];

        if ($login) {

            $usuario = new Usuario();

            if ($usuario->login())            
                header("Location: main.php");
            else
                header("Location: login_erro.php?log=". $login);
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="imagens/icon.ico">

  <title>Poker dos Amigos | Identificação</title>

  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="templates/signin.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <form class="form-signin" role="form" method="post">
      <div class="panel panel-default">
        <div class="panel-body">
          <img src="imagens/logo.png" width="250px" class="img-responsive center-block"/>
          <h2 class="text-center">Identifique-se</h2>
          <input type="text" name="login" class="form-control" placeholder="Login" required autofocus />
          <input type="password" name="senha" class="form-control" placeholder="Senha" required />
          <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
        </div>
      </div>
    </form>
  </div> <!-- /container -->
</body>
</html>