@extends("layouts.dashboardlayout")
@section('content')
<script type="text/javascript">
    function submit_form()
    {
        document.getElementById('profile_form').submit();
    }
</script>
<section class="faq-banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">                       
                <h2 class="m-0 sm-bold">FAQ</h2>
                <p class="m-t-10">Frequently Asked Questions</p>                           
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 text-center  m-auto">   
                {!!Form::open(array('routes' => 'home_faq', 'method' => 'post','id'=>'profile_form'))!!}
                <select class="form-control" name="profile_id" class="" onchange="submit_form();">
                        <option value="2" selected>Customer</option>
                        <option value="3" <?php echo(isset($profile_id) && $profile_id==3)?'selected':''; ?>>Provider</option>
                    </select>                    
                {!!Form::close()!!} 
            </div>
        </div>
    </div>
</section>
<section class=" main-content faq-content p-y-50">
    <div class="container">
        
        <div class="row">    
            <div class="col-md-12">
                @forelse($faq_content as $content)
                <div class="question-box">
                    <h6 class="question m-0">
                    <i class="fas fa-angle-down float-right"></i>
                    <span>{{$content->questions}}</span> </h6>
                    <div class="answer answer-open">
                        <p>{!! $content->answers !!}</p> 
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

@endsection