<?php
require_once('Models/Database.php');

class Users
{

    private $db;

    /**
     * creates an instance of database class to connect to the database
     */
    public function __construct()
    {
        $dbInstance= Database::getInstance();
        $this->db= $dbInstance->getdbConnection();
    }

    /**
     * login function allows users to login
     *
     * @param $name
     * @param $pass
     * @param $email
     */
    public function login($name,$pass,$email)
    {
        if(!empty($name) && !empty($pass))
        {
            $sqlQuery = "SELECT * FROM Users WHERE username = ? OR email = ? AND password = ?";
            $statement = $this->db->prepare($sqlQuery);
            $statement->bindParam(1, $name);
            $statement->bindParam(2, $email);
            $statement->bindParam(3, $pass);
            $statement->execute();



            if($statement->rowCount() == 1)
            {
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $pwdCheck = password_verify($pass, $result['password']); // verify hashed password
                if($pwdCheck == true) // if password is verified log in user and redirect to homepage otherwise display error
                {
                    $_SESSION['login'] = $_POST['uname'];
                    $_SESSION['id'] = $result['user_id'];
                    $_SESSION['profile'] = $result['profile_picture'];
                    header("Location: index.php");
                }
                else
                    {
                        echo '<div id="content">Wrong password</div>';
                    }

            }
            else
                {
                    echo '<div id="content">Incorrect data entered</div>';
                }
        }
        else
            {
                echo '<div id="content">All fields are required</div>';
            }
    }

    /**
     * @param $username
     * @param $password
     * @param $email
     * @param $name
     * @param $pic
     *
     * Register function, allows new users to register and add them to the database
     */
    public function register($username, $password, $email, $name, $pic)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if(!empty($username) && !empty($password) && !empty($email) && !empty($name) && !empty($pic))
        {
            $sqlQuery = "INSERT INTO Users (username, password, email, fullname, profile_picture)"
            ."VALUES(?, ?, ?, ?, ?)";
            $statement = $this->db->prepare($sqlQuery);
            $statement->bindValue(1, $username);
            $statement->bindValue(2, $hash);
            $statement->bindValue(3, $email);
            $statement->bindValue(4, $name);
            $statement->bindValue(5, $pic);

            $statement->execute();

            if ($statement->rowCount() == 1)
            {
                //$result = $statement->fetch(PDO::FETCH_ASSOC);
                $_SESSION['login'] = $_POST['uname'];
                //$_SESSION['ID'] = $result['user_id'];
                header("Location: index.php");
            }
        }
        else
            {
                echo '<div id="content">All fields are required</div>';
            }

    }

    /**
     * @return boolean
     *
     * logout function allows logged in users to log out
     *
     */
    public function logout()
    {
        // Destroy and unset active session
        session_destroy();
        unset($_SESSION['login']);
        unset($_SESSION['profile']);
        header("location:index.php");
        return true;
    }

}