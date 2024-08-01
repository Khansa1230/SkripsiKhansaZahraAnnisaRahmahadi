
<script src="{{ asset('template/assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('template/assets/js/app.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
     $(document).ready(function() {
        $('.sidebar-hide').on('click', function(e) {
            e.preventDefault();
            $('#sidebar').toggleClass('hidden');
        });

        $('.burger-btn').on('click', function(e) {
            e.preventDefault();
            $('#sidebar').toggleClass('hidden');
        });
     })
</script>
<!-- Other scripts -->
<script src="{{ asset('template/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('template/assets/js/pages/dashboard.js') }}"></script>
