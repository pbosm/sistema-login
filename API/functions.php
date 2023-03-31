<?php

require_once('function.php');

class Functions
{
    public function registerUser($args)
    {
        $name = $args['name'];
        $email = $args['email'];
        $password = cryptS($args['password']);
        $cpf = clearString($args['cpf']);

        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        $sql = $conn->prepare("SELECT email AS email FROM usuarios WHERE email = :email");
        $sql->bindParam(':email', $email);
        $sql->execute();
        $verifyEmail = $sql->fetchColumn();

        $sql = $conn->prepare("SELECT cpf AS CPF FROM usuarios WHERE cpf = :cpf");
        $sql->bindParam(':cpf', $cpf);
        $sql->execute();
        $verifyCPF = $sql->fetchColumn();

        if ($verifyEmail || $verifyCPF > 0) {
            throw new Exception("E-mail ou CPF já cadastrados!");
        }

        try {
            $sql = $conn->prepare("INSERT INTO usuarios (nome, email, senha, cpf) VALUES (:name, :email, :password, :cpf)");
            $sql->bindParam(':name', $name);
            $sql->bindParam(':email', $email);
            $sql->bindParam(':password', $password);
            $sql->bindParam(':cpf', $cpf);
            $sql->execute();

            return true;
        } catch (exception $e) {
            return "Erro: {$e}";
        }
    }

    public function loginClient($args)
    {
        session_start();

        $email = $args['email'];
        $password = cryptS($args['password']);

        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        $sql = $conn->prepare("SELECT email AS email, senha FROM usuarios WHERE email = :email AND senha = :password");
        $sql->bindParam(':email', $email);
        $sql->bindParam(':password', $password);
        $sql->execute();
        $verifyLogin = $sql->fetchColumn();

        if ($verifyLogin <= 0) {
            throw new Exception("E-mail ou senha inválidas!");
        }

        try {
            $sql = $conn->prepare("SELECT id AS id, email AS email, senha FROM usuarios WHERE email = :email AND senha = :password");
            $sql->bindParam(':email', $email);
            $sql->bindParam(':password', $password);
            $sql->execute();
            $getUserId = $sql->fetchAll();

            foreach ($getUserId as $user) {
                $getUserId['id'] = cryptS($user['id']);
            }

            $sessao = $_SESSION["conectado"] = true;
            return array($sessao, cryptS($user['id']));

        } catch (exception $e) {
            return "Erro: {$e}";
        }
    }

    public function verificaAcesso()
    {
        //teste para validarmos o acesso do usuário ao conteúdo restrito
        if (!isset($_SESSION['conectado']) or $_SESSION['conectado'] != true) {
            //temos um acesso indevido do usuário. Encerramos a aplicação
            header('Location: ../../src/pages/error404.php');
            exit();
        }
    }

    public function getUser($user)
    {
        $user = descryptS($user);

        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        try {
            $sql = $conn->prepare("SELECT concat(Upper(substr(nome, 1,1)), lower(substr(nome, 2,length(nome)))) AS name FROM usuarios WHERE id = :user");
            $sql->bindParam(':user', $user);
            $sql->execute();
            $getUserId = $sql->fetch(PDO::FETCH_ASSOC);

            return $getUserId;
        } catch (exception $e) {
            return "Erro: {$e}";
        }
    }

    public function searchUser($args)
    {
        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        $limit = $args['limit'];
        $page = $args['page'];
        $search = $args['search'];
        $search = "%$search%";

        if ($limit)
            $current = $limit * ($page - 1);

        try {
            $sql = "SELECT nome AS name, email AS email, cpf AS cpf from usuarios WHERE nome LIKE :search";

            if ($limit)
                $sql .= ' ORDER BY name DESC LIMIT :current, :limit';

            $code = $conn->prepare($sql);
            $code->bindParam(':search', $search);
            $code->bindParam(':limit', $limit, PDO::PARAM_INT);
            $code->bindParam(':current', $current, PDO::PARAM_INT);
            $code->execute();
            $search = $code->fetchAll(PDO::FETCH_ASSOC);

            return $search;
        } catch (exception $e) {
            return "Erro: {$e}";
        }
    }

    public function getUsers($args)
    {
        $limit = $args['limit'];
        $page = $args['page'];

        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        if ($limit)
            $current = $limit * ($page - 1);

        try {
            $sql = "SELECT nome AS name, email AS email, cpf AS cpf from usuarios";

            if ($limit)
                $sql .= ' ORDER BY name DESC LIMIT :current, :limit';

            $code = $conn->prepare($sql);
            $code->bindParam(':limit', $limit, PDO::PARAM_INT);
            $code->bindParam(':current', $current, PDO::PARAM_INT);
            $code->execute();
            $getUsers = $code->fetchAll(PDO::FETCH_ASSOC);

            return $getUsers;
        } catch (exception $e) {
            return "Erro: {$e}";
        }
    }

