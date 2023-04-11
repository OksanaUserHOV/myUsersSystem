<?php 

require_once 'connect.php';

  $select = "SELECT * FROM `users` ORDER BY `id`";
  $query = $pdo->query($select);
  $rows = $query->fetchAll( PDO::FETCH_ASSOC);

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Users table</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
  <div class="container">
    <div class="row flex-lg-nowrap">
      <div class="col">
        <div class="row flex-lg-nowrap">
          <div class="col mb-3">
            <div class="e-panel card">
              <div class="card-header">
                <form>
                  <div class="form-row mt-3">
                    <div class="form-group col-md-4 d-flex justify-content-center">
                      <button type="button" class="btn btn-outline-primary px-5 addUser" data-toggle="modal"  data-target="#user-form-modal">Add</button>
                    </div>
                    <div class="form-group col-md-4">
                      <select class="form-control select-action" >
                        <option value="-Please Select-">-Please Select-</option>
                        <option value="Set active">Set active</option>
                        <option value="Set not active">Set not active</option>
                        <option value="Delete">Delete</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 d-flex justify-content-center">
                      <button type="button" data-toggle="modal"  data-target="#modal-alert" class="btn btn-outline-primary px-5 btnOK">OK</button>
                    </div>                                        
                  </div>
                <form>                 
              </div>
              <div class="card-body">
                <div class="card-title">
                  <h6 class="mr-2"><span>Users</span></h6>
                </div>
                <div class="e-table">
                  <div class="table-responsive table-lg mt-3">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th class="align-top">
                            <div
                              class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0">
                              <input type="checkbox" class="custom-control-input" id="all-items">
                              <label class="custom-control-label" for="all-items"></label>
                            </div>
                          </th>
                          <th class="max-width">Name</th>
                          <th class="sortable">Role</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($rows as $row): ?>
                        <tr data-user-id="<?= $row['id']?>">
                          <td class="align-middle">
                            <div
                              class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top">
                              <input type="checkbox" class="custom-control-input" id="item-<?= $row['id']  ?>">
                              <label class="custom-control-label" for="item-<?= $row['id']  ?>"></label>
                            </div>
                          </td>
                          <td class="text-nowrap align-middle"><span class="first-name"><?= $row['name_first']?></span> <span class=" last-name"><?= $row['name_last']?></span></td>
                          <td class="text-nowrap align-middle role" data-role="<?= $row['role']?>">
                            <span><?= ucfirst($row['role']) ?></span>
                          </td>
                          <td class="text-center align-middle"><i class="<?= $row['status'] ? 'fa fa-circle active-circle' : 'fa fa-circle not-active-circle' ?>"></i></td>
                          <td class="text-center align-middle">
                            <div class="btn-group align-top">
                              <button class="btn btn-sm btn-outline-secondary badge edit" type="button" data-toggle="modal" data-target="#user-form-modal">Edit</button>
                              <button class="btn btn-sm btn-outline-secondary badge delete-user" type="button" data-toggle="modal" data-target="#modal-delete" data-delete-user-id="<?= $row['id']?>">   <i  class="fa fa-trash"></i></button>
                            </div>
                          </td>
                        </tr>                          
                        <?php endforeach ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <form>
                  <div class="form-row mt-3">
                    <div class="form-group col-md-4 d-flex justify-content-center">
                      <button type="button" class="btn btn-outline-primary px-5 addUser" data-toggle="modal"  data-target="#user-form-modal">Add</button>
                    </div>
                    <div class="form-group col-md-4">
                      <select class="form-control select-action" >
                        <option value="-Please Select-">-Please Select-</option>
                        <option value="Set active">Set active</option>
                        <option value="Set not active">Set not active</option>
                        <option value="Delete">Delete</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 d-flex justify-content-center">
                      <button type="button" data-toggle="modal"  data-target="#modal-alert" class="btn btn-outline-primary px-5 btnOK">OK</button>
                    </div>                                        
                  </div>
                <form>                

              </div>
            </div>
          </div>
        </div>

        <!-- User Form Modal -->
        
        <div class="modal fade" id="user-form-modal" tabindex="-1" aria-labelledby="user-form-modal" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="UserModalLabel">Add user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="user-form">
                  <div class="form-group">
                    <label for="first-name" class="col-form-label">First Name:</label>
                    <input type="text" class="form-control" id="first-name">
                  </div>
                  <div class="form-group">
                    <label for="last-name" class="col-form-label">Last Name:</label>
                    <input type="text" class="form-control" id="last-name">
                  </div>
                  <div class="form-group pb-4">
                            <div ><label>Not active / active:</label></div>
                            <div class="checkbox"><input type="checkbox" id="checkbox_status" ></div>                    
                        
                  </div> 
                  <div class="form-group">
                        <select name="role" class="form-control" id="select-role">
                          <option>-Please Select-</option>
                          <option>user</option>
                          <option>admin</option>
                        </select>
                      </div>               
                </form>

                <div class="alert alert-danger msg d-none mt-4" role="alert"></div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btnSave" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>
        </div>

        <!-- alert modal  -->
        <div class="modal fade" tabindex="-1" id="modal-alert" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Warning!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="modal-body-alert">
                <p></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>            
              </div>
            </div>
          </div>
        </div>


        <!-- confirm modal btn delete -->
        <div class="modal fade" tabindex="-1" id="modal-delete" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Warning!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Are you sure you want to delete the user?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btnDelete"  class="btn btn-primary">Delete</button>
              </div>
            </div>
          </div>
        </div>


      <!-- </div> -->
    </div>
  </div>
<script src="main.js"></script>
</body>
</html>