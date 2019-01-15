<script>
    var tag = '<?=$tag;?>';
    function productPDFDownload(i) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            async: false,
            data: {id:i,cate:tag},
            url: '/admin/product_pdf/download',
            dataType: "json",
            cache: false,
            success: function(json){

            },
            error: function(err){

            }
        });
    }
</script>