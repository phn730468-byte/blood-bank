<?php require 'config.php'; need_login();
if(isset($_GET['del'])){ $s=$conn->prepare('DELETE FROM blood_units WHERE id=?'); $s->bind_param('i',$_GET['del']); $s->execute(); header('Location: inventory.php'); exit; }
if($_SERVER['REQUEST_METHOD']=='POST'){ $p=$_POST; $did=$p['donor_id']?:null;
  $s=$conn->prepare('INSERT INTO blood_units(donor_id,blood_group,quantity,collection_date,expiry_date,storage) VALUES(?,?,?,?,?,?)');
  $s->bind_param('isisss',$did,$p['blood_group'],$p['quantity'],$p['collection_date'],$p['expiry_date'],$p['storage']); $s->execute();
  if($did){ $u=$conn->prepare('UPDATE donors SET last_donation=? WHERE id=?'); $u->bind_param('si',$p['collection_date'],$did); $u->execute(); }
  header('Location: inventory.php'); exit; }
head('Blood Inventory'); ?>
<form method="post" class="card">
<select name="donor_id"><option value="">Donor (optional)</option><?php $r=$conn->query('SELECT id,name,blood_group FROM donors'); while($x=$r->fetch_assoc()) echo "<option value='{$x['id']}'>".e($x['name'].' ('.$x['blood_group'].')')."</option>"; ?></select>
<?=group_select()?>
<input name="quantity" type="number" min="1" placeholder="Units" required>
<label>Collection date <input type="date" name="collection_date" id="cdate" value="<?=date('Y-m-d')?>" required></label>
<label>Expiry date (35 days) <input type="date" name="expiry_date" id="edate" value="<?=date('Y-m-d',strtotime('+35 days'))?>" required></label>
<input name="storage" placeholder="Storage (e.g. Fridge 1, Rack B)">
<button>Add Blood Units</button></form>
<div class="scroll"><table><tr><th>ID</th><th>Group</th><th>Units</th><th>Collected</th><th>Expiry</th><th>Storage</th><th>Status</th><th></th></tr>
<?php $r=$conn->query('SELECT * FROM blood_units ORDER BY expiry_date');
while($x=$r->fetch_assoc()){ $days=(strtotime($x['expiry_date'])-strtotime(date('Y-m-d')))/86400;
 $st=$days<0?'<b class="err">Expired</b>':($days<=7?'<b class="warn">Expiring soon</b>':'OK');
 echo "<tr><td>{$x['id']}</td><td>{$x['blood_group']}</td><td>{$x['quantity']}</td><td>{$x['collection_date']}</td><td>{$x['expiry_date']}</td><td>".e($x['storage'])."</td><td>$st</td><td><a href='?del={$x['id']}' onclick=\"return confirm('Delete?')\">Delete</a></td></tr>"; } ?>
</table></div><?php foot(); ?>
