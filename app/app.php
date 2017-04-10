<?php
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Group.php";
  require_once __DIR__."/../src/Task.php";
  require_once __DIR__."/../src/User.php";

  use Symfony\Component\Debug\Debug;
  Debug::enable();

  $app = new Silex\Application();
  $DB = new PDO('mysql:host=localhost:8889;dbname=appdata', 'root', 'root');
  $app['debug'] = true;
  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  $app->get("/", function() use ($app) {
    return $app['twig']->render('index.html.twig');
  });
  $app->post("/create_user", function() use ($app) {
    return $app['twig']->render('create_account.html.twig');
  });
  $app->post("/create_account", function() use ($app) {
    if ($_POST['password'] == $_POST['password1'])
    {
      $new_user = new User($_POST['user_email'], $_POST['password']);
      $new_user->save();
      return $app['twig']->render('profile.html.twig', array('user_id'=>$new_user->getId()));
    } else {
      return $app['twig']->render('create_account.html.twig', array('msg'=>'Your passwords need to be the same'));
    }
  });
  $app->post("/homepage", function() use ($app) {
    $new_user = User::find($_POST['user_id']);
    $new_user->userProfileSave($_POST['first_name'], $_POST['last_name'], $_POST['picture'], $_POST['bio']);
    $new_user->
    return $app['twig']->render('homepage.html.twig');
  });
  $app->post("/login_user", function() use ($app) {
    return $app['twig']->render('homepage.html.twig');
  });
  return $app;
 ?>
