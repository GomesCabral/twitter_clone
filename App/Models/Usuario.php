<?php
    namespace App\Models;

    use MF\Model\Model;

    class Usuario extends Model{
        private $id;
        private $nome;
        private $email;
        private $password;

        public function __get($att){
            return $this->$att;
        }

        public function __set($att, $value){
            $this->$att = $value;
        }

        //REGISTAR
        public function salvar(){
            $query = "insert into usuarios(nome, email, password)values(:nome, :email, :password)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':email', $this->__get('email'));

            //md5()->hash 32 caracteres
            $stmt->bindValue(':password', $this->__get('password'));

            $stmt->execute();

            return $this;
        }

        //validar se o registo pode ser feito
        public function validarCadastro(){
            $valido = true;

            if(strlen($this->__get('nome')) < 3 or empty($this->__get('nome'))){
                $valido = false;
            }

            if(!filter_var($this->__get('email'), FILTER_VALIDATE_EMAIL)){
                $valido = false;
            }

            if(strlen($this->__get('password')) < 3){
                $valido = false;
            }

            return $valido;
        }

        //recuperar user por email
        public function getUsuarioPorEmail(){
            $query = "select nome, email from usuarios where email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email',$this->__get('email'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function autenticar(){
            $query = 'select id, nome, email from usuarios where email = :email and password = :password';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':password', $this->__get('password'));
            $stmt->execute();

            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

            if(!empty($usuario['id']) && !empty($usuario['nome'])){
                $this->__set('id', $usuario['id']);
                $this->__set('nome', $usuario['nome']);
            }
            return $this;
        }

              public function seguirUsuario($id_usuario_seguindo){

            $query = "insert into usuarios_seguidores(id_usuario, id_usuario_seguindo)values(:id_usuario, :id_usuario_seguindo)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
            $stmt->execute();

            return true;
        }

        public function deixarSeguirUsuario($id_usuario_seguindo){
            
            $query = "delete from usuarios_seguidores where id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
            $stmt->execute();

            return true;

        }

          // public function getAll(){
        //     $query = "select id, nome, email from usuarios where nome like :nome and id != :id_usuario";

        //     $stmt = $this->db->prepare($query);
        //     $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        //     $stmt->bindValue(':id_usuario', $this->__get('id'));
        //     $stmt->execute();

        //     return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        // }

        public function getAll(){
            $query = "select 
            u.id,
            u.nome,
            u.email,
            (select 
            count(*) 
            from usuarios_seguidores as us 
            where 
            us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id) 
            as seguindo_sn 
            from usuarios as u 
            where 
            u.nome like :nome and u.id != :id_usuario";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        // INFORMAÇÃO USUARIO
        public function getInfoUsuario(){

            $query = 'select nome from usuarios where id = :id_usuario';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        //TOTAL DE TWEETS
        public function getTotalTweets(){

            $query = 'select count(*) as total_tweets from tweets where id_usuario = :id_usuario';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        //TOTAL DE USUARIOS QUE SEGUIMOS
        public function getTotalSeguindo(){

            $query = 'select count(*) as total_seguindo from usuarios_seguidores where id_usuario = :id_usuario';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        //TOTAL DE SEGUIDORES
        public function totalSeguidores(){
            $query = 'select count(*) as total_seguidores from usuarios_seguidores where id_usuario_seguindo = :id_usuario';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

    }
?>