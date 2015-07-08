<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    include_once("../model/class.usuario.php");
    include_once("../controller/class.usuario.php");
    
    if (!isset($_SESSION))
       session_start();
       
    if (!isset($_SESSION["tentativas"]))
        $_SESSION["tentativas"] = 1;

    $log = isset($_GET['log']) ? $_GET['log'] : '';

    if ($_POST){
    
        $login = $_POST["login"];

        if ($login) {

            $usuario = new Usuario();

            if ($usuario->login()) {
                if ($_SESSION["tentativas"] >= 3) {
                    
                    //comparação case-insensitive de strings
                    if (strcasecmp($_SESSION['captcha'], $_POST['captcha']) == 0) {
                        
                        $_SESSION["tentativas"] = 0;
                        
                        header("Location: main.php");
                    }
                }
                else {
                    header("Location: main.php");
                }
            }
            else {
                $_SESSION["tentativas"] = $_SESSION["tentativas"] + 1;
                
                header("Location: login_erro.php?log=". $login);
            }
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
          <input type="text" name="login" class="form-control" placeholder="Login" value="<?php echo $log; ?>" required autofocus />
          <input type="password" name="senha" class="form-control" placeholder="Senha" required />
          <?php if($_SESSION["tentativas"] >= 3){ ?>
            <img src="captcha/captcha.php" alt="código captcha" class="img-responsive center-block"/>
            <div class="text-center">
              <label for="captcha">Digite o código:</label>
              <input type="text" name="captcha" id="captcha" maxlength="12" />
              <a class="btn-sm btn-default" onClick="location.reload();" title="Gerar outro código"><span class="glyphicon glyphicon-refresh"></span></a>
            </div>
          <?php } ?>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
          <br />
          <?php if($_SESSION["tentativas"] >= 4){ ?>
            <div class="alert alert-danger">O login, a senha ou o código da imagem informados estão incorretos.</div>
          <?php }else{ ?>
            <div class="alert alert-danger">O login ou a senha inseridos estão incorretos.</div>
          <?php } ?>
        </div>
      </div>
    </form>
  </div> <!-- /container -->
</body>
</html>