<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    class Jogo extends JogoModel {
        
        public function __construct() {
        
            date_default_timezone_set('America/Sao_Paulo');
        }


        public function incluir() {
          
            $data = strip_tags(trim($_POST["dt_jogo"]));
          
            $this->setDtJogo(implode("-",array_reverse(explode("/",$data))));
            $this->setLocalId(strip_tags(trim($_POST["local_id"])));
            $this->setObs(strip_tags(trim($_POST["obs"])));
            
            $jogadores_id = isset($_POST["jogador_id"]) ? $_POST["jogador_id"] : array();
            
            $jogadores_id = array_filter($jogadores_id);  //remove os elementos vazios (caso haja)

            return $this->incluiJogo($jogadores_id);
        }
        
        public function alterar() {

            $data = strip_tags(trim($_POST["dt_jogo"]));
          
            $this->setDtJogo(implode("-",array_reverse(explode("/",$data))));
            $this->setLocalId(strip_tags(trim($_POST["local_id"])));
            $this->setObs(strip_tags(trim($_POST["obs"])));
            
            $tickets_id   = isset($_POST["ticket_id"]) ? $_POST["ticket_id"] : array();
            $jogadores_id = isset($_POST["jogador_id"]) ? $_POST["jogador_id"] : array();
            
            $tickets_id   = array_filter($tickets_id);    //remove os elementos vazios (caso haja)
            $jogadores_id = array_filter($jogadores_id);  //remove os elementos vazios (caso haja)

            return $this->alteraJogo($tickets_id, $jogadores_id);
        }
        
        public function excluir($jogo_id) {

            $this->setJogoId($jogo_id);

            return $this->excluiJogo();
        }
        
        public function buscar($jogo_id) {

            $this->setJogoId($jogo_id);

            $ret_consulta = $this->buscaJogo();
            
            $this->setDtJogo($ret_consulta['DT_JOGO']);
            $this->setLocalId($ret_consulta['LOCAL_ID']);
            $this->setObs($ret_consulta['OBS']);
            $this->setFinalizado($ret_consulta['FINALIZADO']);
            
            return ($ret_consulta['JOGO_ID'] == $jogo_id);
        }
        
        public function pesquisar() {
          
            if (!isset($_SESSION))
               session_start();
          
            $dt_ini = (isset($_POST["data_ini"]) ? $_POST["data_ini"] : (isset($_SESSION["jg_busca1"]) ? $_SESSION["jg_busca1"] : ""));
            $dt_fin = (isset($_POST["data_fin"]) ? $_POST["data_fin"] : (isset($_SESSION["jg_busca2"]) ? $_SESSION["jg_busca2"] : ""));
            $status = (isset($_POST["status"]) ? $_POST["status"] : (isset($_SESSION["jg_busca3"]) ? $_SESSION["jg_busca3"] : ""));

            $_SESSION["jg_busca1"] = $dt_ini;
            $_SESSION["jg_busca2"] = $dt_fin;
            $_SESSION["jg_busca3"] = $status;
            
            $dt_ini = implode("-",array_reverse(explode("/",$dt_ini)));
            $dt_fin = implode("-",array_reverse(explode("/",$dt_fin)));
          
            return $this->pesquisaJogos($dt_ini, $dt_fin, $status);
        }
        
    }

?>