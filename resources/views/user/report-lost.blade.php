@extends('layouts.user')

@section('title', 'Lapor Barang Hilang')

@section('content')
    @livewire('user.report-item', ['type' => 'hilang'])
@endsection
