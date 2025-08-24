@extends('layouts.backend')

@can('browse investor_contract')
    @section('content')
        <div class="container py-5">
            <div class="row justify-content-center">
                <!-- Contract Sign Card -->
                <div class="col-sm-12 col-md-6 mb-4">
                    <a href="{{ route('investors.contract.view', ['contractId' => auth()->user()->contract->id]) }}" class="card-link">
                        <div class="card contract-card shadow-lg">
                            <div class="card-body text-center">
                                <div class="icon mb-3">
                                    <svg width="50" height="50" viewBox="0 0 24 24" fill="#28a745" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 16.17L4.83 12L3.41 13.41L9 19L21 7L19.59 5.59L9 16.17Z"/>
                                    </svg>
                                </div>
                                <h5 class="card-title">توقيع العقد</h5>
                                <p class="card-text">وقّع عقدك بأمان وبشكل إلكتروني.</p>
                                <span class="btn btn-gradient-success">توقيع العقد</span>
                            </div>
                            <div class="overlay"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <style>
            /* Ensure right-to-left layout for Arabic */
            .container {
                direction: rtl;
            }

            .card-link {
                text-decoration: none;
                color: inherit;
            }

            .contract-card {
                background: linear-gradient(135deg, #ffffff, #f0f4f8);
                border: none;
                border-radius: 25px;
                transition: transform 0.4s ease, box-shadow 0.4s ease;
                cursor: pointer;
                overflow: hidden;
                position: relative;
                transform: scale(1);
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding: 20px;
            }

            .contract-card::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: linear-gradient(45deg, rgba(40, 167, 69, 0.2), rgba(109, 213, 237, 0.2));
                transform: rotate(45deg);
                transition: opacity 0.4s ease;
                opacity: 0;
                pointer-events: none;
            }

            .contract-card:hover::before {
                opacity: 1;
            }

            .contract-card:hover {
                transform: scale(1.05);
                box-shadow: 0 25px 40px rgba(0, 0, 0, 0.2);
            }

            .btn-gradient-success {
                background: linear-gradient(45deg, #28a745, #6dd5ed);
                border: none;
                color: #fff;
                padding: 12px 25px;
                border-radius: 50px;
                transition: background 0.3s ease, transform 0.3s ease;
                box-shadow: 0 10px 20px rgba(40, 167, 69, 0.4);
                display: inline-block;
                margin-top: 20px;
                font-weight: bold;
            }

            .btn-gradient-success:hover {
                background: linear-gradient(45deg, #6dd5ed, #28a745);
                transform: translateY(-3px);
                box-shadow: 0 15px 25px rgba(40, 167, 69, 0.6);
            }

            .icon svg {
                transition: transform 0.3s ease;
            }

            .contract-card:hover .icon svg {
                transform: scale(1.1);
            }

            .overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.1);
                opacity: 0;
                transition: opacity 0.4s ease;
                pointer-events: none;
            }

            .contract-card:hover .overlay {
                opacity: 1;
            }

            @media (max-width: 576px) {
                .contract-card {
                    margin-bottom: 30px;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.contract-card');

                cards.forEach(card => {
                    card.addEventListener('mouseenter', () => {
                        const svg = card.querySelector('svg');
                        svg.style.transform = 'scale(1.1)';
                    });

                    card.addEventListener('mouseleave', () => {
                        const svg = card.querySelector('svg');
                        svg.style.transform = 'scale(1)';
                    });
                });
            });
        </script>
    @endpush
@endcan
