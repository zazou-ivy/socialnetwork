<?php

$action = $_GET["action"] ?? "display"; //si rien ne sort comme action, on execute le cas de "display"

switch ($action) {

  case 'register':
    // code...
    break;

  case 'logout':
    if (isset($_SESSION['userId'])) {    //si userId existe dans la session, il faut qu'il s'enlève
      unset($_SESSION['userId']);
    }
    header('Location: ?action=display');
    break;

  case 'login':
    include "../models/UserManager.php";
    if (isset($_POST['username']) && isset($_POST['password'])) {   //si  il y a un utilisateur et mot de passe
      $userId = GetUserIdFromUserAndPassword($_POST['username'], $_POST['password']);  //la méthode qui récupère l'id et password id de l'utilisateur est appelée
      if ($userId > 0) {  // si on récupère un utilisateur 
        $_SESSION['userId'] = $userId;
        header('Location: ?action=display');
      } else {
        $errorMsg = "Wrong login and/or password.";  //sinon on recoit un messsage d'erreur
        include "../views/LoginForm.php";
      }
    } else {
      include "../views/LoginForm.php";
    }
    break;

  case 'newMsg':
    // code...
    break;

  case 'newComment':
    // code...
    break;

  case 'display':
  default:
    include "../models/PostManager.php";
    if (isset($_GET['search'])) {
      $posts = SearchInPosts($_GET['search']);
    } else {
      $posts = GetAllPosts();
    }

    include "../models/CommentManager.php";
    $comments = array();

    foreach ($posts as $onePost) {
      $idPost = $onePost['id'];
      $commentsForThisPost = GetAllCommentsFromPostId($idPost);
      $comments[$idPost] = $commentsForThisPost;
    }

    include "../views/DisplayPosts.php";
    break;
}
