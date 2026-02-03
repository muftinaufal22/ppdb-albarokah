<!-- jQuery HARUS PERTAMA - Dipindahkan ke atas -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS SETELAH jQuery -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- BEGIN: Vendor JS (vendors.min.js kemungkinan sudah include jQuery & Bootstrap, tapi kita override) -->
<script src="{{asset('Assets/Backend/vendors/js/vendors.min.js')}}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{asset('Assets/Backend/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('Assets/Backend/vendors/js/tables/datatable/datatables.bootstrap4.min.js')}}"></script>
<script src="{{asset('Assets/Backend/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('Assets/Backend/vendors/js/tables/datatable/responsive.bootstrap4.js')}}"></script>
<script src="{{asset('Assets/Backend/vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('Assets/Backend/vendors/js/tables/datatable/datatables.buttons.min.js')}}"></script>
<script src="{{asset('Assets/Backend/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('Assets/Backend/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('Assets/Backend/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('Assets/Backend/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('Assets/Backend/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('Assets/Backend/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{asset('Assets/Backend/js/core/app-menu.js')}}"></script>
<script src="{{asset('Assets/Backend/js/core/app.js')}}"></script>
<!-- END: Theme JS-->

<!-- Select2 CSS dipindahkan ke file style.blade.php -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- BEGIN: Page JS-->
<script src="{{asset('Assets/Backend/js/scripts/tables/table-datatables-advanced.js')}}"></script>
<script src="{{asset('Assets/Backend/js/scripts/forms/form-select2.js')}}"></script>
<script src="{{asset('Assets/Backend/js/scripts/forms/pickers/form-pickers.js')}}"></script>
<script src="{{asset('Assets/Backend/js/scripts/components/components-modals.js')}}"></script>
<!-- END: Page JS-->

<!-- Debug & Initialize Scripts -->
<script>
    $(document).ready(function() {
        // Debug: Cek apakah library dimuat dengan benar
        console.log('jQuery loaded:', typeof $ !== 'undefined');
        console.log('Bootstrap loaded:', typeof $.fn.dropdown !== 'undefined');
        
        // Force initialize Bootstrap dropdowns
        $('.dropdown-toggle').dropdown();
        
        // Initialize Feather Icons
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    });
</script>