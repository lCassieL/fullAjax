<h3>My List</h3>
<div style="padding:20px;" class="mb-3 card">
  <div class="row">
    <div id="all" style="cursor: pointer;" class="col-1 text-primary"  onclick="checkActived('all')">All</div>
    <div id="incomplete" style="cursor: pointer;" class="col-2 text-muted" onclick="checkActived(0)">Not bought</div>
    <div id="completed" style="cursor: pointer;" class="col-2 text-muted" onclick="checkActived(1)">Bought</div>
    <div class="col-sm">
        <select onchange="filterBy()" id="select_filter" class="form-control hasCustomSelect" style="">
            <option value="all">-----</option>
            <?php foreach($categories as $category) { ?>
            <option value="<?= $category->id ?>"><?= $category->name ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-sm">
        <button data-toggle="modal" data-target="#addCategory" type="button" class="btn btn-primary" onclick="clearCategoryModal()">Add category</button>
    </div>
    <div class="col-sm">
        <button data-toggle="modal" data-target="#addPurchase" type="button" class="btn btn-primary" onclick="clearPurchaseModal()">Add purchase</button>
    </div>
  </div>
</div>

<div class="mb-3 card" style="min-height:80%;">
<table class="table table-sm">
  <thead>
    <tr>
      <th>Name</th>
      <th>Category</th>
      <th>Status</th>
      <th>Created</th>
      <th style="text-align:center;"><i class="fa fa-check"></i></th>
      <th style="text-align:center;"><i class="fa fa-trash"></i></th>
    </tr>
  </thead>
  <tbody id="showGoals">
  <?php foreach($purchases as $purchase) { ?>
    <tr class="w-50 <?= (int)$purchase->status ? 'text-muted' : '' ?> ">
      <td><?= $purchase->name ?></td>
      <td><?= $purchase->category ?></td>
      <td><?= (int)$purchase->status ? 'bought' : 'not bought' ?></td>
      <td><?= date("d.m.Y H:i", strtotime($purchase->created)) ?></td>
      <td style="text-align:center;">
          <button type="button" class="btn <?= (int)$purchase->status ? 'btn-primary' : 'btn-secondary' ?>" style="cursor: pointer;" onclick="toggleStatus(this, <?= $purchase->id ?>, <?= $purchase->status ?>)"><i class="fa fa-check"></i></button>
      </td>
      <td style="text-align:center;">
          <button type="button" class="btn btn-danger" style="cursor: pointer;" onclick="deletePurchase(<?= $purchase->id ?>)"><i class="fa fa-trash"></i></button>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
</div>

<!-- Modal Purchase -->
<div class="modal fade" id="addPurchase" tabindex="-1" role="dialog" aria-labelledby="addPurchaseTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPurchaseLongTitle">Create purchase</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div style="padding:20px;" class="container mb-3 card">
        <form name="createPurchaseForm" class="add" method="post" onsubmit="return createPurchase()">
          <div class="form-group">
            <label for="name">Text:</label>
            <input type="text" name="name" class="form-control" id="purchase_name" value ="" required>
            <div id="purchase_name_error" class="invalid-feedback">
            </div>
          </div>
          <div class="form-group">
            <label for="custom-select">Category:</label>
              <select class="custom-select" name="category_select" id="category_select">
              <?php foreach($categories as $category) { ?>
                <option value="<?= $category->id ?>"><?= $category->name ?></option>
              <?php } ?>
              </select>
              <div id="purchase_category_error" class="invalid-feedback">
              </div>
          </div>
          <input type="submit" class="btn btn-primary" value="Create">
          <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
    </div>
  </div>
</div>


<!-- Modal Category -->
<div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="addCategoryTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryLongTitle">Create category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div style="padding:20px;" class="container mb-3 card">
        <form name="createCategoryForm" class="add" method="post" onsubmit="return createCategory()">
          <div class="form-group">
            <label for="category_name">Text:</label>
            <input type="text" name="category_name" class="form-control" id="category_name" value ="" required>
            <div id='category_error' class="invalid-feedback">
            </div>
          </div>
          <input type="submit" class="btn btn-primary" value="Create">
          <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
    </div>
  </div>
</div>