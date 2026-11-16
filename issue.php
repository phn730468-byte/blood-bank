<?php require 'config.php'; need_login(); $msg='';
if(isset($_GET['issue'])){
  $conn->begin_transaction();
  try{
    $s=$conn->prepare("SELECT * FROM requests WHERE id=? AND status='approved' FOR UPDATE"); $s->bind_param('i',$_GET['issue']); $s->execute();
    $rq=$s->get_result()->fetch_assoc(); if(!$rq) throw new Exception('Request not found or not approved');
    $need=$rq['quantity'];
    $u=$conn->prepare('SELECT id,quantity FROM blood_units WHERE blood_group=? AND quantity>0 AND expiry_date>=CURDATE() ORDER BY expiry_date FOR UPDATE');
    $u->bind_param('s',$rq['blood_group']); $u->execute(); $rows=$u->get_result()->fetch_all(MYSQLI_ASSOC);
    if(array_sum(array_column($rows,'quantity'))<$need) throw new Exception('Insufficient stock for '.$rq['blood_group']);
    foreach($rows as $b){ if($need<=0) break; $t=min($need,$b['quantity']);
      $c=$conn->prepare('UPDATE blood_units SET quantity=quantity-? WHERE id=?'); $c->bind_param('ii',$t,$b['id']); $c->execute(); $need-=$t; }
    $i=$conn->prepare('INSERT INTO issues(request_id,blood_group,quantity,issue_date) VALUES(?,?,?,CURDATE())'); $i->bind_param('isi',$rq['id'],$rq['blood_group'],$rq['quantity']); $i->execute();
    $conn->query("UPDATE requests SET status='issued' WHERE id=".(int)$rq['id']);
    $conn->commit(); $msg='<p class="ok">Blood issued and inventory updated.</p>';
  }catch(Exception $ex){ $conn->rollback(); $msg='<p class="err">'.e($ex->getMessage()).'</p>'; } }
head('Issue Blood'); echo $msg; ?>
<h3>Approved requests (ready to issue)</h3>
<div class="scroll"><table><tr><th>ID</th><th>Requester</th><th>Group</th><th>Units</th><th></th></tr>
<?php $r=$conn->query("SELECT id,requester,blood_group,quantity FROM requests WHERE status='approved'");
while($x=$r->fetch_assoc()) echo "<tr><td>{$x['id']}</td><td>".e($x['requester'])."</td><td>{$x['blood_group']}</td><td>{$x['quantity']}</td><td><a href='?issue={$x['id']}' onclick=\"return confirm('Issue blood now?')\">Issue</a></td></tr>"; ?>
</table></div>
<h3>Issue history</h3>
<?php table($conn->query('SELECT i.id,i.request_id,i.blood_group,i.quantity,i.issue_date FROM issues i ORDER BY i.id DESC'),['Issue ID','Request ID','Group','Units','Date']); foot(); ?>
