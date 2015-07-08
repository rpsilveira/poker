<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    class Caixa extends CaixaModel {
    
        public function __construct() {
        
            date_default_timezone_set('America/Sao_Paulo');
        }
        
        public function buscar($ticket_id) {

            $this->setTicketId($ticket_id);

            $ret_consulta = $this->buscaCaixa();
            
            $this->setCaixaId($ret_consulta['CAIXA_ID']);
            $this->setVrDeb($ret_consulta['VR_DEB']);
            $this->setVrCred($ret_consulta['VR_CRED']);
            $this->setVrCalculado($ret_consulta['VR_CALCULADO']);
            $this->setVrAcerto($ret_consulta['VR_ACERTO']);
            $this->setVrTroco($ret_consulta['VR_TROCO']);
            
            return ($ret_consulta['TICKET_ID'] == $ticket_id);
        }
    
    }
    
?>