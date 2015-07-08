<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    include_once('class.dao.php');
    
    class TicketsModel {
    
        private $ticket_id;
        private $baixado;
        private $jogo_id;
        private $jogador_id;
        
        public function getTicketId() {
            return $this->ticket_id;
        }
        public function getBaixado() {
            return $this->baixado;
        }
        public function getJogoId() {
            return $this->jogo_id;
        }
        public function getJogadorId() {
            return $this->jogador_id;
        }
        
        public function setTicketId($ticket_id) {
            $this->ticket_id = $ticket_id;
        }
        public function setBaixado($baixado) {
            $this->baixado = $baixado;
        }
        public function setJogoId($jogo_id) {
            $this->jogo_id = $jogo_id;
        }
        public function setJogadorId($jogador_id) {
            $this->jogador_id = $jogador_id;
        }
        
        
        public function buscaTicket() {
        
            $query = "SELECT * FROM TTICKETS
                      WHERE TICKET_ID = ?";
                      
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getTicketId(), PDO::PARAM_INT);
            
            $sql->execute();
            
            $retorno = $sql->fetch(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function listarTodos($jogo_id) {
        
            $query = "SELECT 
                        TTICKETS.TICKET_ID,
                        TTICKETS.BAIXADO,
                        TTICKETS.JOGADOR_ID,
                        TJOGADORES.NOME AS JOGADOR,
                        (SELECT COUNT(*) FROM TTICKETSLAN WHERE TTICKETSLAN.TICKET_ID = TTICKETS.TICKET_ID) AS EXISTE_LAN
                      FROM TTICKETS
                      JOIN TJOGADORES ON (TJOGADORES.JOGADOR_ID = TTICKETS.JOGADOR_ID)
                      WHERE TTICKETS.JOGO_ID = ?
                      ORDER BY TJOGADORES.NOME";
                      
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $jogo_id, PDO::PARAM_INT);
            
            $sql->execute();
            
            $retorno = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        private function getVlrCompras() {
        
            $query = "select coalesce(sum(b.val_apurado), 0) as saldo
                      from tticketslan b
                      where b.ticket_id = ?
                      and b.tipolan = 'C'";
                      
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getTicketId(), PDO::PARAM_INT);
            
            $sql->execute();
            
            $retorno = $sql->fetch(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno['saldo'];
        }
        
        private function getVlrDevolucao() {
        
            $query = "select coalesce(sum(b.val_apurado), 0) as saldo
                      from tticketslan b
                      where b.ticket_id = ?
                      and b.tipolan = 'D'";
                      
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getTicketId(), PDO::PARAM_INT);
            
            $sql->execute();
            
            $retorno = $sql->fetch(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno['saldo'];
        }
        
        public function getSaldoFechamento() {
        
            $vr_deb   = $this->getVlrCompras();
            $vr_cred  = $this->getVlrDevolucao();            
            $vr_saldo = ($vr_cred - $vr_deb);
        
            $retorno['VR_DEB']       = $vr_deb;
            $retorno['VR_CRED']      = $vr_cred;
            $retorno['VR_CALCULADO'] = $vr_saldo;
            
            return $retorno;
        }
        
        public function finalizaTicket($vr_acerto, $vr_troco) {
        
            $saldos = $this->getSaldoFechamento();
            
            $vr_deb   = $saldos['VR_DEB'];
            $vr_cred  = $saldos['VR_CRED'];
            $vr_saldo = $saldos['VR_CALCULADO'];
            
            $db = Dao::abreConexao();
            
            $db->beginTransaction();
            
            try {
                //insere o Caixa
                $query = "INSERT INTO TCAIXA(TICKET_ID, VR_DEB, VR_CRED, VR_CALCULADO, VR_ACERTO, VR_TROCO)
                          VALUES(?, ?, ?, ?, ?, ?)";
                          
                $sql = $db->prepare($query);
                
                $sql->bindValue(1, $this->getTicketId(), PDO::PARAM_INT);
                $sql->bindValue(2, $vr_deb, PDO::PARAM_STR);
                $sql->bindValue(3, $vr_cred, PDO::PARAM_STR);
                $sql->bindValue(4, $vr_saldo, PDO::PARAM_STR);
                $sql->bindValue(5, $vr_acerto, PDO::PARAM_STR);
                $sql->bindValue(6, $vr_troco, PDO::PARAM_STR);
                
                $sql->execute();
                
                //baixa o Ticket   
                $query = "UPDATE TTICKETS 
                          SET BAIXADO = 1
                          WHERE TICKET_ID = ?";
                          
                $sql = $db->prepare($query);
                
                $sql->bindValue(1, $this->getTicketId(), PDO::PARAM_INT);
                
                $sql->execute();
                
                //finaliza o Jogo
                $query = "UPDATE TJOGOS
                          SET FINALIZADO = (case when (select count(*) from ttickets where baixado = 0 and jogo_id = ?) = 0 then 1 else 0 end)
                          WHERE JOGO_ID = ?";
                          
                $sql = $db->prepare($query);
                
                $sql->bindValue(1, $this->getJogoId(), PDO::PARAM_INT);
                $sql->bindValue(2, $this->getJogoId(), PDO::PARAM_INT);
                
                $sql->execute();
                
            
                $retorno = $db->commit();
            
            } catch(PDOException $e) {
            
                //echo $e->getMessage();
                
                $db->rollBack();
              
                $retorno = false;
            }
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function getTotCompraTicket() {
            
            $query = "SELECT SUM(VAL_APURADO) AS TOT_COMPRA
                      FROM TTICKETSLAN 
                      WHERE TIPOLAN = 'C' 
                      AND TICKET_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getTicketId(), PDO::PARAM_INT);
            
            $sql->execute();
            
            $retorno = $sql->fetch(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function getTotDevolucaoTicket() {
            
            $query = "SELECT SUM(VAL_APURADO) AS TOT_DEVOLUCAO
                      FROM TTICKETSLAN 
                      WHERE TIPOLAN = 'D' 
                      AND TICKET_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getTicketId(), PDO::PARAM_INT);
            
            $sql->execute();
            
            $retorno = $sql->fetch(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
      
    }

?>