<?php require 'config.php';
if($conn->query('SELECT COUNT(*) c FROM admin')->fetch_assoc()['c']==0){ // first run: default admin
  $h=password_hash('admin123',PASSWORD_DEFAULT); $conn->query("INSERT INTO admin(username,password) VALUES('admin','$h')"); }
$err='';
if($_SERVER['REQUEST_METHOD']=='POST'){
  $s=$conn->prepare('SELECT id,password FROM admin WHERE username=?'); $s->bind_param('s',$_POST['username']); $s->execute();
  $r=$s->get_result()->fetch_assoc();
  if($r && password_verify($_POST['password'],$r['password'])){ session_regenerate_id(true); $_SESSION['admin']=$r['id']; header('Location: dashboard.php'); exit; }
  $err='Invalid username or password';
}
head('Admin Login'); ?>
<form method="post" class="card" style="max-width:340px;margin:auto">
<?php if($err) echo '<p class="err">'.e($err).'</p>'; ?>
<input name="username" placeholder="Username" required>
<input name="password" type="password" placeholder="Password" required>
<button>Login</button>
<p><a href="request.php">Hospital? Submit blood request</a></p>
<small>Default: admin / admin123</small></form>
<?php foot(); ?>
