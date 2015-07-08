<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    class Ticketlan extends TicketslanModel {
    
        public function __construct() {
        
            date_default_timezone_set('America/Sao_Paulo');
        }
        
        public function incluir($ticket_id) {
        
            $this->setTicketId($ticket_id);
            $this->setTipoLan(strip_tags(trim(($_POST["tipolan"]))));
            $this->setDtInc(date("Y-m-d H:i:s"));
        
            $fichas_id  = isset($_POST["ficha_id"]) ? $_POST["ficha_id"] : array();
            $qtd_fichas = isset($_POST["qtd_ficha"]) ? $_POST["qtd_ficha"] : array();
            $val_fichas = isset($_POST["val_ficha"]) ? $_POST["val_ficha"] : array();
            
            //valida se todos os arrays têm a mesma quantidade de elementos
            if ((count($fichas_id) == count($qtd_fichas)) && (count($fichas_id) == count($val_fichas)))
                return $this->incluiLancamento($fichas_id, $qtd_fichas, $val_fichas);
            else
                return false;
        }
        
        public function alterar() {
          
            $this->setTicketlanId(strip_tags(trim($_POST["ticketlan_id"])));
            $this->setQtdFichas(strip_tags(trim($_POST["nova_quant"])));
            $this->setDtAlt(date("Y-m-d H:i:s"));

            return $this->alteraLancamento();
        }
        
        public function excluir($ticketlan_id) {

            $this->setTicketlanId($ticketlan_id);

            return $this->excluiLancamento();
        }
        
        public function buscar($ticketlan_id) {

            $this->setTicketlanId($ticketlan_id);

            $ret_consulta = $this->buscaLancamento();
            
            $this->setTicketId($ret_consulta['TICKET_ID']);
            $this->setTipoLan($ret_consulta['TIPOLAN']);
            $this->setFichaId($ret_consulta['FICHA_ID']);
            $this->setQtdFichas($ret_consulta['QTD_FICHAS']);
            $this->setValFicha($ret_consulta['VAL_FICHA']);
            $this->setValApurado($ret_consulta['VAL_APURADO']);
            $this->setDtInc($ret_consulta['DT_INC']);
            
            return ($ret_consulta['TICKETLAN_ID'] == $ticketlan_id);
        }
        
        public function listar($ticket_id) {
          
            return $this->listarTodos($ticket_id);
        }
    
    }

?>