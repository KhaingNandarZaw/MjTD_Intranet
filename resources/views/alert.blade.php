@push('scripts')
<script src="{{asset('/js/bootstrap-notify.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap-notify.css')}}">

<script>
$(function () {
  @if(Session::has('success'))
    $('.notifications').notify({
        message: { text: "{{ Session::get('success') }}" },
        type:'success'
      }).show();
     @php
       Session::forget('success');
     @endphp
  @endif

  @if(Session::has('info'))
      $('.notifications').notify({
        message: { text: "{{ Session::get('info') }}" },
        type:'info'
      }).show();
      @php
        Session::forget('info');
      @endphp
  @endif

  @if(Session::has('warning'))
  		$('.notifications').notify({
        message: { text: "{{ Session::get('warning') }}" },
        type:'warning'
      }).show();
      @php
        Session::forget('warning');
      @endphp
  @endif

  @if(Session::has('error'))
  		$('.notifications').notify({
        message: { text: "{{ Session::get('error') }}" },
        type:'danger'
      }).show();
      @php
        Session::forget('error');
      @endphp
  @endif
});
</script>
@endpush