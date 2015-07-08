<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    class Ficha extends FichaModel {
    
        public function __construct() {
        
            date_default_timezone_set('America/Sao_Paulo');
        }


        public function incluir() {

            $this->setNome(strip_tags(trim($_POST["nome"])));
            $this->setCor(strip_tags(trim($_POST["cor"])));
            $this->setValor(str_replace(',', '.', strip_tags(trim($_POST["valor"]))));

            return $this->incluiFicha();
        }
        
        public function alterar() {

            $this->setNome(strip_tags(trim($_POST["nome"])));
            $this->setCor(strip_tags(trim($_POST["cor"])));
            $this->setValor(str_replace(',', '.', strip_tags(trim($_POST["valor"]))));

            return $this->alteraFicha();
        }
        
        public function excluir($ficha_id) {

            $this->setFichaId($ficha_id);

            return $this->excluiFicha();
        }
        
        public function buscar($ficha_id) {

            $this->setFichaId($ficha_id);

            $ret_consulta = $this->buscaFicha();
            
            $this->setNome($ret_consulta['NOME']);
            $this->setCor($ret_consulta['COR']);
            $this->setValor($ret_consulta['VALOR']);
            
            return ($ret_consulta['FICHA_ID'] == $ficha_id);
        }
        
        public function listar() {
          
            return $this->listarTodos();
        }
    
    }

?>