@extends("layouts.dashboardlayout")
@section('content')

<section class="main-content register-member-form">
<form class="m-t-30" action="{{ url("/user/edit-user-rating") }}" method="post" enctype="multipart/form-data">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card custom-card m-b-50">
                            <div class="card-header landscapers-review-heading text-center p-t-35 p-b-25">
                                <h4 class="m-0 light m-b-10">How was your service with</h4>
                                <h5  class="m-0 regular">Jhon Harison ?</h5>
                            </div>
                            <div class="card-block p-50 text-center">
                                <div class="form-group landscapers-rating text-center m-b-40">
                                    <label for="rating" class="sm-bold m-b-20">Your Rating</label>
                                    <input id="input-21c" name="rating" value="0" type="number" class="rating" min=0 max=5 step=1 data-size="sm" data-stars="5">
                                </div>
                                <div class="form-group">
                                    <label for="review" class="sm-bold m-b-20">Write a Review</label>
                                    <textarea class="form-control" rows="5" name="review" id="review"></textarea>
                                </div>
                                <input type="hidden" name="landscaper_id" id="landscaper_id" value="{{ $landscaper_id }}">
                                <input type="hidden" name="order_no" id="order_no" value="{{ $order_no }}">
                                <button type="submit" class="btn btn-success noradius p-x-50">Submit</button>
                            </div>
                        </div>
                    </div>          
                </div>
            </div>
</form>
        </section>

@endsection