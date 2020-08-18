@extends('layouts.appartigo')

@section('template_title')
  Listen
@endsection

@section('content')
<style>

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
      <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<script>
  tinymce.init({
    selector: '#mytextarea'
  });
  tinymce.init({
    selector: '#titulo'
  });
</script>
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
    <div class="row mt-5 justify-content-center">
       
            
               
          
    
@foreach ($artigoItems as $row)
<div class="row w-100 eit">
<div class="col edit">
<h3><?php echo $row->titulo;?></h3>
<img src="<?php echo $row->url;?>" class="foto"  width="100%">
<?php echo $row->mensagem;?>
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
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

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
  
	</script>
  <style>
  .card-custom{
    width: 10px;
    margin:0
  }
  </style>
@endsection