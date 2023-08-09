<?php   

    class Connection
    {
        public static function connect()
        {

            $dados = json_decode(file_get_contents('CONFIG.json'));

            $host = $dados->host;
            $user = $dados->user;
            $senha = $dados->senha;
            $banco = $dados->banco;



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