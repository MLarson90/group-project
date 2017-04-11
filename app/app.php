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
    return $app['twig']->render('index.html.twig', array('msg'=>''));
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
      return $app['twig']->render('profile.html.twig', array('user_id'=>$new_user->getId(), 'msg'=>''));
    } elseif (($_POST['password'] == $_POST['password1']) && (in_array($_POST['user_email'], $username) == 1)) {
      return $app['twig']->render('create_account.html.twig', array('msg'=>'That email is in use.'));
      return $app['twig']->render('profile.html.twig', array('user_id'=>$new_user->getId(), 'msg'=>''));
    } else {
      return $app['twig']->render('create_account.html.twig', array('msg'=>'Passwords need to match.'));
    }
  });

  $app->post("/homepage", function() use ($app) {
    if(isset($_POST['button'])){
      $new_profile = new Profile($_POST['first_name'], $_POST['last_name'], $_POST['profile_pic'], $_POST['bio']);
      $new_profile->save($new_profile->getFirstName(), $new_profile->getLastName(), $new_profile->getBio(), $new_profile->getPicture());
      $new_profile->saveUsertoJoinTable($_POST['user_id']);
      $user = User::findUserbyId($_POST['user_id']);
      $groups = $user->getGroup();
      var_dump($groups);
      return $app['twig']->render('homepage.html.twig', array('profile'=>Profile::getProfileUsingId($_POST['user_id']), 'user'=>$user, 'groups'=>$groups,'user_id'=>$_POST['user_id']));
    } else {
        return $app['twig']->render('profile.html.twig', array('user_id'=>$_POST['user_id'], 'msg'=>''));
    }
  });
  $app->post("/login_user", function() use ($app) {
    $username = $_POST['username'];
    $password = $_POST['userpassword'];
    $user_id = User::login($username, $password);

    if ($user_id == null)
    {
      return $app['twig']->render('index.html.twig', array('msg'=>"Sorry, we could not find your account."));
    } else {
      $profile = Profile::getProfileUsingId($user_id);
      $user = User::findUserbyId($user_id);
      $groups = $user->getGroup();
      return $app['twig']->render('homepage.html.twig', array('profile'=>$profile,'user'=>$user,'user_id'=>$user_id, 'groups'=>$groups));
    }
  });

  $app->post("/creategroup", function () use ($app) {
    if(($_POST['group'] != null) && (!empty($_POST['privacy']))){
      $group = new Group($_POST['group'], $_POST['privacy']);
      $group->save();
      $group_id = $group->getId();
      $admin_id = $group->groupAdminId();
      $user = User::findUserbyId($_POST['user_id']);
      $user->addGroup($group_id);
      return $app['twig']->render('group.html.twig',array('group_id'=>$group_id, 'admin_id'=>$admin_id, 'user'=>$user));
    } else {
      return $app['twig']->render('homepage.html.twig', array('profile'=>Profile::getProfileUsingId($_POST['user_id']), 'user'=>User::findUserbyId($_POST['user_id']), 'user_id'=>$_POST['user_id']));
    }

  $app->post("/group", function () use ($app) {
    return $app['twig']->render('group.html.twig', array(''));
  });

  });

  return $app;
 ?>
