/* [ ---- Gebo Admin Panel - datatables ---- ] */

	$(document).ready(function() {
		//* basic
		gebo_datatbles.dt_a();
		// horizontal scroll
		gebo_datatbles.dt_b();
		//* large table
		gebo_datatbles.dt_c();
		//* hideable columns
		gebo_datatbles.dt_d();
		//* server side proccessing with hiden row
		gebo_datatbles.dt_e();
		
		gebo_datatbles.tbl_usr();
	});
	
	//* calendar
	gebo_datatbles = {
		dt_a: function() {
			$('#dt_a').dataTable({
                "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sLengthMenu": "_MENU_ records per page"
                }
            });
		},
        dt_b: function() {
			$('#dt_b').dataTable({
				"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
                "sScrollX": "100%",
                "sScrollXInner": "110%",
                "sPaginationType": "bootstrap",
                "bScrollCollapse": true 
            });
		},
		dt_c: function() {
            var aaData = [];
            for ( var i=1, len=1000 ; i<=len ; i++ ) {
                aaData.push( [ i, i, i, i, i ] );
            }
            
            $('#dt_c').dataTable({
				"sDom": "<'row'<'span6'><'span6'f>r>t<'row'<'span6'i><'span6'>S>",
                "sScrollY": "200px",
                "aaData": aaData,
                "bDeferRender": true
			});
            
            $('#fill_table').click(function(){
                var aaData = [];
                for ( var i=1, len=50000; i <= len; i++){
                    aaData.push( [ i, i, i, i, i ] );
                }
               
                $('#dt_c').dataTable({
                    "sDom": "<'row'<'span6'><'span6'f>r>t<'row'<'span6'i><'span6'>S>",
                    "sScrollY": "200px",
                    "aaData": aaData,
                    "bDestroy": true,
                    "bDeferRender": true
                });
                $(this).remove();
                $('#entries').html('50 000');
                $('.dataTables_scrollHeadInner').css({'height':'34px','top':'0'});
            });
		},
		dt_d: function() {
			function fnShowHide( iCol ) {
				/* Get the DataTables object again - this is not a recreation, just a get of the object */
				var oTable = $('#dt_d').dataTable();
				 
				var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
				oTable.fnSetColumnVis( iCol, bVis ? false : true );
			}
			
			var oTable = $('#dt_d').dataTable({
				"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
				"sPaginationType": "bootstrap"
			});
			
			$('#dt_d_nav').on('click','li input',function(){
				fnShowHide( $(this).val() );
			});
		},
		dt_e: function(){
			if($('#dt_e').length) {
				
				var oTable;
 
				/* Formating function for row details */
				/*function fnFormatDetails ( nTr )
				{
					var aData = oTable.fnGetData( nTr );
					var sOut = '<table cellpadding="5" cellspacing="0" border="0" class="table table-bordered" >';
					sOut += '<tr><td>Rendering engine:</td><td>'+aData[2]+' '+aData[5]+'</td></tr>';
					sOut += '<tr><td>Link to source:</td><td>Could provide a link here</td></tr>';
					sOut += '<tr><td>Extra info:</td><td>And any further details here (images etc)</td></tr>';
					sOut += '</table>';
					 
					return sOut;
				}
*/				
				oTable = $('#dt_e').dataTable( {
					"bProcessing": true,
					"bServerSide": true,
                    "sPaginationType": "bootstrap",
                    "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": "lib/datatables/server_side.php",
					
					 "aoColumnDefs": [
                        { "bSearchable": false, "bVisible": false, "aTargets": [ 7 ] },
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 8 ] },
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 9 ] }
                    ],
					"aaSorting": [[1, 'asc']],
					"oLanguage": {
      "sInfoFiltered": ""
    }
				} );
				
				
				
                 
				//$('#dt_e').on('click','tbody td img', function () {
