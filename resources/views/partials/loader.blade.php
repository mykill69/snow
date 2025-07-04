<div id="page-loader"
     class="position-fixed top-0 start-0 w-100 h-100 flex-column justify-content-center align-items-center"
     style="z-index:1055;display:none;background:linear-gradient(135deg,#f8f9fa,#e9ecef);font-family:'Segoe UI',Tahoma,sans-serif">

    {{-- static logo --}}
    <img src="{{ asset('template/img/mis_logo2.png') }}" alt="MIS logo"
         style="width:110px;height:auto;margin-bottom:28px">

    {{-- progress bar --}}
    <div class="progress-loader" style="width:220px;height:12px;background:#dee2e6;border-radius:6px;overflow:hidden">
        <div id="progress-bar" style="width:0;height:100%;background:#0d6efd;transition:width .4s ease"></div>
    </div>

    <p style="margin-top:1.3rem;font-size:1.15rem;font-weight:500;color:#343a40">
        Submitting your request… please wait
    </p>
</div>
