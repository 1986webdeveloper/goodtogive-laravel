var EditableTable = function () {

    return {

        //main function to initiate the module
        init: function () {
            var DataTable;
            function restoreRow(DataTable, nRow) {
                var aData = DataTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);

                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    DataTable.fnUpdate(aData[i], nRow, i, false);
                }

                DataTable.fnDraw();
            }
//
//            function editRow(DataTable, nRow) {
//                var aData = DataTable.fnGetData(nRow);
//                var jqTds = $('>td', nRow);
//                jqTds[0].innerHTML = '<input type="text" class="form-control small" value="' + aData[0] + '">';
//                jqTds[1].innerHTML = '<input type="text" class="form-control small" value="' + aData[1] + '">';
//                jqTds[2].innerHTML = '<input type="text" class="form-control small" value="' + aData[2] + '">';
//                jqTds[3].innerHTML = '<input type="text" class="form-control small" value="' + aData[3] + '">';
//                jqTds[4].innerHTML = '<a class="edit" href="">Save</a>';
//                jqTds[5].innerHTML = '<a class="cancel" href="">Cancel</a>';
//            }

            function editStaticpageRow(DataTable, nRow) {
                var aData = DataTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                jqTds[0].innerHTML = '<input type="text" class="form-control small" name="name" value="' + aData['name'] + '">';
                jqTds[1].innerHTML = '<input type="text" class="form-control small" name="permalink" value="' + aData['permalink'] + '">';
                jqTds[2].innerHTML = '<a class="edit" href="">Save</a>';
            }

            function editUserpageRow(DataTable, nRow) {
                var aData = DataTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                jqTds[0].innerHTML = '<input type="text" class="form-control small" name="first_name" value="' + aData['first_name'] + '">';
                jqTds[1].innerHTML = '<input type="text" class="form-control small" name="last_name" value="' + aData['last_name'] + '">';
                jqTds[2].innerHTML = '<input type="text" class="form-control small" name="user_name" value="' + aData['user_name'] + '">';
                jqTds[3].innerHTML = '<input type="text" class="form-control small" name="attribute_name" value="' + aData['attribute_name'] + '">';
                jqTds[4].innerHTML = '<input type="text" class="form-control small" name="password" value="' + aData['password'] + '">';
                jqTds[5].innerHTML = '<select name="user_type" class="form-control">\n\
                                        <option value="customer"' + ((aData['user_type'] == "customer") ? "selected" : "") + '>customer</option>\n\
                                        <option value="vendor" ' + ((aData['user_type'] == "vendor") ? "selected" : "") + '>vendor</option>\n\
                                        <option value="sub_admin" ' + ((aData['user_type'] == "sub_admin") ? "selected" : "") + '>sub_admin</option>\n\
                                        <option value="super_admin" ' + ((aData['user_type'] == "super_admin") ? "selected" : "") + '>super_admin</option>\n\
                                    </select>';
                jqTds[6].innerHTML = '<input type="text" class="form-control small" name="agent_code" value="' + aData['agent_code'] + '">';
                jqTds[7].innerHTML = '<input type="text" class="form-control small" name="email_id" value="' + aData['email_id'] + '">';
                jqTds[8].innerHTML = '<input type="text" class="form-control small" name="ref_code" value="' + aData['ref_code'] + '">';
                jqTds[9].innerHTML = '<select name="status" class="form-control">\n\
                                        <option value="1"' + ((aData['status'] == "1") ? "selected" : "") + '>1</option>\n\
                                        <option value="0" ' + ((aData['status'] == "0") ? "selected" : "") + '>0</option>\n\
                                    </select>';
                jqTds[10].innerHTML = '<a class="edit" href="">Save</a>';
            }

//            function saveRow(DataTable, nRow) {
//                var jqInputs = $('input', nRow);
//                DataTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
//                DataTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
//                DataTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
//                DataTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
//                DataTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 4, false);
//                DataTable.fnUpdate('<a class="delete" href="">Delete</a>', nRow, 5, false);
//                DataTable.fnDraw();
//            }

            function saveStaticpageRow(DataTable, nRow) {
                var jqInputs = $('input', nRow);
                var Dataarray = {};
                $.each(jqInputs, function (key, data) {
                    Dataarray[data.name] = data.value;
                });
                Dataarray["id"] = $(nRow).attr("id");
                $.ajax({
                    url: '/admin/edit_static_page',
                    type: 'POST',
                    data: Dataarray,
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (response) {
                        DataTable.fnDraw();
                    },
                    error: function (e, a, b) {
                        console.log(e, a, b);
                    }
                });

            }
            function saveUserpageRow(DataTable, nRow) {
                var jqInputs = $('input', nRow);
                var jqSelects = $('select', nRow);
                var Dataarray = {};
                $.each(jqInputs, function (key, data) {
                    Dataarray[data.name] = data.value;
                });
                $.each(jqSelects, function (key, data) {
                    Dataarray[data.name] = data.value;
                });

                $.ajax({
                    url: '/admin/edit_user_page',
                    type: 'POST',
                    data: Dataarray,
                    beforeSend: function () {
//                                $loading.removeClass('hide');
                    },
                    complete: function () {
//                                $loading.addClass('hide');
                    },
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (e, a, b) {
                        console.log(e, a, b);
                    }
                });
                DataTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                DataTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
                DataTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
                DataTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
                DataTable.fnUpdate(jqInputs[4].value, nRow, 4, false);
                DataTable.fnUpdate(jqInputs[5].value, nRow, 5, false);
                DataTable.fnUpdate(jqSelects[0].value, nRow, 6, false);
                DataTable.fnUpdate(jqInputs[6].value, nRow, 7, false);
                DataTable.fnUpdate(jqInputs[7].value, nRow, 8, false);
                DataTable.fnUpdate(jqInputs[8].value, nRow, 9, false);
                DataTable.fnUpdate(jqSelects[1].value, nRow, 10, false);
                DataTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 11, false);
                DataTable.fnUpdate('<a class="delete" href="">Delete</a>', nRow, 12, false);
                DataTable.fnDraw();
            }

