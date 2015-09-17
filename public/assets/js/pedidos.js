
var $table = $('#table'),
$table2 = $('#table2'),
$remove = $('#remove'),
$add = $("#add"),
selections = [];


$(function () {

    $table.bootstrapTable({
        height: getHeight(),
        flat:true,
        //responseHandler:responseHandler,

        columns: 
        [
                {
                    field: 'state',
                    checkbox: true,
                    align: 'center',
                    valign: 'middle',
                    footerFormatter: function(){ 
                        return [
                        '<button id="del" class="" disabled>',
                        '<i class="fa fa-trash"></i>',
                        '</button>'
                        ].join('');                        
                    }

                }, 
                {
                    field: 'aaa',
                    title: 'Item Operate',
                    align: 'center',
                    events: operateEvents,
                    formatter: operateFormatter
                },

                {
                    title: 'Item ID',
                    field: 'id',
                    align: 'center',
                    formatter:function(){
                        //return $(this).checkbox();
                    },
                    valign: 'middle',
                    sortable: true,
                    footerFormatter: totalTextFormatter
                }, 

                {
                    field: 'categ_id',
                    title: 'categ',
                    sortable: true,
                    footerFormatter: totalNameFormatter,
                    align: 'center'
                },
                {
                    field: 'cliente.nome',
                    title: 'pai',
                    sortable: true,
                    footerFormatter: totalNameFormatter,
                    align: 'center'
                },
                {
                    field: 'firstname',
                    title: 'Item Name',
                    sortable: true,
                    editable: true,
                    footerFormatter: totalNameFormatter,
                    align: 'center'
                },
                {
                    field: 'email',
                    title: 'e-mail',
                    sortable: true,
                    editable: {
                        type: 'select',
                        value: 2,    
                        source: [
                            {value: 1, text: 'Active'},
                            {value: 2, text: 'Blocked'},
                            {value: 3, text: 'Deleted'},
                        ],                        
                        mode: 'popup',
                        title: 'blablaaa',
                            var id = data[index]['id'];
                            var type = 'PATCH';
                            var url = "api/v1/contacts/"+id+"/email";
                        highlight: '#FFFF80',
                        pk:'{id:544}',
                        error: function(response, newValue) {
                            if(response.status === 500) {
                                return 'Service unavailable. Please try later.';
                            } else {
                                return response.responseText;
                            }
                        },
                        url: function(editParams) {
                            var newDescription = editParams.value;

                            var deferredObj = new $.Deferred();

                            function save(callback) {
                                alert('saved, yo.');
                                setTimeout(callback, 1000);
                            }

                            save(function() {
                                alert('resolved, yo.');
                                deferredObj.resolve();
                            });

                            return deferredObj.promise();
                        },


                        url: function(params) {
                            var d = new $.Deferred;
                            if(params.value === 'abc') {
                                return d.reject('error message'); 
                                //returning error via deferred object
                            } else {
                                //async saving data in js model

                                if(res.result==='error'){
                                    alert(res.contact[0].email);
                                    return false;
                                    //return res.contact[0].email;
                                }
                                if(res.result==='OK'){
                                    return '';
                                }


                                someModel.asyncSaveMethod({
                                   //..., 
                                   success: function(){
                                      d.resolve();
                                   }
                                }); 
                                return d.promise();
                            }
                        },

                        validate: function (value) {
                            //console.log('value:'+value);
                            value = $.trim(value);
                            if (!value) {
                                return 'This field is required';
                            }
                            var data = $table.bootstrapTable('getData'),
                                index = $(this).parents('tr').data('index');
                            
                            for(var i in data[index]){
                                console.log('---'+i +'-'+ data[index][i]);
                            }                                    
                            return '';
                        }
                    },
                    footerFormatter: totalNameFormatter,
                    align: 'center'
                },
                {
                    field: 'total',
                    title: 'Item Price',
                    sortable: true,
                    align: 'center',
                    editable: {
                        type: 'text',
                        title: 'Item Price',
                        validate: function (value) {
                            value = $.trim(value);
                            if (!value) {
                                return 'This field is required';
                            }
                            if (!/^-{0,1}\d*\.{0,1}\d+$/.test(value)) {
                                return 'This so m=numeros.'
                            }
                            var data = $table.bootstrapTable('getData'),
                                index = $(this).parents('tr').data('index');
                            console.log('validate:'+data[index]);
                            return '';
                        }
                    },
                    footerFormatter: totalPriceFormatter
                }, 
                {
                    field: 'phone',
                    title: '--fone',
                    sortable: true,
                    editable: true,
                    footerFormatter: totalNameFormatter,
                    align: 'center'
                },
                {
                    field: 'favorite',
                    checkbox: true,
                    footerFormatter: function(){ 
                        return [
                        '<button id="spam" class="" disabled>',
                        '<i class="fa fa-camera-retro"></i>',
                        '</button>'
                        ].join('');                        
                    }
                },
                {
                    field: 'operate',
                    title: 'Item Operate',
                    align: 'center',
                    events: operateEvents,
                    formatter: operateFormatter
                }
        ]
    });
    // sometimes footer render error.
    setTimeout(function () {
        $table.bootstrapTable('resetView');
    }, 200);

/*
    $table.on('editable-save.bs.table',function(field, row, oldValue, $el){
       
        var type = 'PATCH';
        var url = "api/v1/contacts/"+oldValue['id'];
        var chave = row;
        var o = new Object();
        o.email = oldValue[row];
        var data = JSON.stringify(o); //row=campo
        //console.log(data);
        $.ajax({
            url : url,
            type: type,
            data: data
        }).done(function(data){
            $table.bootstrapTable('refresh');
        }).error(function(){
            //alert('erro');
        });
    });    
*/

    $table.on('check.bs.table uncheck.bs.table '+'check-all.bs.table uncheck-all.bs.table', function () {
        $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
        $('#del').prop('disabled', !$table.bootstrapTable('getSelections').length);
        selections = getIdSelections();
    });
    $table.on('expand-row.bs.table', function (e, index, row, $detail) {
        if (index % 2 == 1) {
            $detail.html('Loading from ajax request...');
        }
    });

    $('#modalTable').on('shown.bs.modal', function () {
        $table2.bootstrapTable('resetView');
    });

    $table.on('all.bs.table', function (e, name, args) {
        console.log(name, args);
    });

    //remover varios checkboxes
    $('body').on('click','button#del',function(e){
        e.preventDefault();
        var ids = getIdSelections();
        $table.bootstrapTable('remove', {
            field: 'id',
            values: ids
        });
        $('#del').prop('disabled', true);
    });

    $remove.click(function () {
        var ids = getIdSelections();
        $table.bootstrapTable('remove', {
            field: 'id',
            values: ids
        });
        $remove.prop('disabled', true);
    });
    $(window).resize(function () {
        $table.bootstrapTable('resetView', {
            height: getHeight()
        });
    });

    $('body').on('click','button#add',function(e){
        e.preventDefault();
        $("#modalContacts form").find("input, textarea").val("").removeAttr('checked');
        $("#modalContacts").modal();
    });


     $('#formContacts').formValidation({
            framework: 'bootstrap',
            excluded:  ':disabled',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                firstname: {
                    validators: {
                        notEmpty: {
                            message: 'The username is required'
                        },
                        stringLength: {
                            min: 6,
                            max: 30,
                            message: 'The username must be more than 6 and less than 30 characters long'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9_\.]+$/,
                            message: 'The username can only consist of alphabetical, number, dot and underscore'
                        },
                        blank: {}
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: 'The email address is required'
                        },
                        emailAddress: {
                            message: 'The input is not a valid email address'
                        },
                        blank: {}
                    }
                },
                phone: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required'
                        },
                        blank: {}
                    }
                }
            }
        })
        .on('success.form.fv', function(e) {
            e.preventDefault();

            var $form = $(e.target),
                fv    = $form.data('formValidation');

            $.ajax({
                url: url,
                data: $form.serialize(),
                dataType: 'json'
            }).success(function(response) {
                if (response.result === 'error') {
                    for (var field in response.fields) {
                        fv
                        // Show the custom message
                        .updateMessage(field, 'blank', response.fields[field])
                        // Set the field as invalid
                        .updateStatus(field, 'INVALID', 'blank');
                    }
                } else {
                    // Do whatever you want here
                    // such as showing a modal ...
                }
            });
        });


   $('#modalContacts').on('click','button.btn',function(e){
        e.preventDefault();

        var id = $('form input[type="hidden"][name="id"]').val();

        console.log($("#formContacts").serialize());
        var type = (id=="")? 'POST':'PUT';
        var url = (id=="")? "api/v1/contacts":"api/v1/contacts/"+id;

        $.ajax({
            url:url,
            type:type,
            data: $("#formContacts").serialize()
        }).done(function(data){
            $table.bootstrapTable('refresh');
            document.getElementById('formContacts').reset();
            $("#modalContacts").modal('hide');
        });

    });

});

