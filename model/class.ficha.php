<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    include_once('class.dao.php');
    
    class FichaModel {
    
        private $ficha_id;
        private $nome;
        private $cor;
        private $valor;
        
        public function getFichaId() {
            return $this->ficha_id;
        }
        public function getNome() {
            return $this->nome;
        }
        public function getCor() {
            return $this->cor;
        }
        public function getValor() {
            return $this->valor;
        }
        
        public function setFichaId($ficha_id) {
            $this->ficha_id = $ficha_id;
        }
        public function setNome($nome) {
            $this->nome = $nome;
        }
        public function setCor($cor) {
            $this->cor = $cor;
        }
        public function setValor($valor) {
            $this->valor = $valor;
        }
        
        public function incluiFicha() {
        
            try {
        
                $query = "INSERT INTO TFICHAS(NOME, COR, VALOR)
                          VALUES(?, ?, ?)";
                               
                $db = Dao::abreConexao();

                $sql = $db->prepare($query);

                $sql->bindValue(1, $this->getNome(), PDO::PARAM_STR);
                $sql->bindValue(2, $this->getCor(), PDO::PARAM_STR);
                $sql->bindValue(3, $this->getValor(), PDO::PARAM_STR);

                $retorno = $sql->execute();

                $this->setFichaId($db->lastInsertId());
            
            } catch(PDOException $e) {
            
                //echo $e->getMessage();
            
                $retorno = false;
            }
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function alteraFicha() {
        
            $query = "UPDATE TFICHAS SET
                      NOME = ?,
                      COR = ?,
                      VALOR = ?
                      WHERE FICHA_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getNome(), PDO::PARAM_STR);
            $sql->bindValue(2, $this->getCor(), PDO::PARAM_STR);
            $sql->bindValue(3, $this->getValor(), PDO::PARAM_STR);
            $sql->bindValue(4, $this->getFichaId(), PDO::PARAM_INT);
            
            $retorno = $sql->execute();
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function excluiFicha() {
        
            try {
        
                $query = "DELETE FROM TFICHAS
                          WHERE FICHA_ID = ?";
                               
                $sql = Dao::abreConexao()->prepare($query);
                
                $sql->bindValue(1, $this->getFichaId(), PDO::PARAM_INT);
                
                $retorno = $sql->execute();
                
            } catch(PDOException $e) {
            
                //echo $e->getMessage();
            
                $retorno = false;
            }
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function buscaFicha() {
        
            $query = "SELECT * FROM TFICHAS
                      WHERE FICHA_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getFichaId(), PDO::PARAM_INT);
            
            $sql->execute();
            
            $retorno = $sql->fetch(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function listarTodos() {
        
            $query = "SELECT * FROM TFICHAS
                      ORDER BY VALOR";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->execute();
                     
            $retorno = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
    
    }

?>