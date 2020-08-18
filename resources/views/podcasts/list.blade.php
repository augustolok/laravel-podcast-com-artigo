@extends('layouts.app')

@section('template_title')
  Listen
@endsection

@section('content')
  @if($podcast_items)
    @include('podcasts.player')
  @endif
  <div class="container container-podcast-list">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <h3 class="page-title">
          My Podcast List
        </h3>
        <hr/>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        @if($podcast_items)
          @foreach ($podcast_items as $item)
            @include('podcasts.item')
          @endforeach
          <div class="row container-fluid">
            {{ $podcast_items->render() }}
            <h4>
  <span
     class="txt-rotate"
     data-period="2000"
     data-rotate='[ "Como foi o seu dia?", "Quer  conversa?", "Espero que esteja tudo bem!", "se precisar é só clicar aqui.", "gostaria muito de ouvir sobre seu dia!"  ]'></span>
</h1>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@section('footer-scripts')
  @include('scripts.podcast-scripts')
@endsection
<script>
var TxtRotate = function(el, toRotate, period) {
  this.toRotate = toRotate;
  this.el = el;
  this.loopNum = 0;
  this.period = parseInt(period, 10) || 2000;
  this.txt = '';
  this.tick();
  this.isDeleting = false;
};

TxtRotate.prototype.tick = function() {
  var i = this.loopNum % this.toRotate.length;
  var fullTxt = this.toRotate[i];

  if (this.isDeleting) {
    this.txt = fullTxt.substring(0, this.txt.length - 1);
  } else {
    this.txt = fullTxt.substring(0, this.txt.length + 1);
  }

  this.el.innerHTML = '<span class="wrap">'+this.txt+'</span>';

  var that = this;
  var delta = 300 - Math.random() * 100;

  if (this.isDeleting) { delta /= 2; }

  if (!this.isDeleting && this.txt === fullTxt) {
    delta = this.period;
    this.isDeleting = true;
  } else if (this.isDeleting && this.txt === '') {
    this.isDeleting = false;
    this.loopNum++;
    delta = 500;
  }

  setTimeout(function() {
    that.tick();
  }, delta);
};

window.onload = function() {
  var elements = document.getElementsByClassName('txt-rotate');
  for (var i=0; i<elements.length; i++) {
    var toRotate = elements[i].getAttribute('data-rotate');
    var period = elements[i].getAttribute('data-period');
    if (toRotate) {
      new TxtRotate(elements[i], JSON.parse(toRotate), period);
    }
  }
  // INJECT CSS
  var css = document.createElement("style");
  css.type = "text/css";
  css.innerHTML = ".txt-rotate > .wrap { border-right: 0.08em solid #666 }";
  document.body.appendChild(css);
};
</script>