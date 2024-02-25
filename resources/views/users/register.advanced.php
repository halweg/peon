@extends('layouts/products')
<h1 class="text-xl font-semibold mb-4" >注册 </h1>
<form
    method="post"
    action="{{ $router->route('show-register-form') }}"
    class="flex flex-col w-full space-y-4"
>
    <input type="hidden" name="csrf" value="{{ csrf() }}" />
    <label for="name" class="flex flex-col w-full" >
        <span class="flex">姓名：</span>
        <input
            id="name"
            name="name"
            type="text"
            class="focus:outline-none focus:border-blue-300 border-b-2 border-gray-300"
            placeholder="Alex"
        />
    </label>
    <label for="email" class="flex flex-col w-full" >
        <span class="flex">电子邮件：</span>
        <input
            id="email"
            name="email"
            class="focus:outline-none focus:border-blue-300 border-b-2 border-gray-300"
            placeholder="email"
        />
    </label>
    <label for="password" class="flex flex-col w-full" >
        <span class="flex">密码： </span>
        <input
            id="password"
            name="password"
            type="password"
            class="focus:outline-none focus:border-blue-300 border-b-2 border-gray-300"
        />
    </label>
    @if($_SESSION['errors'] ?? false)
    <ol class="list-disc text-red-500" >
        @foreach($_SESSION['errors'] as $field => $errors)
        @foreach($errors as $error)
        <li>{{ $error }} </li>
        @endforeach
        @endforeach
    </ol>
    @endif
    <button type="submit" class="focus:outline-none focus:border-blue-500 focus:bg-blue-400 border-b-2 border-blue-400 bg-blue-300 p-2">
        注册
    </button>
</form>
