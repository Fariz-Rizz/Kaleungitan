@extends('layouts.user')

@section('title', 'Ajukan Klaim')

@section('content')
    @livewire('user.claim-item', ['item' => $item])
@endsection
