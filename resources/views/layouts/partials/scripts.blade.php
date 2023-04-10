<!-- REQUIRED JS SCRIPTS -->

<!-- JQuery and bootstrap are required by Laravel 5.3 in resources/assets/js/bootstrap.js-->
<!-- Laravel App -->

<script src="{{ url (mix('/js/app.js')) }}" type="text/javascript"></script>
<script src="{{ asset ('/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset ('/js/bootstrap-multiselect.min.js') }}" type="text/javascript"></script>
<link href="{{ asset('/css/bootstrap-datepicker.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('/css/bootstrap-multiselect.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('/css/home.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('/css/fixed-table.css') }}" rel="stylesheet" type="text/css"/>
<link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/js/bootstrap.min.js"></script>

<script>
$( document ).ready(function() {

    //$('[data-toggle="push-menu"]').pushMenu('toggle');

    $('a.sidebar-toggle').on('click', function() {

        var body = $('body');
        var state = 'sidebar-collapse';
        var mini_state = 'sidebar-open';


        if (!body.hasClass(state))
        {
            body.addClass(state);
        }
        else
        {
            body.removeClass(state)
        }

        if (!body.hasClass(mini_state))
        {
            body.addClass(mini_state);
        }
        else
        {
            body.removeClass(mini_state)
        }
    });

    //Date picker
    $('#datepicker').datepicker({
    "startDate": "2018-04-01",
    "endDate": "{{ date("Y-m-d",strtotime("-1 days")) }}",
    "format":"yyyy-mm-dd",
    "maxDate": new Date(),
    "autoclose": true
    });
});


/*$('.sidebar-toggle').on('click',function() {
    alert('hello');
});*/

//sidebar-collapse

</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->
