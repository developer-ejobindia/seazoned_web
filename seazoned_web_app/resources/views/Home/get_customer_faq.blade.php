<!-- Bootstrap -->
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900"
      rel="stylesheet">

<link rel="stylesheet" type="text/css" href="{{ asset("default/plugins/bootstrap-4.0.0/css/bootstrap.css") }}" rel="stylesheet">
<link href="{{ asset("default/plugins/fontawesome-free-5.0.0/web-fonts-with-css/css/fontawesome-all.css") }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset("default/css/style.css") }}">
<link rel="stylesheet" type="text/css" href="{{ asset("default/css/responsive.css") }}">
<link rel="stylesheet" type="text/css" href="{{ asset("default/plugins/custom-timepicker/css/custom_timepicker.css") }}">
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/default/plugins/sweetalert2-7.16.0/dist/sweetalert2.css">
<link rel="stylesheet" type="text/css" href="{{ asset("default/plugins/custom-scrollbar/jquery.mCustomScrollbar.css") }}">
<!--<link rel="stylesheet" type="text/css" href="{{ asset("default/css/jquery-ui.css") }}">-->
<!--<script type="text/javascript" src="{{ asset("default/js/jquery-ui.js") }}"></script>-->
<script type="text/javascript" src="{{ asset("default/js/jquery-3.2.1.js") }}"></script>
<script type="text/javascript" src="{{ asset("default/plugins/bootstrap-4.0.0/js/bootstrap.min.js") }}"></script>
<script type="text/javascript" src="{{ url('/') }}/default/plugins/sweetalert2-7.16.0/dist/sweetalert2.min.js"></script>
<script type="text/javascript" src="{{ asset("default/plugins/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js") }}"></script>
<script type="text/javascript" src="{{ url('/') }}/default/js/custom.js"></script>
<script type="text/javascript" src="{{ url('/') }}/default/plugins/custom-timepicker/js/custom_timepicker.js"></script>
<script src="{{ asset("default/js/less.min.js") }}"></script>

<section class="faq-banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">                       
                <h2 class="m-0 sm-bold">FAQ</h2>
                <p class="m-t-10">Frequently Asked Questions</p>                           
            </div>
        </div>
        <div class="row">

        </div>
    </div>
</section>
<section class=" main-content faq-content p-y-50">
    <div class="container">

        <div class="row">    
            <div class="col-md-12">
                @forelse($faq_content as $content)
                <div class="question-box">
                    <h4 class="question m-0">
                        <i class="fas fa-angle-down float-right"></i>
                        <i class="fab fa-quora"></i><span>{{$content->questions}}</span> </h4>
                    <div class="answer answer-open">
                        <p>{!! ($content->answers) !!}</p> 
                    </div>
                </div>
                <hr class="m-0">  
                @empty                
                <div class="alert alert-danger">No Data Found</div>             
                @endforelse
            </div>
        </div>

    </div>
</section>



