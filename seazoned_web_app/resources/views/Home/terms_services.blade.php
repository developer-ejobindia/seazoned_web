@extends("layouts.dashboardlayout")
@section('content')
<section class="main-content user-profile p-y-30">
    <div class="container">
        <div class="why-work-with-us">
            <div class="row"> 
                <div class="col-md-12 terms-and-services">
                    <h2 class="heading m-0 regular m-b-25">Terms and Services</h2>
                    <hr class="m-b-25">
                    <div class="row">
                        @forelse($terms_content as $content)
                        <div class="col-md-12">
                         <p class="">{{strip_tags($content->content) }}</p>
                        </div>
                        @empty
                        <div class="col-md-12">
                            <div class="alert alert-danger">No Data Found</div>                                    
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection