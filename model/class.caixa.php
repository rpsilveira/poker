<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    include_once('class.dao.php');
    
    class CaixaModel {
      
        private $caixa_id;
        private $ticket_id;
        private $vr_deb;
        private $vr_cred;
        private $vr_calculado;
        private $vr_acerto;
        private $vr_troco;
         
        public function __construct(){}
         
        public function getCaixaId() {
           return $this->caixa_id;
        }
        public function getTicketId() {
           return $this->ticket_id;
        }
        public function getVrDeb() {
           return $this->vr_deb;
        }
        public function getVrCred() {
           return $this->vr_cred;
        }
        public function getVrCalculado() {
           return $this->vr_calculado;
        }
        public function getVrAcerto() {
           return $this->vr_acerto;
        }
        public function getVrTroco() {
           return $this->vr_troco;
        }
         
        public function setCaixaId($caixa_id) {
           $this->caixa_id = $caixa_id;
        }
        public function setTicketId($ticket_id) {
           $this->ticket_id = $ticket_id;
        }
        public function setVrDeb($vr_deb) {
           $this->vr_deb = $vr_deb;
        }
        public function setVrCred($vr_cred) {
           $this->vr_cred = $vr_cred;
        }
        public function setVrCalculado($vr_calculado) {
           $this->vr_calculado = $vr_calculado;
        }
        public function setVrAcerto($vr_acerto) {
           $this->vr_acerto = $vr_acerto;
        }
        public function setVrTroco($vr_troco) {
           $this->vr_troco = $vr_troco;
        }
         
        public function buscaCaixa() {

            $query = "SELECT * FROM TCAIXA
                      WHERE TICKET_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getTicketId(), PDO::PARAM_INT);
            
            $sql->execute();
            
            $retorno = $sql->fetch(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
       
    }

?>