 <!-- / Layout wrapper -->

 <!-- Helpers -->
 <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

 <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
 <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
 <script src="{{ asset('assets/js/config.js') }}"></script>

 <!-- Core JS -->
 <!-- build:js assets/vendor/js/core.js -->
 <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
 <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
 <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
 <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

 <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
 <!-- endbuild -->

 <!-- Vendors JS -->
 {{-- <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script> --}}

 <!-- Main JS -->
 <script src="{{ asset('assets/js/main.js') }}"></script>

 <!-- Page JS -->
 <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
 <script src="{{ asset('assets/js/pages-account-settings-account.js') }}"></script>

 <!-- Place this tag in your head or just before your close body tag. -->
 <script async defer src="{{ asset('assets/js/buttons.js') }}"></script>

 {{-- sweetaler2 --}}

 <script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>

 {{-- blocl ui --}}
 <script src="{{ asset('assets/js/jquery.blockUI.min.js') }}"></script>

 <script>
     function changePassword() {
         $('#changePassword').modal('show');
     }

     function savePassword() {

         $.ajax({
             url: "{{ route('profile.change.password') }}",
             type: "post",
             data: {
                 '_token': '{{ csrf_token() }}',
                 'password': $('#password').val()
             },
             success: function(response) {
                 $('#changePassword').modal('hide');
                 $('#password').val('')
                 swal({
                     title: 'Password Updated successfully',
                     icon: 'success',
                 });
             },
         });

     }
 </script>
