@extends('layouts.appartigo')

@section('template_title')
  Listen
@endsection

@section('content')
<style>
@media (min-width: 768px) {

}
@import 'https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300';
.edit { overflow-y: hidden; margin-bottom: 10px; }
body{
  
  font-family: 'Open Sans Condensed', sans-serif;
}

.edit>div { height: 180px }
.href{
  color: white;
}
.btn {
	line-height: 50px;
	text-align: center;
	width: 250px;
	cursor: pointer;
}

/* 
========================
      BUTTON ONE
========================
*/
.btn-one {
	color: #FFF;
	transition: all 0.3s;
	position: relative;
  
  
}
.btn-one span {
	transition: all 0.3s;
  font-size: 25px;
  
}
.btn-one::before {
	content: '';
	position: absolute;
	bottom: 0;
	left: 0;
	width: 100%;
	height: 100%;
	z-index: 1;
	opacity: 0;
	transition: all 0.3s;
	border-top-width: 1px;
	border-bottom-width: 1px;
	border-top-style: solid;
	border-bottom-style: solid;
	border-top-color: rgba(255,255,255,0.5);
	border-bottom-color: rgba(255,255,255,0.5);
	transform: scale(0.1, 1);
  
}
.btn-one:hover span {
	letter-spacing: 1px;
}
.btn-one:hover::before {
	opacity: 1;	
	transform: scale(1, 1);	
}
.btn-one::after {
	content: '';
	position: absolute;
	bottom: 0;
	left: 0;
	width: 100%;
	height: 100%;
	z-index: 1;
	transition: all 0.3s;
	background-color: rgba(255,255,255,0.1);
}
.btn-one:hover::after {
	opacity: 0;	
	transform: scale(0.1, 1);
}

} 
  </style>
  <div class="container container-podcast-list">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <h3 class="page-title">
         Artigo
        </h3>
        <hr/>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
      @if (Auth::check())
      <form method="post" action="/artigo" id="ajax_form">
      {{csrf_field()}}
      <script src="https://cdn.tiny.cloud/1/yijv97ejjj4oothrpcbrt9llizpbwk3fw80spo32scaberh4/tinymce/5/tinymce.min.js" referrerpolicy="origin"/></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

		<div class="form-group">Titulo: <input  class="form-control" type="text" id="titulo" name="titulo" value="" /></div>
    <div class="form-group">Url imagem: <input  class="form-control" type="text" id="url" name="url" value="" /></div>
    <div class="form-group">
    <label for="form-group">Example textarea</label>
    <textarea class="form-control" name="texto" id="mytextarea" rows="4"></textarea>
  </div>
		<div class="form-group"><input class="form-control" type="submit" name="enviar" value="Enviar" /></div>
	</form>
  @endif
  @if($artigoItems)
  <?php 
   
 ?>
    
       
            
               
          
    
@foreach ($artigoItems as $row)
<div class="row mt-5 justify-content-center">
<div class="row w-100 eit">
<div class="col edit">
<h3><?php echo $row->titulo;?></h3>
<img src="<?php echo $row->url;?>" class="foto"  width="100%">
  <div><?php echo $row->mensagem;?></div>
</div>
</div>
</div>
<div class="row eit">
<div class="col-2">
<a class="href" href="/artigo/<?php echo $row->id;?>">
<div class="box-1">
  <div class="btn btn-one">
    <span>Continuar lendo</span>
  </div>
</div>
</a>
</div>
</div>
@endforeach


      </div>
    </div>
     @endif  

@endsection

@section('footer-scripts')
  @include('scripts.podcast-scripts')
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

  
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src='https://cdn.tiny.cloud/1/yijv97ejjj4oothrpcbrt9llizpbwk3fw80spo32scaberh4/tinymce/5/tinymce.min.js' referrerpolicy="origin">
  </script>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#ajax_form').submit(function(){
			var dados = jQuery( this ).serialize();

			jQuery.ajax({
				type: "POST",
				url: "/artigo",
				data: dados,
				success: function( data )
				{
					alert( data );
				}
			});
			
			return false;
		});
  });
  tinymce.init({
      selector: '#mytextarea'
    });
   
	</script>
  <style>
  .card-custom{
    width: 10px;
    margin:0
  }
  </style>
@endsection