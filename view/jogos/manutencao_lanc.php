<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    require "../verifica.php";
    
    include_once("../../model/class.ticketslan.php");
    include_once("../../controller/class.ticketslan.php");

    $ticketlan_id = isset($_GET["id"]) ? $_GET["id"] : 0;
    $ticket_id = isset($_GET["ticket"]) ? $_GET["ticket"] : 0;
    $jogo_id = isset($_GET["ticket"]) ? $_GET["jogo"] : 0;
    $acao = isset($_GET["acao"]) ? $_GET["acao"] : '';
    
    $lan = new Ticketlan();
    
    if (($acao == 'excluir')&&($ticketlan_id > 0)&&($ticket_id > 0)) {
      if ($lan->excluir($ticketlan_id))
        echo "<script>window.location = 'lancamentos.php?id=$ticket_id&jogo=$jogo_id';</script>";
      else
        echo "<script>
                window.location = 'lancamentos.php?id=$ticket_id';
                alert('Erro ao excluir. Por favor, tente novamente.');
              </script>";
    }
    
?>