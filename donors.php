<?php require 'config.php'; need_login();
if(isset($_GET['del'])){ $s=$conn->prepare('DELETE FROM donors WHERE id=?'); $s->bind_param('i',$_GET['del']); $s->execute(); header('Location: donors.php'); exit; }
if($_SERVER['REQUEST_METHOD']=='POST'){
  $p=$_POST; $ld=$p['last_donation']?:null;
  if($p['id']){ $s=$conn->prepare('UPDATE donors SET name=?,age=?,gender=?,contact=?,address=?,blood_group=?,last_donation=? WHERE id=?'); $s->bind_param('sisssssi',$p['name'],$p['age'],$p['gender'],$p['contact'],$p['address'],$p['blood_group'],$ld,$p['id']); }
  else { $s=$conn->prepare('INSERT INTO donors(name,age,gender,contact,address,blood_group,last_donation) VALUES(?,?,?,?,?,?,?)'); $s->bind_param('sisssss',$p['name'],$p['age'],$p['gender'],$p['contact'],$p['address'],$p['blood_group'],$ld); }
  $s->execute(); header('Location: donors.php'); exit; }
$d=['id'=>'','name'=>'','age'=>'','gender'=>'Male','contact'=>'','address'=>'','blood_group'=>'','last_donation'=>''];
if(isset($_GET['edit'])){ $s=$conn->prepare('SELECT * FROM donors WHERE id=?'); $s->bind_param('i',$_GET['edit']); $s->execute(); $d=$s->get_result()->fetch_assoc()?:$d; }
head('Donor Management'); ?>
<form method="post" class="card" onsubmit="return validateForm(this)">
<input type="hidden" name="id" value="<?=e($d['id'])?>">
<input name="name" placeholder="Donor name" value="<?=e($d['name'])?>" required>
<input name="age" type="number" min="18" max="65" placeholder="Age (18-65)" value="<?=e($d['age'])?>" required>
<select name="gender"><?php foreach(['Male','Female','Other'] as $g) echo '<option'.($g==$d['gender']?' selected':'').">$g</option>"; ?></select>
<input name="contact" placeholder="Contact (10 digits)" pattern="[0-9]{10}" value="<?=e($d['contact'])?>" required>
<textarea name="address" placeholder="Address"><?=e($d['address'])?></textarea>
<?=group_select($d['blood_group'])?>
<label>Last donation <input type="date" name="last_donation" value="<?=e($d['last_donation'])?>"></label>
<button><?=$d['id']?'Update':'Add'?> Donor</button></form>
<div class="scroll"><table><tr><th>ID</th><th>Name</th><th>Age</th><th>Gender</th><th>Contact</th><th>Address</th><th>Group</th><th>Last donation</th><th></th></tr>
<?php $r=$conn->query('SELECT * FROM donors ORDER BY id DESC'); while($x=$r->fetch_assoc()){ echo '<tr>'; foreach($x as $v) echo '<td>'.e($v).'</td>'; echo "<td><a href='?edit={$x['id']}'>Edit</a> | <a href='?del={$x['id']}' onclick=\"return confirm('Delete this donor?')\">Delete</a></td></tr>"; } ?>
</table></div><?php foot(); ?>
