<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    include_once('class.dao.php');

    class LocalModel {
    
        private $local_id;
        private $dsc_local;
        
        public function __construct(){}
        
        public function getLocalId() {
            return $this->local_id;
        }        
        public function getDscLocal() {
            return $this->dsc_local;
        }
        
        public function setLocalId($local_id) {
            $this->local_id = $local_id;
        }        
        public function setDscLocal($dsc_local) {
            $this->dsc_local = $dsc_local;
        }

        
        public function incluiLocal() {
        
            try {
        
                $query = "INSERT INTO TLOCAIS(DSC_LOCAL)
                          VALUES(?)";
                               
                $db = Dao::abreConexao();

                $sql = $db->prepare($query);

                $sql->bindValue(1, $this->getDscLocal(), PDO::PARAM_STR);
                
                $retorno = $sql->execute();
                
                $this->setLocalId($db->lastInsertId());
            
            } catch(PDOException $e) {
            
                //echo $e->getMessage();
            
                $retorno = false;
            }
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function alteraLocal() {
        
            $query = "UPDATE TLOCAIS SET
                      DSC_LOCAL = ?
                      WHERE LOCAL_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getDscLocal(), PDO::PARAM_STR);
            $sql->bindValue(2, $this->getLocalId(), PDO::PARAM_INT);
            
            $retorno = $sql->execute();
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function excluiLocal() {
        
            try {
        
                $query = "DELETE FROM TLOCAIS
                          WHERE LOCAL_ID = ?";
                               
                $sql = Dao::abreConexao()->prepare($query);
                
                $sql->bindValue(1, $this->getLocalId(), PDO::PARAM_INT);
                
                $retorno = $sql->execute();
            
            } catch(PDOException $e) {
            
                //echo $e->getMessage();
            
                $retorno = false;
            }
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function buscaLocal() {
        
            $query = "SELECT * FROM TLOCAIS
                      WHERE LOCAL_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getLocalId(), PDO::PARAM_INT);
            
            $sql->execute();
            
            $retorno = $sql->fetch(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function listarTodos() {
        
            $query = "SELECT * FROM TLOCAIS ORDER BY DSC_LOCAL";

            $sql = Dao::abreConexao()->prepare($query);

            $sql->execute();
                     
            $retorno = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
    
    }

?>