//            function cancelEditRow(DataTable, nRow) {
//                var jqInputs = $('input', nRow);
//                DataTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
//                DataTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
//                DataTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
//                DataTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
//                DataTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 4, false);
//                DataTable.fnDraw();
//            }

            function assignDataTable($table) {

                var onReady = true;
                var data_ajax_url = $($table).attr('data-ajax-url');
                var colspan = $($table).find('thead').find('tr').find('th');
                var $order = $($table).attr("data-order-cols");
                var $order_type = $($table).attr("data-order-type");
                var $aoColumns = [];
                var $columns = [];
                var sColumns = [];
                var sColumnsSearch = [];
                colspan.each(function () {
                    var bSortable = (typeof $(this).attr('data-table-sort') !== typeof undefined && $(this).attr('data-table-sort') === "true") ? true : false;
                    var bSearchable = (typeof $(this).attr('data-table-search') !== typeof undefined && $(this).attr('data-table-search') === "true") ? true : false;
                    var aoColumns = {};
                    if ($(this).attr('data-table-search') === "true") {
                        sColumnsSearch.push({"name": $(this).attr('data-name'), "text": $(this).text()});
                    }
                    aoColumns["bSearchable"] = (typeof bSearchable !== typeof undefined) ? bSearchable : false;
                    aoColumns["bSortable"] = (typeof bSortable !== typeof undefined) ? bSortable : false;
                    aoColumns["sWidth"] = $(this).attr('data-width') + "%";
                    aoColumns["mData"] = $(this).attr('data-name');
                    aoColumns["sClass"] = $(this).attr('data-name');
                    if (typeof $(this).attr('data-class') !== typeof undefined && $(this).attr('data-class') !== null) {
                        aoColumns["sClass"] = aoColumns["sClass"] + " " + $(this).attr('data-class');
                    }
                    sColumns.push($(this).attr('data-name'));

                    $aoColumns.push(aoColumns);
                    $columns.push({"data": $(this).attr('data-name')});
                });
                DataTable = $($table).DataTable({
                    "processing": true,
                    "serverSide": true,
                    "columns": $columns,
                    "aoColumns": $aoColumns,
                    "columnFilter": $aoColumns,
                    "oLanguage": {
                        "sSearch": "Search",
                        "sLengthMenu": "_MENU_ records per page",
                        "oPaginate": {
                            "sPrevious": "Prev",
                            "sNext": "Next"
                        }
                    },
                    "sPaginationType": "bootstrap",
                    "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
                    "bFilter": (sColumnsSearch.length > 0),
                    "aaSorting": [[$order, $order_type]],
                    "retrieve": true,
                    "bProcessing": false,
                    "bServerSide": true,

                    "ajax": {
                        "url": "/admin/" + data_ajax_url,
                        "data": function (aoData) {
                            console.log(aoData);
                        }
                    }
                });

//                DataTable = $($table).dataTable({
//                    "aLengthMenu": [
//                        [5, 15, 20, -1],
//                        [5, 15, 20, "All"] // change per page values here
//                    ],
//                    "iDisplayLength": 5,
//                    "columns": $columns,
//                    "aoColumns": $aoColumns,
//                    "columnFilter": $aoColumns,
//                    "oLanguage": {
//                        "sSearch": "Search",
//                        "sLengthMenu": "_MENU_ records per page",
//                        "oPaginate": {
//                            "sPrevious": "Prev",
//                            "sNext": "Next"
//                        }
//                    },
//                    "sPaginationType": "bootstrap",
//                    "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
//                    "bFilter": (sColumnsSearch.length > 0),
//                    "aaSorting": [[$order, $order_type]],
//                    "retrieve": true,
//                    "bProcessing": false,
//                    "bServerSide": true,
//                    "sAjaxSource": "/admin/"+data_ajax_url,
//                    "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
//                       
//                        $.each(aoData, function (i, val) {
//                            if (val.name === 'sColumns') {
//                                aoData[i].value = sColumns.join(',');
//                            }
//                        });
//                        $.getJSON(sSource, aoData, function (json) {
//                            console.log(json);
//                            fnCallback(json);
////                        dataTableAction();
//                        });
//                    },
//                    "fnDrawCallback": function (settings) {
//                    },
//                    "fnPreDrawCallback": function (settings) {
//
//                    }
//                });
            }
            assignDataTable("#editable-sample");
            jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            jQuery('#editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown

            var nEditing = null;

//            $('.static_page #editable-sample_new').click(function (e) {
//                e.preventDefault();
//                var aiNew = DataTable.fnAddData(['', '', '', '',
//                        '<a class="edit" href=""><i class="fa fa-edit"></i></a>', '<a class="cancel" data-mode="new" href="">Cancel</a>'
//                ]);
//                var nRow = DataTable.fnGetNodes(aiNew[0]);
//                editStaticpageRow(DataTable, nRow);
//                nEditing = nRow;
//            });


            $('#editable-sample a.delete').live('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var result = confirm("Are you sure you want to delete this rowS?");
                if (result) {
                    window.location.href = $(this).attr('href');
                    return true;
                } else {
//                        throw new Error('cancel');
                    return false;
                }
            });
            
            $('.static_page #editable-sample a.cancel').live('click', function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    DataTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(DataTable, nEditing);
                    nEditing = null;
                }
            });

            $('.static_page #editable-sample a.edit').live('click', function (e) {
                e.preventDefault();

                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];

                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(DataTable, nEditing);
                    editStaticpageRow(DataTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && this.innerHTML == "Save") {
                    /* Editing this row and want to save it */
                    saveStaticpageRow(DataTable, nEditing);
//                        nEditing = null;
                } else {
                    /* No edit in progress - let's start one */
                    editStaticpageRow(DataTable, nRow);
                    nEditing = nRow;
                }
            });


            $('.user_page #editable-sample a.edit').live('click', function (e) {
                e.preventDefault();

                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];

                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(DataTable, nEditing);
                    editUserpageRow(DataTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && this.innerHTML == "Save") {
                    /* Editing this row and want to save it */
                    saveUserpageRow(DataTable, nEditing);
//                        nEditing = null;
                } else {
                    /* No edit in progress - let's start one */
                    editUserpageRow(DataTable, nRow);
                    nEditing = nRow;
                }
            });

