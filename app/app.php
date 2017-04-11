<?php
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Group.php";
  require_once __DIR__."/../src/Task.php";
  require_once __DIR__."/../src/User.php";
  require_once __DIR__."/../src/Profile.php";

  use Symfony\Component\Debug\Debug;
  Debug::enable();

  $app = new Silex\Application();
  $DB = new PDO('mysql:host=localhost;dbname=appdata', 'root', 'root');
  $app['debug'] = true;
  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  $app->get("/", function() use ($app) {
    return $app['twig']->render('index.html.twig', 'msg'=>'');
  });
  $app->post("/create_user", function() use ($app) {
    return $app['twig']->render('create_account.html.twig', array('msg'=>''));
  });
  $app->post("/create_account", function() use ($app) {
    $username = User::usernameArray();
    if (($_POST['password'] == $_POST['password1']) && (in_array($_POST['user_email'], $username) == 0))
    {
      $new_user = new User($_POST['user_email'], $_POST['password']);
      $new_user->save();
      return $app['twig']->render('profile.html.twig', array('user_id'=>$new_user->getId()));
    } elseif (($_POST['password'] == $_POST['password1']) && (in_array($_POST['user_email'], $username) == 1)) {
      return $app['twig']->render('create_account.html.twig', array('msg'=>'That email is in use.'));
    } else {
      return $app['twig']->render('create_account.html.twig', array('msg'=>'Passwords need to match.'));
    }
  });
  $app->post("/homepage", function() use ($app) {
    $new_profile = new Profile ($_POST['first_name'], $_POST['last_name'], $_POST['profile_pic'], $_POST['bio']);
    var_dump($new_profile->getPicture());
    $new_profile->save();
    return $app['twig']->render('homepage.html.twig');
  });
  $app->post("/login_user", function() use ($app) {
    $username = $_POST['username'];
    $password = $_POST['userpassword'];
    $user = User::login($username, $password);
    if ($user != null)
    {
      $user_id = $user->getId();
      $profile = Profile::getProfileUsingId($user_id);
      return $app['twig']->render('homepage.html.twig', array('profile'=>$profile));
    } else {
      return $app['twig']->render('index.html.twig', 'msg'=>"Sorry, we could not find your account.");
    }
  });
  return $app;
 ?>
