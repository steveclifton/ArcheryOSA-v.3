<script src="{{URL::asset('/serviceworker.js')}}"></script>
<script src="{{URL::asset('/js/popper.min.js')}}"></script>
<script src="{{URL::asset('/js/bootstrap.min.js')}}"></script>
<script src="{{URL::asset('/js/jquery.slimscroll.js')}}"></script>
<script src="{{URL::asset('/js/jquery.scrollTo.min.js')}}"></script>
<script src="{{URL::asset('/plugins/moment/moment.js')}}"></script>
<script src="{{URL::asset('/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{URL::asset('/pages/jquery.form-pickers.init.js')}}"></script>
<script src="{{URL::asset('/plugins/jstree/jstree.min.js')}}"></script>
<script src="{{URL::asset('/js/jquery.core.js')}}"></script>
<script src="{{URL::asset('/js/jquery.app.js')}}"></script>
<script src="{{URL::asset('/js/archeryosa.js')}}"></script>
<script>
    window.addEventListener('load', async e => {
        if ('serviceWorker' in navigator) {
            try {
                navigator.serviceWorker.register('serviceWorker.js');
            }
            catch (error) {
                console.log('SW failed');
            }
        }
    });
</script>
