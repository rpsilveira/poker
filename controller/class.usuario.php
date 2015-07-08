<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    class Usuario extends UsuarioModel {
        
        public function __construct() {
        
            date_default_timezone_set('America/Sao_Paulo');
        }


        public function incluir() {

            $this->setUsrNome(strip_tags(trim($_POST["nome"])));
            $this->setUsrLogin(strip_tags(trim($_POST["login"])));
            $this->setUsrSenha(md5($_POST["senha"]));
            $this->setBloqueado(isset($_POST["bloqueado"]) ? 1 : 0);

            return $this->incluiUsuario();
        }
        
        public function alterar() {

            $this->setUsrNome(strip_tags(trim($_POST["nome"])));
            $this->setUsrLogin(strip_tags(trim($_POST["login"])));
            $this->setUsrSenha( ($_POST["senha"] != '') ? md5($_POST["senha"]) : '');
            $this->setBloqueado(isset($_POST["bloqueado"]) ? 1 : 0);

            return $this->alteraUsuario();
        }
        
        public function excluir($usr_id) {

            $this->setUsrId($usr_id);

            return $this->excluiUsuario();
        }
        
        public function buscar($usr_id) {

            $this->setUsrId($usr_id);

            $ret_consulta = $this->buscaUsuario();
            
            $this->setUsrNome($ret_consulta['USR_NOME']);
            $this->setUsrLogin($ret_consulta['USR_LOGIN']);
            $this->setUsrSenha($ret_consulta['USR_SENHA']);
            $this->setBloqueado($ret_consulta['BLOQUEADO']);
            
            return ($ret_consulta['USR_ID'] == $usr_id);
        }
        
        public function login() {
        
            $this->setUsrLogin($_POST["login"]);
            $this->setUsrSenha(md5($_POST["senha"]));
            
            $id = $this->validaLogin();
			
            $retorno = ($id > 0);
            
            if ($retorno) {
            
                $this->buscar($id);
                
                if (!isset($_SESSION))
                   session_start();
              
                $_SESSION["usr_nome"] = $this->getUsrNome();
                $_SESSION["usr_id"]   = $id;

                //seta o tempo limite de inatividade
                $_SESSION["registro"] = time(); // armazena o momento em que foi autenticado
                $_SESSION["limite"] = 900;  //limite para encerrar a sessão por inatividade (segundos)
            }
            
            return $retorno;
        }
        
        public function listar() {
          
            return $this->listarTodos();
        }
        
        public function alteraSenha() {
        
            $this->setUsrId($_SESSION["usr_id"]);
            $this->setUsrSenha(md5($_POST["nova_senha"]));

            return $this->alteraSenhaUsuario();
        }
        
        public function validaSenha() {
        
            $this->setUsrId($_SESSION["usr_id"]);
            $this->setUsrSenha(md5($_POST["senha_atual"]));

            return $this->validaSenhaAtual();
        }        
        
    }

?>