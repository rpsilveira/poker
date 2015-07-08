<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    include_once('class.dao.php');

    class UsuarioModel {
      
        private $usr_id;
        private $usr_nome;
        private $usr_login;
        private $usr_senha;
        private $bloqueado;
        
        public function __construct(){}
        
        public function getUsrId() {
            return $this->usr_id;
        }        
        public function getUsrNome() {
            return $this->usr_nome;
        }
        public function getUsrLogin() {
            return $this->usr_login;
        }
        public function getUsrSenha() {
            return $this->usr_senha;
        }
        public function getBloqueado() {
            return $this->bloqueado;
        }
        
        public function setUsrId($usr_id) {
            $this->usr_id = $usr_id;
        }        
        public function setUsrNome($usr_nome) {
            $this->usr_nome = $usr_nome;
        }
        public function setUsrLogin($usr_login) {
            $this->usr_login = $usr_login;
        }
        public function setUsrSenha($usr_senha) {
            $this->usr_senha = $usr_senha;
        }
        public function setBloqueado($bloqueado) {
            $this->bloqueado = $bloqueado;
        }
        
        
        public function incluiUsuario() {
        
            try {
        
                $query = "INSERT INTO TUSUARIOS(USR_NOME, USR_LOGIN, USR_SENHA, BLOQUEADO)
                          VALUES(?, ?, ?, ?)";
                               
                $db = Dao::abreConexao();

                $sql = $db->prepare($query);

                $sql->bindValue(1, $this->getUsrNome(), PDO::PARAM_STR);
                $sql->bindValue(2, $this->getUsrLogin(), PDO::PARAM_STR);
                $sql->bindValue(3, $this->getUsrSenha(), PDO::PARAM_STR);
                $sql->bindValue(4, $this->getBloqueado(), PDO::PARAM_INT);
                
                $retorno = $sql->execute();
                
                $this->setUsrId($db->lastInsertId());
            
            } catch(PDOException $e) {
            
                //echo $e->getMessage();
            
                $retorno = false;
            }
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function alteraUsuario() {
        
            if ($this->getUsrSenha() != '') {
        
                $query = "UPDATE TUSUARIOS SET
                          USR_NOME = ?,
                          USR_LOGIN = ?,
                          USR_SENHA = ?,
                          BLOQUEADO = ?,
                          WHERE USR_ID = ?";
                          
                $sql = Dao::abreConexao()->prepare($query);
                
                $sql->bindValue(1, $this->getUsrNome(), PDO::PARAM_STR);
                $sql->bindValue(2, $this->getUsrLogin(), PDO::PARAM_STR);
                $sql->bindValue(3, $this->getUsrSenha(), PDO::PARAM_STR);
                $sql->bindValue(4, $this->getBloqueado(), PDO::PARAM_INT);
                $sql->bindValue(5, $this->getUsrId(), PDO::PARAM_INT);
                
                $retorno = $sql->execute();
            }
            else {
                $query = "UPDATE TUSUARIOS SET
                          USR_NOME = ?,
                          USR_LOGIN = ?,
                          BLOQUEADO = ?
                          WHERE USR_ID = ?";
                        
                $sql = Dao::abreConexao()->prepare($query);
                
                $sql->bindValue(1, $this->getUsrNome(), PDO::PARAM_STR);
                $sql->bindValue(2, $this->getUsrLogin(), PDO::PARAM_STR);
                $sql->bindValue(3, $this->getBloqueado(), PDO::PARAM_INT);
                $sql->bindValue(4, $this->getUsrId(), PDO::PARAM_INT);
                
                $retorno = $sql->execute();
            }
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function excluiUsuario() {
        
            try {
        
                $query = "DELETE FROM TUSUARIOS
                          WHERE USR_ID = ?";
                               
                $sql = Dao::abreConexao()->prepare($query);
                
                $sql->bindValue(1, $this->getUsrId(), PDO::PARAM_INT);
                
                $retorno = $sql->execute();
            
            } catch(PDOException $e) {
            
                //echo $e->getMessage();
            
                $retorno = false;
            }
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function buscaUsuario() {
        
            $query = "SELECT * FROM TUSUARIOS
                      WHERE USR_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getUsrId(), PDO::PARAM_INT);
            
            $sql->execute();
            
            $retorno = $sql->fetch(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function validaLogin() {
        
            $query = "SELECT USR_ID 
                      FROM TUSUARIOS
                      WHERE BLOQUEADO = 0
                      AND USR_LOGIN = ?
                      AND USR_SENHA = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getUsrLogin(), PDO::PARAM_STR);
            $sql->bindValue(2, $this->getUsrSenha(), PDO::PARAM_STR);
            
            $sql->execute();
            
            $dados_login = $sql->fetch(PDO::FETCH_ASSOC);
			
            if ($dados_login)
                $retorno = $dados_login["USR_ID"];
            else
                $retorno = 0;
			
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function validaSenhaAtual() {
        
            $query = "SELECT USR_ID 
                      FROM TUSUARIOS
                      WHERE USR_ID = ?
                      AND USR_SENHA = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getUsrId(), PDO::PARAM_INT);
            $sql->bindValue(2, $this->getUsrSenha(), PDO::PARAM_STR);
            
            $sql->execute();
            
            $dados_login = $sql->fetch(PDO::FETCH_ASSOC);
			
            if ($dados_login)
                $retorno = $dados_login["USR_ID"];
            else
                $retorno = 0;
			
            Dao::fechaConexao();
            
            return ($retorno > 0);
        }
        
        public function listarTodos() {
        
            $query = "SELECT
                        USR_ID,
                        USR_NOME,
                        USR_LOGIN,
                        USR_SENHA,
                        BLOQUEADO
                      FROM TUSUARIOS
                      ORDER BY USR_NOME";
                      
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->execute();
            
            $retorno = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            Dao::fechaConexao();
            
            return $retorno;
        }
        
        public function alteraSenhaUsuario() {
        
            $query = "UPDATE TUSUARIOS SET
                      USR_SENHA = ?
                      WHERE USR_ID = ?";
                           
            $sql = Dao::abreConexao()->prepare($query);
            
            $sql->bindValue(1, $this->getUsrSenha(), PDO::PARAM_STR);
            $sql->bindValue(2, $this->getUsrId(), PDO::PARAM_INT);
            
            $retorno = $sql->execute();
            
            Dao::fechaConexao();
            
            return $retorno;
        }
    
    }

?>