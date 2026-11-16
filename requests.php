<?php require 'config.php'; need_login();
if(isset($_GET['id'],$_GET['do']) && in_array($_GET['do'],['approved','rejected'])){
  $s=$conn->prepare("UPDATE requests SET status=? WHERE id=? AND status='pending'"); $s->bind_param('si',$_GET['do'],$_GET['id']); $s->execute(); header('Location: requests.php'); exit; }
head('Blood Requests'); ?>
<div class="scroll"><table><tr><th>ID</th><th>Requester</th><th>Contact</th><th>Group</th><th>Units</th><th>Date</th><th>Status</th><th>Available</th><th></th></tr>
<?php $r=$conn->query("SELECT r.*,(SELECT COALESCE(SUM(quantity),0) FROM blood_units b WHERE b.blood_group=r.blood_group AND b.expiry_date>=CURDATE()) av FROM requests r ORDER BY id DESC");
while($x=$r->fetch_assoc()){ echo "<tr><td>{$x['id']}</td><td>".e($x['requester'])."</td><td>".e($x['contact'])."</td><td>{$x['blood_group']}</td><td>{$x['quantity']}</td><td>{$x['request_date']}</td><td><span class='badge {$x['status']}'>{$x['status']}</span></td><td>{$x['av']}</td><td>";
 if($x['status']=='pending') echo "<a href='?id={$x['id']}&do=approved'>Approve</a> | <a href='?id={$x['id']}&do=rejected'>Reject</a>"; echo '</td></tr>'; } ?>
</table></div><?php foot(); ?>
