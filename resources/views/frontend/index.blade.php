@extends('layouts.frontend')



@section('title', 'FliSim - Activate your eSIM in 60 seconds — campus-ready')
@php
    $carousel = getTable('carousels');
    $buttons = !empty($carousel?->buttons) ? json_decode($carousel->buttons) : [];
    $buttonCount = is_array($buttons) || is_object($buttons) ? count($buttons) : 0;
@endphp
@section('content')
    <!-- ===== HERO AREA START ======= -->
    @include('frontend.components.hero')
    <!-- ===== HERO AREA END ======= -->

    <!-- ===== CHOOSE AREA START ======= -->
    @include('frontend.components.choose')

    <!-- ===== CHOOSE AREA END ======= -->

    <!-- ===== EMAIL INNOVATION START ======= -->
    @include('frontend.components.email')
    <!-- ===== EMAIL INNOVATION END ======= -->

    <!-- ===== WORK AREA START ======= -->
    @include('frontend.components.work')
    <!-- ===== WORK AREA END ======= -->
    <!-- ===== TES AREA START ======= -->
    @include('frontend.components.testimonials')
    <!-- ===== TES AREA END ======= -->



    @include('frontend.components.faqs')

    {{-- {{ LandingPageSection}} --}}
    <!-- ===== STAPES AREA START ======= -->
    @include('frontend.components.sections')
    <!-- ===== STAPES AREA END ======= -->


@endsection
