<?php require 'config.php'; $msg='';
if($_SERVER['REQUEST_METHOD']=='POST'){ $p=$_POST;
  $s=$conn->prepare('INSERT INTO requests(requester,contact,blood_group,quantity,request_date) VALUES(?,?,?,?,CURDATE())');
  $s->bind_param('sssi',$p['requester'],$p['contact'],$p['blood_group'],$p['quantity']); $s->execute(); $msg='Request submitted. Reference ID: '.$conn->insert_id; }
head('Blood Request'); if($msg) echo '<p class="ok">'.e($msg).'</p>'; ?>
<form method="post" class="card" style="max-width:420px;margin:auto">
<input name="requester" placeholder="Hospital / requester name" required>
<input name="contact" placeholder="Contact (10 digits)" pattern="[0-9]{10}" required>
<?=group_select()?>
<input name="quantity" type="number" min="1" placeholder="Units required" required>
<button>Submit Request</button><p><a href="login.php">Admin login</a></p></form><?php foot(); ?>
