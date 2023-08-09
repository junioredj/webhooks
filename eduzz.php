<?php

    require_once "autoload.php";

    $json = json_decode(file_get_contents('eduzz.json'));
    
    
    


    //Códigos para o campo "trans_status"
    /*
    1	Aberta	Fatura aberta, cliente gerou boleto, mas ainda não foi compensado
    3	Paga	Compra foi paga, o cliente já esta apto a receber o produto
    4	Cancelada	Fatura Cancelada pela Eduzz
    6	Aguardando Reembolso	Cliente solicitou reembolso, porem o mesmo ainda não foi efetuado
    7	Reembolsado	Cliente já foi reembolsado pela eduzz
    9	Duplicada	Cliente tentou comprar mais de uma vez o mesmo produto, a segunda fatura fica como duplicada e não é cobrada.
    10	Expirada	A fatura que fica mais de 15 dias aberta é alterada para expirada.
    11	Em Recuperacao	Fatura entrou para o processo de recuperação
    15	Aguardando Pagamento	Faturas de recorrência após o vencimento ficam com o status aguardando pagamento

    Campo: "item_product_chargetype"

    SIGLA	Tipo
    N	Cobrança única
    A	Assinatura
    L	Outros
    G	Gratuita (Valor R$ 0,00)
    */



    if($json->api_key == "s044o56d2n")//Verificação da API KEY encontrada no "https://orbita.eduzz.com/producer/config-api"
    {
        if($json->trans_status == 3)//Verifica se a fatura foi paga
        {
            $product_key = $json->pro_cod;//Código do produto
            $trans_key = $json->trans_key;//Código da transação
            $code_transaction = $json->recurrence_cod;//Id do contrato
            $product_name = $json->product_name;//Nome do produto comprado
            $client_name = $json->cus_name;//Nome do cliente
            $client_email = $json->cus_email;//E-mail do cliente
            $client_phone = $json->cus_cel;//Celular do cliente
            $date_payment = date_format(date_create_from_format("Ymd", $json->trans_paiddate), "Y-m-d H:i:s");//Data do pagamento
            $recurrence_interval_type = $json->recurrence_interval_type;//Intervalo da recorrencia
            $plan_name = $json->recurrence_plan;//Nome do plano da recorrencia
            $canceled_recurrence = $json->recurrence_canceled_at;//Caso a assinatura esteja cancelada indica a data de cancelamento
            $expiration_trans = date("Y-m-d H:i:s");



            if($json->trans_items[0]->item_product_chargetype == "A")//Assinatura
            {
                

                if(strtolower($recurrence_interval_type) == "month")
                {
                    $expiration_trans = date('Y-m-d H:i:s', strtotime("+".$json->recurrence_interval." months", strtotime($date_payment)));  
                }
                else if(strtolower($recurrence_interval_type) == "day")
                {
                    $expiration_trans = date('Y-m-d H:i:s', strtotime("+".$json->recurrence_interval." days", strtotime($date_payment)));  
                }
                else if(strtolower($recurrence_interval_type) == "year")
                {
                    $expiration_trans = date('Y-m-d H:i:s', strtotime("+".$json->recurrence_interval." years", strtotime($date_payment)));  
                }
                
            }
            else if($json->trans_items[0]->item_product_chargetype == "N")//Cobrança única
            {
                $expiration_trans = date('Y-m-d H:i:s', strtotime("+1000 years", strtotime($date_payment)));  
            }


            //Verifica se uma assintura ja existe para poder atualizar a antiga
            if(Webhook::assinaturaExistente($code_transaction))
            {
                $webhook = new Webhook($product_key, $trans_key, $code_transaction,$product_name,$client_name,$client_email,$client_phone,$date_payment,$recurrence_interval_type,$plan_name,$canceled_recurrence,$expiration_trans);
                echo json_encode($webhook->updateTransacao());
                
            }
            else
            {
                //Cadastra um novo registro
                $webhook = new Webhook($product_key, $trans_key, $code_transaction,$product_name,$client_name,$client_email,$client_phone,$date_payment,$recurrence_interval_type,$plan_name,$canceled_recurrence,$expiration_trans);
                echo json_encode($webhook->inserirTransacao());
            }


            
        }
        else if($json->trans_status == 4 || $json->trans_status == 7)//Verifica se a compra foi cancelada
        {
            $expiration_trans = date("Y-m-d H:i:s");
            $webhook = new Webhook($product_key, $trans_key, $code_transaction,$product_name,$client_name,$client_email,$client_phone,$date_payment,$recurrence_interval_type,$plan_name,$canceled_recurrence,$expiration_trans);
            echo json_encode($webhook->updateTransacao());
        }
    }

    