$.ajaxSetup({
    dataType    : "json", 
    type        : "POST", 
    beforeSend  : function() {
        //$('<i/>').addClass('fa fa-spinner fa-pulse').appendTo($('body')).fadeIn();
    },
    complete    : function() { // 2
        $('#general-ajax-load ').fadeOut();
        console.log( "Request complete." );
    },
    /*
    success     : function(data) { // 1
        $('#general-ajax-load ').fadeOut();
        console.log('success:'+data);
        alert('sucesso');
        $table.bootstrapTable('refresh');
    },
    */
    error : function(xhr, textStatus, errorThrown){
        //console.log(textStatus+'Request could not be completed: '+errorThrown);
    }

});
    

function getIdSelections() {
    return $.map($table.bootstrapTable('getSelections'), function (row) {
        return row.id
    });
}


function detailFormatter(index, row) {
    var html = [];
    $.each(row, function (key, value) {
        html.push('<p><b>' + key + ':</b> ' + value + '</p>');
    });
    return html.join('');
}

function operateFormatter(value, row, index) {
    return [
        '<a class="like" href="javascript:void(0)" title="Like">',
        '<i class="glyphicon glyphicon-heart"></i>',
        '</a>',
        '<a class="remove" href="javascript:void(0)" title="Remove">',
        '<i class="glyphicon glyphicon-remove"></i>',
        '</a>'
    ].join('');
}
//=================================================
function operateFormatter2(value, row, index) {
    return [
        '<a class="like-child" href="javascript:void(0)" title="Like">',
        '<i class="fa fa-heart"></i>',
        '</a>',
        '<a class="del-child" href="javascript:void(0)" title="del">',
        '<i class="fa fa-trash"></i>',
        '</a>'
    ].join('');
}