    public function loadChart()
    {
        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        try {
            $sql = $conn->prepare("SELECT COUNT(nome) AS users FROM usuarios");
            $sql->execute();
            $getNumberUsers = $sql->fetch(PDO::FETCH_ASSOC);

            $sql = $conn->prepare("SELECT COUNT(nome) AS collaborators FROM colaboradores");
            $sql->execute();
            $getNumberCollaborators = $sql->fetch(PDO::FETCH_ASSOC);

            $result = array(
                'users' => $getNumberUsers,
                'collaborators' => $getNumberCollaborators,
            );

            return $result;
        } catch (exception $e) {
            return "Erro: {$e}";
        }
    }

    public function registrationCollaborators($args)
    {
        $password = $args['password'];
        $user = descryptS($args['user']);

        $name = $args['name'];
        $email = $args['email'];
        $cpf = clearString($args['cpf']);
        $data = $args['data'];

        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        if (!verifyPassword($password, $user)) {
            throw new Exception("Senha incorreta");
            // exit;
        }

        $sql = $conn->prepare("SELECT email AS email FROM usuarios WHERE email = :email");
        $sql->bindParam(':email', $email);
        $sql->execute();
        $verifyEmail = $sql->fetchColumn();

        $sql = $conn->prepare("SELECT cpf AS CPF FROM usuarios WHERE cpf = :cpf");
        $sql->bindParam(':cpf', $cpf);
        $sql->execute();
        $verifyCPF = $sql->fetchColumn();

        if ($verifyEmail || $verifyCPF > 0) {
            throw new Exception("E-mail ou CPF já cadastrados!");
        }

        $sql = $conn->prepare("INSERT INTO colaboradores (nome, email, cpf, dt_registration) VALUES (:nome, :email, :cpf, :data)");
        $sql->bindParam(':nome', $name);
        $sql->bindParam(':email', $email);
        $sql->bindParam(':cpf', $cpf);
        $sql->bindParam(':data', $data);
        $sql->execute();
    }

    public function getUsersTable($args)
    {
        $limit = $args['limit'];
        $page = $args['page'];

        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        if ($limit)
            $current = $limit * ($page - 1);

        try {
            $sql = "SELECT id AS id, nome AS name, email AS email, cpf AS cpf from usuarios";

            if ($limit)
                $sql .= ' ORDER BY name DESC LIMIT :current, :limit';

            $code = $conn->prepare($sql);
            $code->bindParam(':limit', $limit, PDO::PARAM_INT);
            $code->bindParam(':current', $current, PDO::PARAM_INT);
            $code->execute();
            $getUsersTable = $code->fetchAll(PDO::FETCH_ASSOC);

            foreach ($getUsersTable as $key => $users) {
                $getUsersTable[$key]['id'] = cryptS($users['id']);
            }

            return $getUsersTable;
        } catch (exception $e) {
            return "Erro: {$e}";
        }
    }

    public function deleteUser($args)
    {
        $user = descryptS($args['user']);
        $id = descryptS($args['id']);
        $password = $args['password'];
        $item = $args['item'];

        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        if (!verifyPassword($password, $user)) {
            throw new Exception("Senha incorreta");
            // exit;
        }

        try {
            if ($item == 'users') {

                $sql = $conn->prepare("DELETE from usuarios WHERE id = :id");
                $sql->bindParam(':id', $id);
                $sql->execute();

                return true;
            }

            if ($item == 'collaborators') {
                $sql = $conn->prepare("DELETE from colaboradores WHERE id = :id");
                $sql->bindParam(':id', $id);
                $sql->execute();

                return true;
            }

        } catch (exception $e) {
            return "Erro: {$e}";
        }
    }

    public function editUser($args)
    {
        $user = descryptS($args['user']);
        $id = descryptS($args['id']);
        $name = $args['name'];
        $email = $args['email'];
        $cpf = clearString($args['cpf']);
        $password = $args['password'];
        $item = $args['item'];

        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        if (!verifyPassword($password, $user)) {
            throw new Exception("Senha incorreta");
            // exit;
        }

        try {
            if ($item == 'users') {
                $sql = $conn->prepare('UPDATE usuarios SET nome=:nome, email=:email, cpf=:cpf WHERE id = :id');
                $sql->bindParam(':id', $id);
                $sql->bindParam(':nome', $name);
                $sql->bindParam(':email', $email);
                $sql->bindParam(':cpf', $cpf);
                $sql->execute();

                return true;
            }

            if ($item == 'collaborators') {
                $sql = $conn->prepare('UPDATE colaboradores SET nome=:nome, email=:email, cpf=:cpf WHERE id = :id');
                $sql->bindParam(':id', $id);
                $sql->bindParam(':nome', $name);
                $sql->bindParam(':email', $email);
                $sql->bindParam(':cpf', $cpf);
                $sql->execute();

                return true;
            }

        } catch (exception $e) {
            return "Erro: {$e}";
        }
    }

