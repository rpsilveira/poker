<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    include_once('class.dao.php');
    
    class TicketslanModel {
    
        private $ticketlan_id;
        private $ticket_id;
        private $tipolan;
        private $ficha_id;
        private $qtd_fichas;
        private $val_ficha;
        private $val_apurado;
        private $dt_inc;
        private $dt_alt;
        
        public function getTicketlanId() {
            return $this->ticketlan_id;
        }
        public function getTicketId() {
            return $this->ticket_id;
        }
        public function getTipolan() {
            return $this->tipolan;
        }
        public function getFichaId() {
            return $this->ficha_id;
        }
        public function getQtdFichas() {
            return $this->qtd_fichas;
        }
        public function getValFicha() {
            return $this->val_ficha;
        }
        public function getValApurado() {
            return $this->val_apurado;
        }
        public function getDtInc() {
            return $this->dt_inc;
        }
        public function getDtAlt() {
            return $this->dt_alt;
        }
        
        public function setTicketlanId($ticketlan_id) {
            $this->ticketlan_id = $ticketlan_id;
        }
        public function setTicketId($ticket_id) {
            $this->ticket_id = $ticket_id;
        }
        public function setTipolan($tipolan) {
            $this->tipolan = $tipolan;
        }
        public function setFichaId($ficha_id) {
            $this->ficha_id = $ficha_id;
        }
        public function setQtdFichas($qtd_fichas) {
            $this->qtd_fichas = $qtd_fichas;
        }
        public function setValFicha($val_ficha) {
            $this->val_ficha = $val_ficha;
        }
        public function setValApurado($val_apurado) {
            $this->val_apurado = $val_apurado;
        }
        public function setDtInc($dt_inc) {
            $this->dt_inc = $dt_inc;
        }
        public function setDtAlt($dt_alt) {
            $this->dt_alt = $dt_alt;
        }
        
        public function incluiLancamento($fichas_id, $qtd_fichas, $val_fichas) {
        
            $db = Dao::abreConexao();
            
            $db->beginTransaction();
            
            try {

                for ($i = 0; $i < sizeof($fichas_id); $i++) {
                
                    if ($qtd_fichas[$i] > 0) {
            
                        $query = "INSERT INTO TTICKETSLAN(TICKET_ID, TIPOLAN, FICHA_ID, QTD_FICHAS, VAL_FICHA, VAL_APURADO, DT_INC)
                                  VALUES(?, ?, ?, ?, ?, ?, ?)";
                                       
                        $sql = $db->prepare($query);
                        
                        $sql->bindValue(1, $this->getTicketId(), PDO::PARAM_INT);
                        $sql->bindValue(2, $this->getTipolan(), PDO::PARAM_STR);
                        $sql->bindValue(3, $fichas_id[$i], PDO::PARAM_INT);
                        $sql->bindValue(4, $qtd_fichas[$i], PDO::PARAM_STR);
                        $sql->bindValue(5, $val_fichas[$i], PDO::PARAM_STR);
                        $sql->bindValue(6, ($qtd_fichas[$i] * $val_fichas[$i]), PDO::PARAM_STR);
                        $sql->bindValue(7, $this->getDtInc(), PDO::PARAM_STR);
                        
                        $sql->execute();
                    }
                }
                
                $retorno = $db->commit();
            
            } catch(PDOException $e) {
            
                //echo $e->getMessage();

                $db->rollBack();
              
                $retorno = false;
            }
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function alteraLancamento() {
        
            $query = "UPDATE TTICKETSLAN SET 
                      QTD_FICHAS = ?, 
                      VAL_APURADO = (? * VAL_FICHA),
                      DT_ALT = ?
                      WHERE TICKETLAN_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getQtdFichas(), PDO::PARAM_STR);
            $sql->bindValue(2, $this->getQtdFichas(), PDO::PARAM_STR);
            $sql->bindValue(3, $this->getDtAlt(), PDO::PARAM_STR);
            $sql->bindValue(4, $this->getTicketlanId(), PDO::PARAM_INT);
            
            $retorno = $sql->execute();
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function excluiLancamento() {
        
            $query = "DELETE FROM TTICKETSLAN
                      WHERE TICKETLAN_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getTicketlanId(), PDO::PARAM_INT);
            
            $retorno = $sql->execute();
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function buscaLancamento() {
        
            $query = "SELECT * FROM TTICKETSLAN
                      WHERE TICKETLAN_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getTicketlanId(), PDO::PARAM_INT);
            
            $retorno = $sql->fetch(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
      
        public function listarTodos($ticket_id) {
        
            $query = "SELECT
                        TTICKETSLAN.TICKETLAN_ID,
                        TTICKETSLAN.TICKET_ID,
                        TTICKETSLAN.TIPOLAN,
                        CASE TTICKETSLAN.TIPOLAN
                          WHEN 'C' THEN 'Compra'
                          WHEN 'D' THEN 'Devolução'
                        END AS DSC_TIPOLAN,
                        TTICKETSLAN.FICHA_ID,
                        TTICKETSLAN.QTD_FICHAS,
                        TTICKETSLAN.VAL_FICHA,
                        TTICKETSLAN.VAL_APURADO,
                        TFICHAS.NOME AS NOME_FICHA,
                        TFICHAS.COR AS COR_FICHA
                      FROM TTICKETSLAN
                      JOIN TFICHAS ON (TFICHAS.FICHA_ID = TTICKETSLAN.FICHA_ID)
                      WHERE TTICKETSLAN.TICKET_ID = ?
                      ORDER BY TICKETLAN_ID";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $ticket_id, PDO::PARAM_INT);
            
            $sql->execute();
            
            $retorno = $sql->fetchAll(PDO::FETCH_ASSOC);            
            
            Dao::fechaConexao();
            
            return $retorno;
        }
      
    }

?>