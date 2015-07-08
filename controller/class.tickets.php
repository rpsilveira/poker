<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    class Ticket extends TicketsModel {
    
        public function __construct() {
        
            date_default_timezone_set('America/Sao_Paulo');
        }
        
        
        public function buscar($ticket_id) {

            $this->setTicketId($ticket_id);

            $ret_consulta = $this->buscaTicket();
            
            $this->setJogoId($ret_consulta['JOGO_ID']);
            $this->setJogadorId($ret_consulta['JOGADOR_ID']);
            $this->setBaixado($ret_consulta['BAIXADO']);
            
            return ($ret_consulta['TICKET_ID'] == $ticket_id);
        }
        
        public function listar($jogo_id) {
          
            return $this->listarTodos($jogo_id);
        }
        
        public function getTotais($ticket_id) {
        
            $this->setTicketId($ticket_id);
            
            return $this->getSaldoFechamento();
        }
        
        public function finaliza() {
        
            $vr_acerto = str_replace(',', '.', strip_tags(trim($_POST["vr_acerto"])));
            $vr_troco  = str_replace(',', '.', strip_tags(trim($_POST["vr_troco"])));
        
            return $this->finalizaTicket($vr_acerto, $vr_troco);
        }
        
        public function getTotCompra($ticket_id) {
        
            $this->setTicketId($ticket_id);
            
            $dados_cons = $this->getTotCompraTicket();
            
            return $dados_cons["TOT_COMPRA"];
        }
        
        public function getTotDevolucao($ticket_id) {
        
            $this->setTicketId($ticket_id);
            
            $dados_cons = $this->getTotDevolucaoTicket();
            
            return $dados_cons["TOT_DEVOLUCAO"];
        }
    
    }

?>