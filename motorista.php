<?php

class Motorista
{



    private $pdo;
    //conexao com o banco de dados
    public function __construct($dbname, $host, $user, $password)
    {
        try {
            $this->pdo = new PDO("mysql:host=" . $host . ";dbname=" . $dbname, $user, $password);
        } catch (PDOException $e) {
            echo "Erro com banco de dados " . $e->getMessage();
            exit();
        } catch (Exception $e) {
            echo "Erro genérico" . $e->getMessage();
            exit();
        }
    }

    //função para inserir os resultados na listagem
    public function buscarDados()
    {
        $result = array();
        $sql = $this->pdo->query("SELECT * FROM motorista ORDER BY nome");
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //função para cadastrar motorista
    public function cadastrarMotorista($nome, $cpf, $cnh)
    {
        $sql = $this->pdo->prepare("SELECT id FROM motorista WHERE cpf = :c");
        $sql->bindValue(":c", $cpf);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            return false;
        } else {
            $sql = $this->pdo->prepare("INSERT INTO motorista (nome, cpf, cnh) VALUES (:n, :c, :h)");
            $sql->bindValue(":n", $nome);
            $sql->bindValue(":c", $cpf);
            $sql->bindValue(":h", $cnh);
            $sql->execute();
            return true;
        }
    }

    //Exclusao motorista
    public function excluirMotorista($id)
    {
        $sql = $this->pdo->prepare("DELETE FROM motorista WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();
    }

    //Buscar dados do motorista
    public function buscarDadosMotorista($id)
    {
        $result = array();
        $sql = $this->pdo->prepare("SELECT * FROM motorista WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //Atualizar os dados no banco de dados\
    public function atualizarDados($id, $nome, $cpf, $cnh)
    {
        $sql = $this->pdo->prepare("UPDATE motorista SET nome = :n, cpf = :c, cnh = :h WHERE id = :id");
        $sql->bindValue(":n", $nome);
        $sql->bindValue(":c", $cpf);
        $sql->bindValue(":h", $cnh);
        $sql->bindValue(":id", $id);
        $sql->execute();
    }
}
