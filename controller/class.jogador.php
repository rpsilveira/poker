<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    class Jogador extends JogadorModel {
        
        public function __construct() {
        
            date_default_timezone_set('America/Sao_Paulo');
        }


        public function incluir() {

            $this->setNome(strip_tags(trim($_POST["nome"])));
            $this->setBloqueado( isset($_POST["bloqueado"]) ? 1 : 0 );
            $this->setDtInc(date("Y-m-d H:i:s"));
            $this->setEmail(strip_tags(trim($_POST["email"])));

            return $this->incluiJogador();
        }
        
        public function alterar() {

            $this->setNome(strip_tags(trim($_POST["nome"])));
            $this->setBloqueado( isset($_POST["bloqueado"]) ? 1 : 0 );
            $this->setDtInc(date("Y-m-d H:i:s"));
            $this->setEmail(strip_tags(trim($_POST["email"])));

            return $this->alteraJogador();
        }
        
        public function excluir($jogador_id) {

            $this->setJogadorId($jogador_id);

            return $this->excluiJogador();
        }
        
        public function buscar($jogador_id) {

            $this->setJogadorId($jogador_id);

            $ret_consulta = $this->buscaJogador();
            
            $this->setNome($ret_consulta['NOME']);
            $this->setBloqueado($ret_consulta['BLOQUEADO']);
            $this->setDtInc($ret_consulta['DT_INC']);
            $this->setEmail($ret_consulta['EMAIL']);
            
            return ($ret_consulta['JOGADOR_ID'] == $jogador_id);
        }
        
        public function listar() {
          
            return $this->listarTodos();
        }
        
        public function listarAtivos() {
          
            return $this->listarTodosAtivos();
        }
        
    }

?>