<?php
    class Webhook
    {
        private $product_key;//Código do produto
        private $trans_key;//Código da transação
        private $code_transaction;//Id do contrato
        private $product_name;//Nome do produto comprado
        private $client_name;//Nome do cliente
        private $client_email;//E-mail do cliente
        private $client_phone;//Celular do cliente
        private $date_payment;//Data do pagamento
        private $recurrence_interval_type;//Intervalo da recorrencia
        private $plan_name;//Nome do plano da recorrencia
        private $canceled_recurrence;//Caso a assinatura esteja cancelada indica a data de cancelamento
        private $expiration_trans;


        public function __construct(
         $product_key,//Código do produto
         $trans_key,//Código da transação
         $code_transaction,//Id do contrato
         $product_name,//Nome do produto comprado
         $client_name,//Nome do cliente
         $client_email,//E-mail do cliente
         $client_phone,//Celular do cliente
         $date_payment,//Data do pagamento
         $recurrence_interval_type,//Intervalo da recorrencia
         $plan_name,//Nome do plano da recorrencia
         $canceled_recurrence,//Caso a assinatura esteja cancelada indica a data de cancelamento
         $expiration_trans)
         {
            $this->product_key = $product_key;
            $this->trans_key = $trans_key;
            $this->code_transaction = $code_transaction;
            $this->product_name = $product_name;
            $this->client_name = $client_name;
            $this->client_email = $client_email;
            $this->client_phone = $client_phone;
            $this->date_payment = $date_payment;
            $this->recurrence_interval_type = $recurrence_interval_type;
            $this->plan_name = $plan_name;
            $this->canceled_recurrence = $canceled_recurrence;
            $this->expiration_trans = $expiration_trans;


            
         }

        public static function assinaturaExistente($code_transaction)
        {
            try
            {
                $con = Connection::connect();
            }
            catch(PDOException $p)
            {
                throw new PDOException($p->getMessage());
            }

            try
            {    
                $pst = $con->prepare("select id from webhook where code_transaction = :code_transaction");
                $pst->bindParam(":code_transaction", $code_transaction);
                $pst->execute();
                

                if($pst->rowCount() > 0)
                {           
                    return true;
                }
                else
                    return false;
            }
            catch(PDOException $p)
            {
                return false;
            }
        }

        public function updateTransacao()
        {
            
            try
            {
                $con = Connection::connect();
            }
            catch(PDOException $p)
            {
                throw new PDOException($p->getMessage());
            }

            try
            {
                
                $pst = $con->prepare("update webhook set date_payment = :date_payment, expiration_trans = :expiration_trans, updated_at = now() where code_transaction = :code_transaction");
                $pst->bindParam(":code_transaction", $this->code_transaction);
                $pst->bindParam(":date_payment", $this->date_payment);
                $pst->bindParam(":expiration_trans", $this->expiration_trans);
                $pst->execute();

                if($pst->rowCount() > 0)
                {
                        
                    return array('success' => true);
                }
                else
                        return array('success' => false);
            }
            catch(PDOException $p)
            {
                throw new PDOException("Erro ao obter licenca ".$p->getMessage());
            }
        }




        public function inserirTransacao()
        {
            
            try
            {
                $con = Connection::connect();
            }
            catch(PDOException $p)
            {
                throw new PDOException($p->getMessage());
            }

            try
            {
                
                $pst = $con->prepare("insert into webhook (product_key, trans_key, code_transaction,product_name,client_name,client_email,client_phone,date_payment,recurrence_interval_type,plan_name,canceled_recurrence,expiration_trans, created_at, updated_at) values(:product_key, :trans_key, :code_transaction, :product_name, :client_name, :client_email, :client_phone, :date_payment, :recurrence_interval_type, :plan_name, :canceled_recurrence, :expiration_trans, now(), now())");
                $pst->bindParam(":product_key", $this->product_key);
                $pst->bindParam(":trans_key", $this->trans_key);
                $pst->bindParam(":code_transaction", $this->code_transaction);
                $pst->bindParam(":product_name", $this->product_name);
                $pst->bindParam(":client_name", $this->client_name);
                $pst->bindParam(":client_email", $this->client_email);
                $pst->bindParam(":client_phone", $this->client_phone);
                $pst->bindParam(":date_payment", $this->date_payment);
                $pst->bindParam(":recurrence_interval_type", $this->recurrence_interval_type);
                $pst->bindParam(":plan_name", $this->plan_name);
                $pst->bindParam(":canceled_recurrence", $this->canceled_recurrence);
                $pst->bindParam(":expiration_trans", $this->expiration_trans);
                $pst->execute();

                if($pst->rowCount() > 0)
                {
                        
                    return array('success' => true);
                }
                else
                        return array('success' => false);
            }
            catch(PDOException $p)
            {
                throw new PDOException("Erro ao obter licenca ".$p->getMessage());
            }
        }
        
        
    }
?>