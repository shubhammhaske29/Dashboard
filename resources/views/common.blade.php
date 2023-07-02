<script type="text/javascript">
function pad(n) 
{
return n<10 ? '0'+n : n
}

function showWard() {
    debugger
    let zone = $('#zone option:selected').val()
    let zones = '<?php echo json_encode(config('common.zones'));?>';
    zones = JSON.parse(zones);
    let wards = zones[zone];
    let content = '';
    let index = 0;

    $.each(wards, function (k,ward) {
        if (index === 0) {
            content += '<option value="' + ward + '" selected>' + ward + '</option>';
        } else {
            content += '<option value="' + ward + '">' + ward + '</option>';
        }
        index++;
    });
    $('#ward').html(content);
}

</script>
