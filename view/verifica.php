<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    
    header("Content-Type: text/html; charset=utf-8", true);

    // Iniciar Session PHP 
    if (!isset($_SESSION)) {
        session_start();
    }

    // Se o usuário não estiver logado, direciona para o formulário de login
    if ( (!isset($_SESSION["usr_id"]))||($_SESSION["usr_id"] == null) ) {

        echo("
            <script>
                alert('Acesso permitido somente para usuários logados.');
                location.href = '/';
            </script>
        ");

        exit();
    }

    $registro = $_SESSION['registro'];
    $limite   = $_SESSION['limite'];

    if ($registro) {  // verifica se a session registro está ativa
        $segundos = time() - $registro;
    }

    if ($segundos > $limite) {

        $min = intval($segundos / 60);  

        session_destroy();

        echo("
            <script>
                alert('Sua sessão está sem atividade há $min minutos. Por favor, faça o login novamente.');
                location.href = '/';
            </script>
        ");

        exit();		
    }
    else{
        $_SESSION['registro'] = time();
    }
?>