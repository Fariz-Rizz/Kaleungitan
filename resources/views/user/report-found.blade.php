@extends('layouts.user')

@section('title', 'Lapor Barang Temuan')

@section('content')
    @livewire('user.report-item', ['type' => 'temuan'])
@endsection
