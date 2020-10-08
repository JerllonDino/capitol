<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
     <link rel="icon" type="image/png" href="{{asset('asset/images/benguet_capitol.png')}}" />
    <title>{{ $base['site_title'] }} | {{ $base['page_title'] }}</title>

    <!-- Bootstrap CSS -->
    {{ Html::style('/bootstrap-3.4.0/css/bootstrap.min.css') }}
    {{ Html::style('/bootstrap-3.4.0/css/bootstrap-theme.min.css') }}

    <!-- jQuery UI CSS -->
    {{ Html::style('/jquery-ui-1.12.1/jquery-ui.min.css') }}
    {{ Html::style('/jquery-ui-1.12.1/jquery-ui.structure.min.css') }}
    {{ Html::style('/jquery-ui-1.12.1/jquery-ui.theme.min.css') }}

    <!-- Featherlight CSS -->
    {{ Html::style('/featherlight-1.5.0/featherlight.min.css') }}
    {{ Html::style('/featherlight-1.5.0/featherlight.gallery.min.css') }}

    <!-- Theme CSS -->
    {{ Html::style('/sb-admin-1.0.4/css/sb-admin.css') }}
    {{ Html::style('/sb-admin-1.0.4/font-awesome/css/font-awesome.min.css') }}
    {{ Html::style('/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css') }}
    {{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}

    <!-- Custom CSS -->
    {{ Html::style('/base/css/style.css') }}

    <!-- Other CSS -->
    @yield('css')
</head>
<body>
    @yield('page')

    <!-- jQuery -->
    {{ Html::script('/jquery-2.2.4/jquery-2.2.4.min.js') }}

	<!-- Bootstrap JS -->
    {{ Html::script('/bootstrap-3.4.0/js/bootstrap.min.js') }}

    <!-- jQuery UI -->
    {{ Html::script('/jquery-ui-1.12.1/jquery-ui.min.js') }}

    <!-- Featherlight JS -->
    {{ Html::script('/featherlight-1.5.0/featherlight.min.js') }}
    {{ Html::script('/featherlight-1.5.0/featherlight.gallery.min.js') }}
    {{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
    {{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
    {{ Html::script('/base/sweetalert/sweetalert2.min.js') }}

    {{ Html::script('/vendor/moment.min.js') }}
    {{ Html::script('/vendor/collapse.js') }}
    {{ Html::script('/vendor/transition.js') }}
    {{ Html::script('vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
    {{ Html::script('/vendor/autocomplete/jquery.autocomplete.js') }}
    {{ Html::script('/tinymce-4.5.6/tinymce.min.js') }}
    <!-- Custom JS -->
    {{ Html::script('/base/js/script.js') }}

    <!-- Other JS -->
    @yield('js')
</body>
</html>