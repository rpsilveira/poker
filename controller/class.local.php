<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    class Local extends LocalModel {
        
        public function __construct() {
        
            date_default_timezone_set('America/Sao_Paulo');
        }


        public function incluir() {

            $this->setDscLocal(strip_tags(trim($_POST["dsc_local"])));

            return $this->incluiLocal();
        }
        
        public function alterar() {

            $this->setDscLocal(strip_tags(trim($_POST["dsc_local"])));

            return $this->alteraLocal();
        }
        
        public function excluir($local_id) {

            $this->setLocalId($local_id);

            return $this->excluiLocal();
        }
        
        public function buscar($local_id) {

            $this->setLocalId($local_id);

            $ret_consulta = $this->buscaLocal();
            
            $this->setDscLocal($ret_consulta['DSC_LOCAL']);
            
            return ($ret_consulta['LOCAL_ID'] == $local_id);
        }
        
        public function listar() {
          
            return $this->listarTodos();
        }
        
    }

?>