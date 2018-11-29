<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="gridMailBox" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Mail to...</h4>
            </div>
            <div class="modal-body">
                <label for="emailBox">Email Address</label>
                <input type="email" class="form-control" id="emailBox" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="confirmSendMail" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div id="msg" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Notice</h4>
            </div>
            <div class="modal-body">
                <p id="msgTxt" style="text-align: center;"></p>
            </div>
        </div>
    </div>
</div>

<script>
    var id = 0;
    var tag = '<?=$tag;?>';
    function productGridMailBox(i) {
        id = i;
        $('#gridMailBox').modal('show');
    }

    $('#confirmSendMail').click(function () {
        $('#gridMailBox').modal('hide');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            async: false,
            data: {id:id,cate:tag,address:$('#emailBox').val()},
            url: '/admin/send_product_mail/product_grid',
            dataType: "json",
            cache: false,
            success: function(json){
                showMsg(json.msg);
            },
            error: function(err){
                showMsg('fail');
            }
        });
    });

    function showMsg(msg)
    {
        $('#msgTxt').html(msg);
        $('#msg').modal('show');
    }
</script>