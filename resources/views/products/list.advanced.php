@extends('layouts/products')
<h1>所有产品</h1>
<p>显示所有产品...</p>

@if($next)
<a href="{{ $next }}">下一个</a>
@endif

