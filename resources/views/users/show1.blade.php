<?php
use phpGrid\C_DataGrid;

require_once(public_path() ."/phpGrid_Enterprise_v7.2.7/conf.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>A iFrame to Load a custom edit/add form</title>
</head>
<body>

<?php
$dg = new C_DataGrid("SELECT id,name,email,password,role_id,created_at,updated_at FROM users");

// change column titles
$dg->set_col_title("id", "ID");
$dg->set_col_title("name", "User Name");
$dg->set_col_title("email", "EMail");
$dg->set_col_title("role_id", "Role");
$dg->set_col_title("created_at", "Created At");
$dg->set_col_title("updated_at", "Updated At");

// change date format of a column
//$dg -> set_col_date("orderDate", "Y-m-d", "n/j/Y", "yy-mm-dd");

// change a date field to regular text field (no datepicker)
// $dg->set_col_property('orderDate', array('editoptions'=>array('dataInit'=>'')));

$dg -> enable_edit("FORM");
$dg -> display();
?>

<div>
Use jQuery <a href="http://www.ericmmartin.com/projects/simplemodal/">SimpleModal</a> to load iframe inside the modal. Notice how we unbind "dblclick" event handler first in the Javascript.
</div>

<div>
Be sure to replace "phpGrid_users" with your own "phpGrid_TABLENAME" in our custom javascript
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/simplemodal/1.4.4/jquery.simplemodal.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
    phpGrid_users.unbind('dblclick');
    phpGrid_users.bind("dblclick", function(row_id, users) {

        var src = 'http://phpgrid.com';

        jQuery.modal('<iframe src="' + src + '" height="600px" width="1000px"  frameBorder="0" >', {

            minHeight: '620px',
            minWidth: '1050px',
            containerCss: { 'background-color': '#a1dbfc' },
            onClose: function(dialog) {

                dialog.data.fadeOut('fast', function() {
                    dialog.container.slideUp('slow', function() {
                        dialog.overlay.fadeOut('fast', function() {
                            jQuery.modal.close();
                            //  alert("im done");
                            phpGrid_users.trigger("reloadGrid");
                        });
                    });
                });

            }
        });

    });

    jQuery("#add_users").off("click");
    jQuery("#add_users").on("click", function() {
        //alert( "Redirected insert function")
        //
        var src = 'http://phpgrid.com';

        //alert( "Ny varelinje " + src);
        jQuery.modal('<iframe src="' + src + '" height="600px" width="1000px"  frameBorder="0" >', {

            minHeight: '620px',
            minWidth: '1050px',
            containerCss: { 'background-color': '#a1dbfc' },
            onClose: function(dialog) {

                dialog.data.fadeOut('fast', function() {
                    dialog.container.slideUp('slow', function() {
                        dialog.overlay.fadeOut('fast', function() {
                            jQuery.modal.close();
                            phpGrid_users.trigger("reloadGrid");
                        });
                    });
                });

            }
        });

    });


});
</script>


<style>
/*
 * SimpleModal Basic Modal Dialog
 * http://simplemodal.com
 *
 * Copyright (c) 2013 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 */

#basic-modal-content {display:none;}

/* Overlay */
#simplemodal-overlay
{
    background-color:#000;
}

/* Container */
#simplemodal-container
{
    /*
    height:360px;
    width:600px;
    */
    /*color:#bbb;*/
    background-color:#e6e6ed;
    border:4px solid #444;
    padding:5px;
}
#simplemodal-container .simplemodal-data
{
    padding:8px;
}
#simplemodal-container code
{
    background:#141414;
    border-left:3px solid #65B43D;
    color:#bbb;
    display:block;
    font-size:12px;
    margin-bottom:12px;
    padding:4px 6px 6px;
}
#simplemodal-container a
{
    color:#ddd;
}
#simplemodal-container a.modalCloseImg:after{
    content: 'CLOSE'; /* UTF-8 symbol */
}
#simplemodal-container a.modalCloseImg
{
    width:25px;
    height:29px;
    display:inline;
    z-index:3200;
    position:absolute;
    top:-15px;
    right:-16px;
    cursor:pointer;
}
#simplemodal-container h3
{
    color:#84b8d9;
}
</style>

</body>
</html>
