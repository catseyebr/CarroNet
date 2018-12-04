<?php
    require_once WS_PATH . 'xml_handler.php';
    
    Final Class XML_Handler_Cielo extends XML_Handler {
        
        public function __construct($received_data, $request_data) {
            $this->_haw_received_data = $received_data;
            $this->_haw_request_data  = $request_data;
            $request = simplexml_load_string($this->_haw_request_data);
            $received = simplexml_load_string($this->_haw_received_data);
            if ($received !== NULL) {
                if ($received->getName() == 'erro') {
                    $this->parseError($received);
                } else {
                    if ($received->getName() == 'transacao') {
                        $this->_type = 'transacao';
                        $this->parseResposta($request, $received);
                    } else {
                        $this->_is_ok = 0;
                    }
                }
            } else {
                $this->_is_ok = 2;
            }
        }
        
        private function parseResposta($request, $received) {
            $this->_is_ok = 2;
            $dados = get_object_vars($received);
            $autorizacao['tid'] = $dados['tid'];
            $autorizacao['pan'] = $dados['pan'];
            $dadosPedido = get_object_vars($dados['dados-pedido']);
            $autorizacao['pedido'] = array(
                'numero' => $dadosPedido['numero'],
                'valor' => $dadosPedido['valor'],
                'moeda' => $dadosPedido['moeda'],
                'data-hora' => $dadosPedido['data-hora'],
                'idioma' => $dadosPedido['idioma'],
                'taxa-embarque' => $dadosPedido['taxa-embarque']
            );
            $formaPagamento = get_object_vars($dados['forma-pagamento']);
            $autorizacao['forma-pagamento'] = array(
                'bandeira' => $formaPagamento['bandeira'],
                'produto' => $formaPagamento['produto'],
                'parcelas' => $formaPagamento['parcelas'],
            );
            $autorizacao['status'] = $dados['status'];
            $autenti = get_object_vars($dados['autenticacao']);
            $autori = get_object_vars($dados['autorizacao']);
            $captura = get_object_vars($dados['captura']);
            if(isset($dados['cancelamentos'])){
                $cancels = get_object_vars($dados['cancelamentos']);
                foreach ($cancels['cancelamento'] as $canc) {
                    $can = get_object_vars($canc);
                    $autorizacao['cancelamento'][] = array(
                        'codigo' => $can['codigo'],
                        'mensagem' => $can['mensagem'],
                        'data-hora' => $can['data-hora'],
                        'valor' => $can['valor'],
                    );
                }
            }
            $autorizacao['autenticacao'] = array(
                'codigo' => $autenti['codigo'],
                'mensagem' => $autenti['mensagem'],
                'data-hora' => $autenti['data-hora'],
                'valor' => $autenti['valor'],
                'eci' => $autenti['eci']
            );
            $autorizacao['autorizacao'] = array(
                'codigo' => $autori['codigo'],
                'mensagem' => $autori['mensagem'],
                'data-hora' => $autori['data-hora'],
                'valor' => $autori['valor'],
                'lr' => $autori['lr'],
                'arp' => $autori['arp'],
                'nsu' => $autori['nsu']
            );
            $autorizacao['captura'] = array(
                'codigo' => $captura['codigo'],
                'mensagem' => $captura['mensagem'],
                'data-hora' => $captura['data-hora'],
                'valor' => $captura['valor']
            );
            $this->_arr_dados['transacao'] = $autorizacao;
        }
        
        private function parseError($received) {
            $this->_is_ok = 0;
            $dados = get_object_vars($received);
            $erros = array(
                'codigo' => $dados['codigo'],
                'mensagem' => $dados['mensagem']
            );
            $this->_arr_dados['erro'] = $erros;
            $this->_vis_errorTrack = $dados['mensagem'];
        }
    }