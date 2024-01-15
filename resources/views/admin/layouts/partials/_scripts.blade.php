<!-- JAVASCRIPT -->
<script src="{{ asset('assets/admin/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/feather-icons/feather.min.js') }}"></script>
<!-- pace js -->
<script src="{{ asset('assets/admin/libs/pace-js/pace.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/notiflix-aio-2.7.0.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>


<script>
    function displayMessage(message, type = null) {
        if (type === "error") {
            Notiflix.Notify.Failure(message);
        } else if (type === "success") {
            Notiflix.Notify.Success(message);
        } else {
            Notiflix.Notify.info(message);
        }
    }
</script>

@if (Session::has('success'))
    <script>
        Notiflix.Notify.Success("{{ Session::get('success') }}");
    </script>
@endif

@if (Session::has('error'))
    <script>
        Notiflix.Notify.Failure("{{ Session::get('error') }}");
    </script>
@endif