//            $('#editable-sample a.delete').live('click', function (e) {
//                e.preventDefault();
//
//                if (confirm("Are you sure to delete this row ?") == false) {
//                    return;
//                }
//
//                var nRow = $(this).parents('tr')[0];
//                DataTable.fnDeleteRow(nRow);
//                alert("Deleted! Do not forget to do some ajax to sync with backend :)");
//            });

//            $('#editable-sample a.cancel').live('click', function (e) {
//                e.preventDefault();
//                if ($(this).attr("data-mode") == "new") {
//                    var nRow = $(this).parents('tr')[0];
//                    DataTable.fnDeleteRow(nRow);
//                } else {
//                    restoreRow(DataTable, nEditing);
//                    nEditing = null;
//                }
//            });

//            $('#editable-sample a.edit').live('click', function (e) {
//                e.preventDefault();
//
//                /* Get the row as a parent of the link that was clicked on */
//                var nRow = $(this).parents('tr')[0];
//
//                if (nEditing !== null && nEditing != nRow) {
//                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
//                    restoreRow(DataTable, nEditing);
//                    editRow(DataTable, nRow);
//                    nEditing = nRow;
//                } else if (nEditing == nRow && this.innerHTML == "Save") {
//                    /* Editing this row and want to save it */
//                    saveRow(DataTable, nEditing);
//                    nEditing = null;
//                    alert("Updated! Do not forget to do some ajax to sync with backend :)");
//                } else {
//                    /* No edit in progress - let's start one */
//                    editRow(DataTable, nRow);
//                    nEditing = nRow;
//                }
//            });
        }

    };

}();