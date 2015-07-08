<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    include_once('class.dao.php');
    
    class JogoModel {
        
        private $jogo_id;
        private $dt_jogo;
        private $local_id;
        private $obs;
        private $finalizado;
        
        public function getJogoId() {
            return $this->jogo_id;
        }
        public function getDtJogo() {
            return $this->dt_jogo;
        }
        public function getLocalId() {
            return $this->local_id;
        }
        public function getObs() {
            return $this->obs;
        }
        public function getFinalizado() {
            return $this->finalizado;
        }
        
        public function setJogoId($jogo_id) {
            $this->jogo_id = $jogo_id;
        }
        public function setDtJogo($dt_jogo) {
            $this->dt_jogo = $dt_jogo;
        }
        public function setLocalId($local_id) {
            $this->local_id = $local_id;
        }
        public function setObs($obs) {
            $this->obs = $obs;
        }
        public function setFinalizado($finalizado) {
            $this->finalizado = $finalizado;
        }
        
        public function incluiJogo($jogadores_id) {
        
            $db = Dao::abreConexao();
            
            $db->beginTransaction();
            
            try {
            
                $query = "INSERT INTO TJOGOS(DT_JOGO, LOCAL_ID, OBS)
                          VALUES(?, ?, ?)";
                
                $sql = $db->prepare($query);
                
                $sql->bindValue(1, $this->getDtJogo(), PDO::PARAM_STR);
                $sql->bindValue(2, $this->getLocalId(), PDO::PARAM_INT);
                $sql->bindValue(3, $this->getObs(), PDO::PARAM_STR);
                
                $sql->execute();
                
                $this->setJogoId($db->lastInsertId());
                
                foreach($jogadores_id as $jogador_id) {
                
                    $query = "INSERT INTO TTICKETS(JOGO_ID, JOGADOR_ID)
                              VALUES(?, ?)";
                    
                    $sql = $db->prepare($query);
                    
                    $sql->bindValue(1, $this->getJogoId(), PDO::PARAM_INT);
                    $sql->bindValue(2, $jogador_id, PDO::PARAM_INT);
                   
                    $sql->execute();
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
        
        public function alteraJogo($tickets_id, $jogadores_id) {
        
            $id_tickets = trim(implode(',', $tickets_id));  //array to string
        
            $db = Dao::abreConexao();
            
            $db->beginTransaction();
            
            try {
            
                $query = "UPDATE TJOGOS SET
                          DT_JOGO = ?,
                          LOCAL_ID = ?,
                          OBS = ?
                          WHERE JOGO_ID = ?";
                               
                $sql = $db->prepare($query);
                
                $sql->bindValue(1, $this->getDtJogo(), PDO::PARAM_STR);
                $sql->bindValue(2, $this->getLocalId(), PDO::PARAM_INT);
                $sql->bindValue(3, $this->getObs(), PDO::PARAM_STR);
                $sql->bindValue(4, $this->getJogoId(), PDO::PARAM_INT);
                
                $sql->execute();
                              
                $query = "DELETE FROM TTICKETS
                          WHERE JOGO_ID = ?";
                        
                if ($id_tickets != '')
                    $query .= "AND TICKET_ID NOT IN ($id_tickets)";
                    
                $sql = $db->prepare($query);
                
                $sql->bindValue(1, $this->getJogoId(), PDO::PARAM_INT);
                
                $sql->execute();
                
                foreach($jogadores_id as $jogador_id) {
                    
                    //IGNORE: não insere caso já exista o registro (devido à unique key na tabela)
                    $query = "INSERT IGNORE INTO TTICKETS(JOGO_ID, JOGADOR_ID)
                              VALUES(?, ?)";
                            
                    $sql = $db->prepare($query);
                    
                    $sql->bindValue(1, $this->getJogoId(), PDO::PARAM_INT);
                    $sql->bindValue(2, $jogador_id, PDO::PARAM_INT);
                    
                    $sql->execute();
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
        
        public function excluiJogo() {
        
            $query = "DELETE FROM TJOGOS
                      WHERE JOGO_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);

            $sql->bindValue(1, $this->getJogoId(), PDO::PARAM_INT);
            
            $retorno = $sql->execute();
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function buscaJogo() {
        
            $query = "SELECT * FROM TJOGOS
                      WHERE JOGO_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getJogoId(), PDO::PARAM_INT);
            
            $sql->execute();
            
            $retorno = $sql->fetch(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function pesquisaJogos($dt_ini, $dt_fin, $status) {
        
            $query = "SELECT 
                        TJOGOS.JOGO_ID,
                        TJOGOS.DT_JOGO,
                        TJOGOS.LOCAL_ID,
                        TJOGOS.FINALIZADO,
                        TLOCAIS.DSC_LOCAL
                      FROM TJOGOS
                      LEFT JOIN TLOCAIS ON (TLOCAIS.LOCAL_ID = TJOGOS.LOCAL_ID)
                      WHERE TJOGOS.DT_JOGO BETWEEN ? AND ?
                      AND TJOGOS.FINALIZADO = CASE ?
                                                WHEN 'S' THEN 1
                                                WHEN 'N' THEN 0
                                                ELSE FINALIZADO
                                              END
                      ORDER BY TJOGOS.DT_JOGO";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $dt_ini, PDO::PARAM_STR);
            $sql->bindValue(2, $dt_fin, PDO::PARAM_STR);
            $sql->bindValue(3, $status, PDO::PARAM_STR);
            
            $sql->execute();
            
            $retorno = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
      
    }

?>