window.operateEvents = {
    'click .like': function (e, value, row, index) {
        e.preventDefault();
        var $modal = $("#modalContacts");
        $modal.find("h4").empty().attr('data-id',row.id).append($('<p/>').html("Edit #"+row.id))
        
        $modal.find("form input[type='hidden'][name='id']").val(row.id);
        $modal.find("form input[name='firstname']").val(row.firstname);
        $modal.find("form a").empty().html('editar');
        $modal.modal();
        
    },
    'click .remove': function (e, value, row, index) {
        e.preventDefault();
        //alert(row.id);
        $('#modalTable h4.modal-title').empty().html(row.firstname);
        $('#modalTable #table2').attr('data-url','api/v1/contacts/'+row.id+'/notes');
        $('#modalTable').modal();
        //$table.bootstrapTable('removeByUniqueId', row.id);
    }
};

//=================================================
window.operateEvents2 = {
    'click .like-child': function (e, value, row, index) {
        //alert('You click like action, row: ' + JSON.stringify(row));
        e.preventDefault();
        //alert(row.id);
    },
    'click .del-child': function (e, value, row, index) {
        e.preventDefault();
        //$table.bootstrapTable2('removeByUniqueId', row.id);
    }
};

function totalTextFormatter(data) {
    return 'Total';
}

function totalNameFormatter(data) {
    return data.length;
}

function totalPriceFormatter(data) {
    var total = 0;
    $.each(data, function (i, row) {
        total += +(row.val);
    });
    return '$' + total.toFixed(2);
}

function getHeight() {
    return $(window).height() - $('h1').outerHeight(true);
}