
function JobsConvertedToStatusDetailReportPresenter(){
    
    
    function init(){

    }

	var publicInt={

         showReport:function(reportId,reportYear,reportMonth) {

               var api_url="reports_api.php";
               var post_data = {
                'reportId': reportId,
                'reportYear': reportYear,
                'reportMonth': reportMonth
               };
           

               $.ajax({
                    type: 'POST',
                    url: api_url,
                    data: post_data,
                    dataType: 'json'
                })
                .done(function (data) {

                    publicInt.buildReportBody(data);

                });

	    },
      buildReportHead: function(data){
             
         },
	    buildReportBody: function(data){
            
            if($.fn.DataTable.isDataTable( '#report-table' ) ) {
                $("#report-table").dataTable().fnDestroy();
             }
            
            $('#report-table').DataTable( {
                        "data": data,
                        "searching":   false,
                        "info":     false,
                        "pageLength": 50,
                        "bLengthChange": false,
                        columns: [
                            { "data":"consultantname","title" : "Consultant", "orderable": true, "className": 'desktop' },
                            { "data":"statuschangedate","title" : "Date Status Changed", "orderable": true, "className": 'all' },
                            { "data":"title","title" : "Title", "orderable": true, "className": 'all' },
                            { "data":"status","title" : "Current Status", "orderable": true, "className": 'all' },
                            { "data":"client","title" : "Client", "orderable": true, "className": 'all' }
                         ],
                         "columnDefs": [
                            { "visible": false, "targets": 0 }
                        ],
                        responsive: true,
                        dom: 'Bfrtip',
                        buttons: [
                           {
                              extend: 'print',
                              title: "Jobs Converted To Status Details - "+ $("#report-month option:selected").text() + " " + $("#report-year").val()
                            },'excel', 'pdf'
                        ],
                        "order": [[0, 'asc' ]],
                         initComplete: function(settings, json) {
                          $('#loadingSpinner').hide();
                        },
                        "drawCallback": function ( settings ) {
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
             
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if ( last !== group ) {
                                $(rows).eq( i ).before(
                                    '<tr class="group"><td colspan="4">'+group+'</td></tr>'
                                );
             
                                last = group;
                            }
                        } );
                    }

            });

      }

   };

   init();
   return publicInt;

}