//					var nTr = $(this).parents('tr')[0];
//					if ( oTable.fnIsOpen(nTr) )
//					{
//						/* This row is already open - close it */
//						this.src = "img/details_open.png";
//						oTable.fnClose( nTr );
//					}
//					else
//					{
//						/* Open this row */
//						this.src = "img/details_close.png";
//						oTable.fnOpen( nTr, fnFormatDetails(nTr), 'details' );
//					}
//				} );
				
				 
			 

				
				

			}
		},
		
		
		tbl_usr: function(){
			
				
				var oTable;
 
							
				oTable = $('#tbl_usr').dataTable( {
					"bProcessing": true,
					"bServerSide": true,
                    "sPaginationType": "bootstrap",
                    "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": "lib/datatables/user_side.php",
					
					 "aoColumnDefs": [
                        { "bSearchable": false, "bVisible": false, "aTargets": [ 4 ] },
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 5 ] },
						{ "bSearchable": false, "bSortable":false, "sClass": "center", "aTargets": [ 6 ] }
                    ],
					"aaSorting": [[1, 'asc']],
					"oLanguage": {
      "sInfoFiltered": ""
    }
				} );
				
				
				
				
				
				
				
				
					
				



				
				
				
      			
		}
		
		
	};

//* http://datatables.net/blog/Twitter_Bootstrap_2

    /* Default class modification */
    $.extend( $.fn.dataTableExt.oStdClasses, {
        "sWrapper": "dataTables_wrapper form-inline"
    } );
    
    /* API method to get paging information */
    $.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
    {
        return {
            "iStart":         oSettings._iDisplayStart,
            "iEnd":           oSettings.fnDisplayEnd(),
            "iLength":        oSettings._iDisplayLength,
            "iTotal":         oSettings.fnRecordsTotal(),
            "iFilteredTotal": oSettings.fnRecordsDisplay(),
            "iPage":          Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
            "iTotalPages":    Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
        };
    }
    
    /* Bootstrap style pagination control */
    $.extend( $.fn.dataTableExt.oPagination, {
        "bootstrap": {
            "fnInit": function( oSettings, nPaging, fnDraw ) {
                var oLang = oSettings.oLanguage.oPaginate;
                var fnClickHandler = function ( e ) {
                    e.preventDefault();
                    if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
                        fnDraw( oSettings );
                    }
                };
    
                $(nPaging).addClass('pagination').append(
                    '<ul>'+
                        '<li class="prev disabled"><a href="#">&lt; '+oLang.sPrevious+'</a></li>'+
                        '<li class="next disabled"><a href="#">'+oLang.sNext+' &gt; </a></li>'+
                    '</ul>'
                );
                var els = $('a', nPaging);
                $(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
                $(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
            },
    
            "fnUpdate": function ( oSettings, fnDraw ) {
                var iListLength = 5;
                var oPaging = oSettings.oInstance.fnPagingInfo();
                var an = oSettings.aanFeatures.p;
                var i, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);
    
                if ( oPaging.iTotalPages < iListLength) {
                    iStart = 1;
                    iEnd = oPaging.iTotalPages;
                }
                else if ( oPaging.iPage <= iHalf ) {
                    iStart = 1;
                    iEnd = iListLength;
                } else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
                    iStart = oPaging.iTotalPages - iListLength + 1;
                    iEnd = oPaging.iTotalPages;
                } else {
                    iStart = oPaging.iPage - iHalf + 1;
                    iEnd = iStart + iListLength - 1;
                }
    
                for ( i=0, iLen=an.length ; i<iLen ; i++ ) {
                    // Remove the middle elements
                    $('li:gt(0)', an[i]).filter(':not(:last)').remove();
    
                    // Add the new list items and their event handlers
                    for ( j=iStart ; j<=iEnd ; j++ ) {
                        sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
                        $('<li '+sClass+'><a href="#">'+j+'</a></li>')
                            .insertBefore( $('li:last', an[i])[0] )
                            .bind('click', function (e) {
                                e.preventDefault();
                                oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
                                fnDraw( oSettings );
                            } );
                    }
    
                    // Add / remove disabled classes from the static elements
                    if ( oPaging.iPage === 0 ) {
                        $('li:first', an[i]).addClass('disabled');
                    } else {
                        $('li:first', an[i]).removeClass('disabled');
                    }
    
                    if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
                        $('li:last', an[i]).addClass('disabled');
                    } else {
                        $('li:last', an[i]).removeClass('disabled');
                    }
                }
            }
        }
    } );
