<?php
    
?>
<h3>My Goals</h3>
<div style="padding:20px;" class="mb-3 card">
  <div class="row">
    <div id="all" style="cursor: pointer;" class="col-sm text-primary"  onclick="filterByCompleted(1)">
    All
    </div>
    <div id="incomplete" style="cursor: pointer;" class="col-sm text-muted" onclick="filterByCompleted(0)">
    Not bought
    </div>
    <div id="completed" style="cursor: pointer;" class="col-sm text-muted" onclick="filterByCompleted(1)">
    Bought
    </div>
    <div class="col-sm">
    <form method="post">
        <input type="hidden" name="action" value="create_goal">
        <input type="submit" class="btn btn-primary" value="Create a Goal">
    </form>
    </div>
  </div>
</div>

<div class="mb-3 card" style="min-height:80%;">
<table class="table table-sm">
  <thead>
    <tr>
      <!-- <th></th> -->
      <th>Name</th>
      <th>Category</th>
      <th>Status</th>
      <th>Created</th>
    </tr>
  </thead>
  <tbody id="showGoals">
  <?php 
    foreach($purchases as $purchase) {
  ?>
    <tr style="cursor: pointer;" onclick="window.location='/purchase/<?= $purchase->id ?>';" class="<?= $purchase->status ? 'text-muted' : '' ?> ">
      <!-- <th scope="row"><i class="fa fa-check btn-outline-success"></i></th> -->
      <td><i style="margin-right:10px;" class="fa fa-check"></i><?= $purchase->name ?></td>
      <td><?= $purchase->category_id ?></td>
      <td><?= $purchase->status ?></td>
      <td><?= date("d.m.Y H:i", strtotime($purchase->created)) ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
</div>