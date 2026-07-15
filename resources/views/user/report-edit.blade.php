@extends('layouts.user')

@section('title', 'Edit Laporan')

@section('content')
    @livewire('user.report-item', ['item' => $item])
@endsection
