@yield('css')
<style>
    input[readonly] {
       background: #eaecf4 !important;
    }

    textarea:disabled {
      background: #eaecf4 !important;

    }



    input:disabled {
      background: #eaecf4 !important;

    }

    textarea:disabled {
      background: #eaecf4 !important;

    }


/* Fullscreen Button
------------------------------*/

.full{
    min-width: 100% !important;
}
#fullscreen-button {
  position: absolute;
  top:  15px;
  right:  15px;
  border:  0;
  width:  40px;
  height:  40px;
  border-radius: 50%;
  box-sizing: border-box;
  font-size: 18px;
}

#compress-button {
  position: absolute;
  top:  15px;
  right:  15px;
  border:  0;
  width:  40px;
  height:  40px;
  border-radius: 50%;
  box-sizing: border-box;
  font-size: 18px;
}



</style>

    <!-- Bootstrap Css -->
    <link href="{{asset('assets/backend/css/bootstrap.min.css')}}" id="bootstrap-style"  rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('assets/backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('assets/backend/css/app.min.css')}}"  id="app-style" rel="stylesheet" type="text/css" />

    {{-- add --}}
    {{-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&amp;family=Ubuntu:wght@400;500;700&amp;display=swap" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"  />


    <link href="{{asset('assets/backend/libs/select2/select2.min.css')}}"  id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/style.css" rel="stylesheet" />


