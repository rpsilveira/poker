<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    include_once('class.dao.php');

    class JogadorModel {
    
        private $jogador_id;
        private $nome;
        private $bloqueado;
        private $dt_inc;
        private $email;
        
        public function __construct(){}
        
        public function getJogadorId() {
            return $this->jogador_id;
        }
        public function getNome() {
            return $this->nome;
        }
        public function getBloqueado() {
            return $this->bloqueado;
        }
        public function getDtInc() {
            return $this->dt_inc;
        }
        public function getEmail() {
            return $this->email;
        }
        
        public function setJogadorId($jogador_id) {
            $this->jogador_id = $jogador_id;
        }
        public function setNome($nome) {
            $this->nome = $nome;
        }
        public function setBloqueado($bloqueado) {
            $this->bloqueado = $bloqueado;
        }
        public function setDtInc($dt_inc) {
            $this->dt_inc = $dt_inc;
        }
        public function setEmail($email) {
            $this->email = $email;
        }
        
        
        public function incluiJogador() {
        
            try {
        
                $query = "INSERT INTO TJOGADORES(NOME, BLOQUEADO, DT_INC, EMAIL)
                          VALUES(?, ?, ?, ?)";
                               
                $db = Dao::abreConexao();

                $sql = $db->prepare($query);

                $sql->bindValue(1, $this->getNome(), PDO::PARAM_STR);
                $sql->bindValue(2, $this->getBloqueado(), PDO::PARAM_INT);
                $sql->bindValue(3, $this->getDtInc(), PDO::PARAM_STR);
                $sql->bindValue(4, $this->getEmail(), PDO::PARAM_STR);
                
                $retorno = $sql->execute();

                $this->setJogadorId($db->lastInsertId());
            
            } catch(PDOException $e) {
            
                //echo $e->getMessage();
            
                $retorno = false;
            }
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function alteraJogador() {
        
            $query = "UPDATE TJOGADORES SET
                      NOME = ?,
                      BLOQUEADO = ?,
                      EMAIL = ?
                      WHERE JOGADOR_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getNome(), PDO::PARAM_STR);
            $sql->bindValue(2, $this->getBloqueado(), PDO::PARAM_INT);
            $sql->bindValue(3, $this->getEmail(), PDO::PARAM_STR);
            $sql->bindValue(4, $this->getJogadorId(), PDO::PARAM_INT);
            
            $retorno = $sql->execute();
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function excluiJogador() {
        
            try {
        
                $query = "DELETE FROM TJOGADORES
                          WHERE JOGADOR_ID = ?";
                               
                $sql = Dao::abreConexao()->prepare($query);
                
                $sql->bindValue(1, $this->getJogadorId(), PDO::PARAM_INT);
                
                $retorno = $sql->execute();
            
            } catch(PDOException $e) {
            
                //echo $e->getMessage();
            
                $retorno = false;
            }
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function buscaJogador() {
        
            $query = "SELECT * FROM TJOGADORES
                      WHERE JOGADOR_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getJogadorId(), PDO::PARAM_INT);
            
            $sql->execute();
            
            $retorno = $sql->fetch(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function listarTodos() {
        
            $query = "SELECT * FROM TJOGADORES
                      ORDER BY NOME";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->execute();
            
            $retorno = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function listarTodosAtivos() {
        
            $query = "SELECT * FROM TJOGADORES
                      WHERE BLOQUEADO = 0
                      ORDER BY NOME";
                      
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->execute();
            
            $retorno = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
    
    }

?>