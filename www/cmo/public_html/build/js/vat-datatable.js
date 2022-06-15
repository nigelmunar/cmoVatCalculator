var tSearchTimout;
var vatDatatable;

var vatDatatableColumnDefinitions = {
    0: {
        'name': 'id',
        'filter': false,
        'filterType': null
    },
    1: {
        'name': 'ex_vat',
        'filter': false,
        'filterType': null
    },
    2: {
        'name': 'inc_vat',
        'filter': false,
        'filterType': null
    }
    ,
    3: {
        'name': 'vat_rate',
        'filter': false,
        'filterType': null
    }
}

function rendervatFilters(binitialLoad)
{
    
    var $vatDatatable = $('#vat-datatable');
    var $vatDatatableTHead = $vatDatatable.find('thead');

    if(!binitialLoad)
    {
        $vatDatatableTHead.find('tr:last').remove();
    }

    $vatDatatableTHead.append('<tr></tr>');
    $vatDatatable.dataTable().api().columns().every( function (i) {
        if(this.visible())
        {
            $vatDatatableTHead.find('tr:eq(1)').append('<th></th>');

            if(vatDatatableColumnDefinitions[i].filter)
            {
                var column = this;

                if(vatDatatableColumnDefinitions[i].filterType == 'text')
                {
                    var input = $('<input class="form-control" type="text" placeholder="Search ' + $(column.header()).text() + '" value="' + column.search() + '" />')
                        .appendTo( $vatDatatableTHead.find('tr:eq(1) th:last') )
                        .on( 'keyup change clear', function () {
                            var term = this.value;

                            clearTimeout(tSearchTimout);
                            tSearchTimout = setTimeout(function() { column.search(term, false, false ).draw(); }, 500);
                        } );
                }
                else
                {
                    var select = $('<select class="form-control"><option value="">Any</option></select>')
                        .appendTo( $vatDatatableTHead.find('tr:eq(1) th:last') )
                        .on( 'change', function () {
                            var term = this.value;

                            column.search(term, false, false ).draw();
                        } );

                    switch(i)
                    {
                        case 1:
                            if(column.search() == 'none')
                            {
                                select.append('<option value="none" selected="selected">None</option>');
                            } 
                            else 
                            {
                                select.append('<option value="none">None</option>');
                            }

                            for(var i = 0; i < chapters.length; i++)
                            {    
                                if(column.search() == chapters[i].urlName)
                                {
                                    select.append('<option value="' + chapters[i].urlName + '" selected="selected">' + chapters[i].name + '</option>');
                                } 
                                else 
                                {
                                    select.append('<option value="' + chapters[i].urlName + '">' + chapters[i].name + '</option>');
                                }
                            }

                            break;  
                    }

                }
            }
        }
    });
}

$(function(){
    vatDatatable = $('#vat-datatable').DataTable({
        initComplete: function() {  setTimeout('rendervatFilters(true);', 50); },
        orderCellsTop: true,
        fixedHeader: true,
        bStateSave: true,
        fnStateSave: function (oSettings, oData) {
            localStorage.setItem('vat_DataTables', JSON.stringify(oData) );
        },
        fnStateLoad: function (oSettings) {
            return JSON.parse( localStorage.getItem('vat_DataTables') );
        },
        dom: '<"top"lip<"clear">>rt<"bottom"ip<"clear">>',
        processing: true,
        serverSide: true,
        language: {
            info: 'Showing language _START_ to _END_ of _TOTAL_ vat',
            paginate: {
                previous: '<i class="fal fa-chevron-left"></i>',
                next: '<i class="fal fa-chevron-right"></i>'
            },
            processing: '<img src="/images/loading.svg" class="dataTables_processing__loading" alt="Processing">'
        },
        lengthMenu: [ 20, 40, 50, 80, 100 ],
        order: [[ 1, "asc" ]],
        ajax: '/admin/ajax/get-vat.html',
        columnDefs: [
            {
                targets: 0,
                name: "id",
                orderable: true,
                searchable : false,
                data: null,
                render: function ( data, type, row, meta ) {
                    return (data.id);
                }
            },
            {
                targets: 1,
                name: "ex_vat",
                orderable: true,
                searchable : false,
                data: null,
                render: function ( data, type, row, meta ) {
                    return (data.exVat);
                }
            },
            {
                targets: 2,
                name: "inc_vat",
                orderable: true,
                searchable : false,
                data: null,
                render: function ( data, type, row, meta ) {
                    return (data.incVat);
                }
            },
            {
                targets: 3,
                name: "vat_rate",
                orderable: false,
                searchable : false,
                data: null,
                render: function ( data, type, row, meta ) {
                    return (data.vatRate)
                }
            }
        ]
    });
    
    $('vat-datatable').on('column-visibility.dt', function() { rendervatFilters(false); });


    var vatJSON = JSON.parse(localStorage.getItem('DataTables_vat-datatable_/vat/'));

    if(vatJSON != null)
    {
        vatJSON = vatJSON.columns;
        
        $('#vat-columns option').prop('selected', '');

        for(var i = 0; i < vatJSON.length; i++)
        {
            if(vatJSON[i].visible)
            {
                $('vat-columns option[value=\'' + vatDatatableColumnDefinitions[i].name + '\']').prop('selected', 'selected');
            }
        }

        $('vat-columns').multiselect('reload');
    }
});