    public function filterUser($args)
    {
        $name = $args['name'];
        $item = $args['item'];

        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        try {

            if ($item == 'users') {

                $sql = $conn->prepare('SELECT id AS id, nome AS name, email AS email, cpf AS cpf FROM usuarios WHERE nome = :nome');
                $sql->bindParam(':nome', $name);
                $sql->execute();
                $filterUser = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach ($filterUser as $key => $users) {
                    $filterUser[$key]['id'] = cryptS($users['id']);
                }

                return $filterUser;
            }

            if ($item == 'collaborators') {

                $sql = $conn->prepare('SELECT id AS id, nome AS name, email AS email, cpf AS cpf FROM colaboradores WHERE nome = :nome');
                $sql->bindParam(':nome', $name);
                $sql->execute();
                $filterCollaborators = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach ($filterCollaborators as $key => $users) {
                    $filterCollaborators[$key]['id'] = cryptS($users['id']);
                }

                return $filterCollaborators;
            }
        } catch (exception $e) {
            return "Erro: {$e}";
        }
    }

    public function getSelectUsers()
    {
        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        try {
            $sql = $conn->prepare("SELECT nome AS name FROM usuarios");
            $sql->execute();
            $getSelectUsers = $sql->fetchAll(PDO::FETCH_ASSOC);

            $result = [
                'users' => $getSelectUsers,
            ];

            return $result;
        } catch (exception $e) {
            return "Erro: {$e}";
        }
    }

    public function getSelectCollaborators()
    {
        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        try {
            $sql = $conn->prepare("SELECT nome AS name FROM colaboradores");
            $sql->execute();
            $getSelectCollaborators = $sql->fetchAll(PDO::FETCH_ASSOC);

            $result = [
                'collaborators' => $getSelectCollaborators,
            ];

            return $result;
        } catch (exception $e) {
            return "Erro: {$e}";
        }
    }

    public function ViewLaunch($args)
    {
        $page = $args['page'];
        $limit = $args['limit'];
        $item = $args['item'];

        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        if ($limit)
            $current = $limit * ($page - 1);

        $result = array();

        if ($item) {

            $result = [
                'users' => [],
                'collaborators' => []
            ];

            if ($item == 'users') {

                $sql = "SELECT id AS id, nome AS name, email AS email, cpf AS cpf from usuarios";

                if ($limit)
                    $sql .= ' ORDER BY name DESC LIMIT :current, :limit';

                $code = $conn->prepare($sql);
                $code->bindParam(':limit', $limit, PDO::PARAM_INT);
                $code->bindParam(':current', $current, PDO::PARAM_INT);
                $code->execute();
                $getUsersTable = $code->fetchAll(PDO::FETCH_ASSOC);

                foreach ($getUsersTable as $key => $users) {
                    $getUsersTable[$key]['id'] = cryptS($users['id']);
                }

                $result['users'] = $getUsersTable;
            }

            if ($item == 'collaborators') {
                $sql = "SELECT id AS id, nome AS name, email AS email, cpf AS cpf from colaboradores";

                if ($limit)
                    $sql .= ' ORDER BY name DESC LIMIT :current, :limit';

                $code = $conn->prepare($sql);
                $code->bindParam(':limit', $limit, PDO::PARAM_INT);
                $code->bindParam(':current', $current, PDO::PARAM_INT);
                $code->execute();
                $getCollaboratorsTable = $code->fetchAll(PDO::FETCH_ASSOC);

                foreach ($getCollaboratorsTable as $key => $collaborators) {
                    $getCollaboratorsTable[$key]['id'] = cryptS($collaborators['id']);
                }

                $result['collaborators'] = $getCollaboratorsTable;
            }

            return $result;
        }
    }

    public function getCollaborators($args)
    {
        $limit = $args['limit'];
        $page = $args['page'];

        require_once('../src/conn/conn.php');
        $conn = Database::connectionPDO();

        if ($limit)
            $current = $limit * ($page - 1);

        try {
            $sql = "SELECT id AS id, nome AS name, email AS email, cpf AS cpf from colaboradores";

            if ($limit)
                $sql .= ' ORDER BY name DESC LIMIT :current, :limit';

            $code = $conn->prepare($sql);
            $code->bindParam(':limit', $limit, PDO::PARAM_INT);
            $code->bindParam(':current', $current, PDO::PARAM_INT);
            $code->execute();
            $getCollaborators = $code->fetchAll(PDO::FETCH_ASSOC);

            foreach ($getCollaborators as $key => $collaborators) {
                $getCollaborators[$key]['id'] = cryptS($collaborators['id']);
            }

            return $getCollaborators;
        } catch (exception $e) {
            return "Erro: {$e}";
        }
    }
}

?>