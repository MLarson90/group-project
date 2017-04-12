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

  $app->get("/viewprofile/{first_name}/{profile_id}/{id}", function($first_name, $profile_id, $id) use ($app) {
    $profile = Profile::findProfile($profile_id);
    $user = Profile::findUserbyProfileId($profile_id);
    $user_id = $user->getId();
    $groups = $user->getGroup();
    return $app['twig']->render('viewprofile.html.twig', array('profile'=>$profile,  'profile_id'=>$profile_id, 'user_id'=>$user_id, 'groups' => $groups, 'id'=>$id));
  });
  $app->post("/viewprofile/{id}", function($id) use ($app) {
    return $app['twig']->render('viewprofile.html.twig', array('profile'=>$profile, 'user_id'=>$id ));
  });
  $app->get("/homepage/{id}", function($id) use($app){
    $user = User::findUserbyId($id);
    $user_id = $user->getId();
    $groups = $user->getGroup();
    $group_requests = $user->findGroupRequest();
    $user_request = $user->findFriendRequest();
    return $app['twig']->render('homepage.html.twig', array('profile'=>Profile::getProfileUsingId($id), 'user'=>$user, 'groups'=>$groups,'user_id'=>$user_id, 'group_requests'=>$group_requests,'user_request'=>$user_request));
  });

  $app->post("/homepage", function() use ($app) {
    if(isset($_POST['button'])){
      $new_profile = new Profile($_POST['first_name'], $_POST['last_name'], $_POST['profile_pic'], $_POST['bio']);
      $new_profile->save($new_profile->getFirstName(), $new_profile->getLastName(), $new_profile->getBio(), $new_profile->getPicture());
      $new_profile->saveUsertoJoinTable($_POST['user_id']);
      $user = User::findUserbyId($_POST['user_id']);
      $groups = $user->getGroup();
      $group_requests = $user->findGroupRequest();
      $user_request = $user->findFriendRequest();
      return $app['twig']->render('homepage.html.twig', array('profile'=>Profile::getProfileUsingId($_POST['user_id']), 'user'=>$user, 'groups'=>$groups,'user_id'=>$_POST['user_id'], 'group_requests'=>$group_requests,'user_request'=>$user_request));
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
      $group_requests = $user->findGroupRequest();
      $user_request = $user->findFriendRequest();
      return $app['twig']->render('homepage.html.twig', array('profile'=>$profile,'user'=>$user,'user_id'=>$user_id, 'groups'=>$groups, 'group_requests'=>$group_requests,'user_request'=>$user_request));
    }
  });

  $app->post("/creategroup", function () use ($app) {
   if(($_POST['group'] != null) && (isset($_POST['privacy']))){
      $group = new Group($_POST['group'], $_POST['privacy']);
      $group->save();
      $group_id = $group->getId();
      $admin_id = $group->groupAdminId();
      $user = User::findUserbyId($_POST['user_id']);
      $user->addGroup($group_id);
      $group_requests = $user->findGroupRequest();
      $user_request = $user->findFriendRequest();
      $groups = $user->getGroup();
      return $app['twig']->render('homepage.html.twig', array('profile'=>Profile::getProfileUsingId($_POST['user_id']), 'user'=>User::findUserbyId($_POST['user_id']), 'user_id'=>$_POST['user_id'], 'groups'=>$groups, 'group_requests'=>$group_requests, 'user_request'=>$user_request));
    } else {
      $user = User::findUserbyId($_POST['user_id']);
      $group_requests = $user->findGroupRequest();
      $user_request = $user->findFriendRequest();
      $groups = $user->getGroup();
      return $app['twig']->render('homepage.html.twig', array('profile'=>Profile::getProfileUsingId($_POST['user_id']), 'user'=>User::findUserbyId($_POST['user_id']), 'user_id'=>$_POST['user_id'], 'groups'=>$groups, 'group_requests'=>$group_requests,'user_request'=>$user_request));
    }
  });

  $app->get("/group/{id}", function ($id) use ($app) {
    $user = User::findUserbyId($id);
    $groups = Group::findGroupByUserId($id);
    $group_requests = $user->findGroupRequest();
    $user_request = $user->findFriendRequest();
    return $app['twig']->render('homepage.html.twig', array('groups'=>$groups, 'user_id'=>$id, 'user'=>$user, 'profile'=>Profile::getProfileUsingId($id), 'group_requests'=>$group_requests,'user_request'=>$user_request));
  });
  $app->get("/groupinfo/{group_id}/{user_id}", function ($group_id, $user_id) use ($app) {
    $group = Group::find($group_id);
    $admin_id = $group->groupAdminId();
    $user = User::findUserbyId($user_id);
    $tasks = Task::getAllByGroupId($group_id);
    return $app['twig']->render('group.html.twig', array('group_id'=>$group->getId(), 'admin_id'=>$admin_id, 'user'=>$user, 'msg'=>'', 'tasks'=>$tasks));
  });

  $app->post("/search/{id}", function($id) use($app){
      $user = User::findUserbyId($id);
      $user_id = $user->getId();
      $search = '%'.$_POST['searchName'].'%';
      $results = Profile::search($search);
      if($_POST['searchName'] != null){
        return $app['twig']->render('search_results.html.twig', array('profiles'=>$results, 'msg'=>'', 'user_id'=>$user_id));
      } else {
        return $app['twig']->render('search_results.html.twig', array('profiles'=>'', 'user_id'=>$user_id, 'msg'=>'No Match!'));
      }
  });

  $app->post("/sendinvite", function() use($app){
    if(!empty($_POST['user'])){
      $user_name_array = User::usernameArray();
      if(in_array($_POST['user'], $user_name_array)){
        $user = User::findByUserName($_POST['user']);
        $user->saveGroupRequest($_POST['group_id'], $_POST['user_id']);
        $tasks = Task::getAllByGroupId($_POST['group_id']);
        return $app['twig']->render('group.html.twig', array('group_id'=>$_POST['group_id'], 'admin_id'=>$_POST['admin_id'], 'user'=>User::findUserbyId($_POST['user_id']), 'msg'=>'Invitation has sent!', 'tasks'=>$tasks));
      } else {
        $tasks = Task::getAllByGroupId($_POST['group_id']);
        return $app['twig']->render('group.html.twig', array('group_id'=>$_POST['group_id'], 'admin_id'=>$_POST['admin_id'], 'user'=>User::findUserbyId($_POST['user_id']), 'msg'=>'User is not existed!', 'tasks'=>$tasks));
      }
    }
  });

  $app->post("/groupaccept", function () use ($app) {
    $user = User::findUserbyId($_POST['user_id']);
    $user->addGroup($_POST['group_id']);
    $user->deleteGroupRequest($_POST['group_id'], $_POST['sender_id']);
    $group_requests = $user->findGroupRequest();
    $user_request = $user->findFriendRequest();
    $groups = $user->getGroup();
    return $app['twig']->render('homepage.html.twig', array('profile'=>Profile::getProfileUsingId($_POST['user_id']), 'user'=>User::findUserbyId($_POST['user_id']), 'user_id'=>$_POST['user_id'], 'groups'=>$groups, 'group_requests'=>$group_requests,'user_request'=>$user_request));
  });

  $app->post("/grouprefuse", function () use ($app) {
    $user = User::findUserbyId($_POST['user_id']);
    $user->deleteGroupRequest($_POST['group_id'], $_POST['sender_id']);
    $group_requests = $user->findGroupRequest();
    $user_request = $user->findFriendRequest();
    $groups = $user->getGroup();
    return $app['twig']->render('homepage.html.twig', array('profile'=>Profile::getProfileUsingId($_POST['user_id']), 'user'=>User::findUserbyId($_POST['user_id']), 'user_id'=>$_POST['user_id'], 'groups'=>$groups, 'group_requests'=>$group_requests,'user_request'=>$user_request));
  });

  $app->post("/createtask", function () use ($app) {
    if(isset($_POST['createtask'])){
      $new_task = new Task($_POST['task'], $_POST['description']);
      $new_task->save();
      $new_task->addGroupToTask($_POST['group_id']);
      $tasks = Task::getAllByGroupId($_POST['group_id']);
      return $app['twig']->render('group.html.twig', array('group_id'=>$_POST['group_id'], 'admin_id'=>$_POST['admin_id'], 'user'=>User::findUserbyId($_POST['user_id']), 'msg'=>'Task created successfully', 'tasks'=>$tasks));
    }
  });
  $app->post('/sendFriendRequest', function() use ($app){
    $sender = User::findUserbyId($_POST['sender_id']);
    $receiver = User::findUserbyId($_POST['receiver_id']);
    $profile = Profile::getProfileUsingId($_POST['receiver_id']);
    $user = Profile::findUserbyProfileId($_POST['receiver_id']);
    $user_id = $receiver->getId();
    $groups = $receiver->getGroup();
    $id = ($_POST['sender_id']);
    $sender->saveFriendRequest($receiver->getId());
    return $app['twig']->render('viewprofile.html.twig', array('profile'=>$profile,  'profile_id'=>$_POST['receiver_id'], 'user_id'=>$user_id, 'groups' => $groups, 'id'=>$id));
  });


  return $app;
 ?>
