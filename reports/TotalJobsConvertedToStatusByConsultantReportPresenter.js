
function TotalJobsConvertedToStatusByConsultantReportPresenter(){
    
    
    function init(){

    }

	var publicInt={

         showReport:function(reportId,reportYear) {

               var api_url="reports_api.php";
               var post_data = {
                'reportId': reportId,
                'reportYear': reportYear
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
                            { "data":"status","title" : "Status", "orderable": true, "className": 'all subheader' },
                            { "data":"Jan","title" : "Jan", "orderable": true, "className": 'all' },
                            { "data":"Feb","title" : "Feb", "orderable": true, "className": 'all' },
                            { "data":"Mar","title" : "Mar", "orderable": true, "className": 'all' },
                            { "data":"Apr","title" : "Apr", "orderable": true, "className": 'all' },
                            { "data":"May","title" : "May", "orderable": true, "className": 'all' },
                            { "data":"Jun","title" : "Jun", "orderable": true, "className": 'all' },
                            { "data":"Jul","title" : "Jul", "orderable": true, "className": 'all' },
                            { "data":"Aug","title" : "Aug", "orderable": true, "className": 'all' },
                            { "data":"Sep","title" : "Sep", "orderable": true, "className": 'all' },
                            { "data":"Oct","title" : "Oct", "orderable": true, "className": 'all' },
                            { "data":"Nov","title" : "Nov", "orderable": true, "className": 'all' },
                            { "data":"Dec","title" : "Dec", "orderable": true, "className": 'all' },
                         ],
                         "columnDefs": [
                            { "visible": false, "targets": 0 }
                        ],
                        responsive: true,
                        dom: 'Bfrtip',
                        buttons: [
                           {
                              extend: 'print',
                              title: "Total Job Converted To Status - Monthly By Consultant"
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
                                    '<tr class="group"><td colspan="13">'+group+'</td></tr>'
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
