@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/show.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
  <h1 class='pagetitle'>レビュー詳細ページ</h1>
  <div class="card">
    <div class="card-body d-flex">
      <section class='review-main'>
        <h2 class='h2'>ユーザー名：{{ $user_name->name}}</h2>
        <h2 class='h2'>本のタイトル</h2>
        <p class='h2 mb20'>{{ $review->title }}</p>
        <h2 class='h2'>レビュー本文</h2>
        <p>{{ $review->body }}</p>
        @if(!empty($review->url))
        <h3 class='h2'>商品紹介URL</h2>
        <a href= "{{ ($review->url)}}" target="_blank">商品詳細へ</a>
        @endif
      
      
        @foreach($comments as $comment)
          <p>コメント：{{ $comment->description}}</p>
        @endforeach
      
        
      <div class="row justify-content-center container">
        <div class="col-md-10">
          <form method='POST' action="{{ route('comment') }}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                  <div class="form-group">
                  <label>コメントフォーム</label>
                    <textarea class='description form-control' name='description' placeholder='コメントを入力'></textarea>
                  </div>
                  <input type='submit' class='btn btn-primary' value='コメントを送信する'>
                </div>
            </div>
          </form>
        </div>
    </div>
      </section>
      <aside class='review-image'>
@if(!empty($review->image))
        <img class='book-image' src="{{ asset('storage/images/'.$review->image) }}">
@else
        <img class='book-image' src="{{ asset('images/dummy.png') }}">
@endif
      </aside>
    </div>
      @if(\Auth::id() == $user_id)
      <div class='btn'>
        <form method='POST' action="{{ route('reviewdelete') }}" enctype="multipart/form-data">
          @csrf
          <input type='submit' class='btn btn-info btn-back mb20' value='レビューを削除'>
        </form>
      </div>
      @endif
    <a href="{{ route('index') }}" class='btn btn-info btn-back mb20'>一覧へ戻る</a>
  </div>
</div>
@endsection