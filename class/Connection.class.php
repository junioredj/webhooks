<?php   

    class Connection
    {
        public static function connect()
        {

            $host = "localhost";
            $user = "root";
            $senha = "";
            $banco = "webhook-universal";



            try
            {
                $conexao = new PDO("mysql:dbname=$banco;host=$host", $user,$senha); 
                return $conexao;
            }
            catch(PDOException $erro_banco)
            {
                echo "Erro com banco de dados ".$erro_banco->getMessage();
            }
            catch(Exception $erro)
            {
                echo "Erro genérico ".$erro->getMessage();
            }

            return $conexao;
        }
    }

?>