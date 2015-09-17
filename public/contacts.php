<!DOCTYPE html>
<html lang="br">
<head>
<title>AAAAAAAAAA</title>
<meta charset="utf-8">
<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="../bower_components/bootstrap-table/dist/bootstrap-table.min.css" type="text/css" />
<link rel="stylesheet" href="assets/js/formvalidation/dist/css/formValidation.min.css" type="text/css" />
<link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css" type="text/css" />
<link rel="stylesheet" href="assets/css/style.css" type="text/css" />
<link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Dosis:400,500,600' type='text/css'>

<link rel="stylesheet" href="//rawgit.com/vitalets/x-editable/master/dist/bootstrap3-editable/css/bootstrap-editable.css">
</head>
<body>

<main>
<div id="toolbar">
    

</div>


<table id="table"
    data-toolbar="#toolbar"
    data-search="true"
    data-show-refresh="true"
    data-show-toggle="true"
    data-show-columns="true"
    data-detail-view="true"
    data-detail-formatter="detailFormatter"

    data-minimum-count-columns="2"
    data-show-pagination-switch="true"

    data-url="api/v1/contacts"  
    
    data-pagination="true"
    data-id-field="id"
    data-show-footer="true">

<thead></thead>
</table>
</main>

<div class="modal fade" id="modalTable" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal table</h4>
            </div>
            <div class="modal-body">
                <table id="table2"
                       data-toggle="table"
                       data-height="299"
                       data-show-footer="true"
                       >
                    <thead>
                    <tr>
                   

                        <th data-field="id">ID</th>
                        <th data-field="body" 
                        data-footer-formatter="totalNameFormatter">Item Name</th>
                        <th data-field="contact_id">Item Price</th>
    <th data-field="operate" data-formatter="operateFormatter2" 
    data-events="operateEvents2">Operate</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->





<!-- Modal -->
<div class="modal fade" id="modalContacts" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4></h4>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="formContacts" role="form">
                    <input type="hidden" name="id" value="">
                    <div class="form-group">
                        <label for="firstname">
                            <span class="glyphicon glyphicon-user"></span>
                             FirstName
                         </label>
                        <input type="text" name="firstname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">
                            <span class="glyphicon glyphicon-email"></span> 
                            Email
                        </label>
                        <input type="text" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="fone">
                            <span class="glyphicon glyphicon-phone"></span> 
                            Fone
                        </label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="favorite" value="1">Fav
                        </label>
                    </div>
                    <button class="btn btn-success btn-block">save</button>
                </form>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-danger btn-default pull-right" data-dismiss="modal">
                    <span class="glyphicon glyphicon-remove"></span> Cancel
                </button>
            </div><!-- .modal-footer -->
        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div><!-- .modal -->



</body>

<script type="text/javascript" src="../bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script> 

<script type="text/javascript" src="../bower_components/bootstrap-table/dist/bootstrap-table.js"></script>


<script type="text/javascript" src="../bower_components/bootstrap-table/dist/extensions/flat-json/bootstrap-table-flat-json.js"></script>

<script type="text/javascript" src="../bower_components/bootstrap-table/dist/extensions/editable/bootstrap-table-editable.js"></script>
<script src="//rawgit.com/vitalets/x-editable/master/dist/bootstrap3-editable/js/bootstrap-editable.js"></script>


<script type="text/javascript" src="assets/js/formvalidation/dist/js/formValidation.min.js"></script>
<script type="text/javascript" src="assets/js/formvalidation/dist/js/framework/bootstrap.min.js"></script>

<script type="text/javascript" src="assets/js/script.js"></script>

</html>
