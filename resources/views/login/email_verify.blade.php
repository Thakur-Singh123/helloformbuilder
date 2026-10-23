 @extends('layouts.header')
 
 @section('seo')	 
<title> Account Verification - {{$settings->seo_title}}</title> 
<meta data-rh="true" name="title"                content="Account Verification -{{$settings->seo_title}}">
<meta data-rh="true" name="keywords"             content="Account Verification -{{$settings->seo_tag}}">
<meta data-rh="true" name="description"          content="Account Verification -{{$settings->seo_description}}"> 

<meta data-rh="true" property="og:url"           content="{{ url('/')}}" />
<meta data-rh="true" property="og:site_name"     content="{{$settings->site_name}}" /> 
<meta data-rh="true" property="og:type"          content="website" />
<meta data-rh="true" property="og:title"         content="Account Verification -{{$settings->seo_title}}" />
<meta data-rh="true" property="og:description"   content="Account Verification -{{$settings->seo_description}}" />
<meta data-rh="true" property="og:image"         content="{{url('public/')}}/{{$settings->logo}}" />

<meta data-rh="true" name="twitter:title"        content="Account Verification -{{$settings->seo_title}}" /> 
<meta data-rh="true" name="twitter:site"         content="{{$settings->site_name}}" /> 
<meta data-rh="true" name="twitter:description"  content="Account Verification -{{$settings->seo_description}}" />
<meta data-rh="true" name="twitter:creator"      content="Account Verification -{{$settings->seo_title}}" />
<meta data-rh="true" name="twitter:card"         content="summary_large_image" />
<meta data-rh="true" name="twitter:image:src"    content="{{url('public/')}}/{{$settings->logo}}" />

 @stop	
 

 @section('css') 
      <link href="{{url('public/assets/web/css/single-product.css')}}" rel="stylesheet">
 @stop	

@section('content')


<style>
	.form-control {
	    height: 45px !important;
	} 
</style>

<div class="container py-2">
    <div class="row">
        <div class="col-md-12 pr-0">
            <div class="card border-radius-0 bg-color-light border-0 box-shadow-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col-md-12 y-3"> 
                                   <div class="row">
										<div class="col-md-3"> </div>
                                            <div class="col-md-6"> 
                                                <div class="py-3"> 
                                                    <div class="row"> 
                                                            <div class="form-group  text-center col-md-12"> 
                                                                 <h1 class="font-weight-bold mb-2 mt-1">Account Verification</h1> 
                                                                      <hr> 

                                                                    <img class="error__content--img mb-50"  style="height: 150px !important;" src="{{@$image}}" alt="{{@$title}}">
                                                                    <h2>{{@$title}}</h2>
                                                                    <b>{{@$message}}</b>
                                                                        </br> </br>
                                                                    <a class="btn btn-success"  style="color:white !important;" href="{{url('/login')}}">Login Now</a>
                                                            </div> 
                                                    </div> 
                                                </div>
                                            </div> 
                                        </div>
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop


@section('footer')
  

@stop
