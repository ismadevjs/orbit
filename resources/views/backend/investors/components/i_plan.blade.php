@php
                    // Retrieve all plans
                    $plans = \App\Models\PricingPlan::all();

                    // Get the capital of the investor
                    $getCapital = $investor->user->wallet->capital;

                    // Find the applicable plan
                    $applicablePlan = null;

                    foreach ($plans as $plan) {
                        if ($getCapital >= $plan->min_amount) {
                            $applicablePlan = $plan;
                        }
                    }
                @endphp

                @if ($applicablePlan)
                    <div class="plan-card {{ strtolower($applicablePlan->name) }}-plan w-100">
                        <div class="animation-container"></div>
                        <div class="plan-content">
                            <h2 class="plan-title">
                                {{ __('messages.' . $applicablePlan->name) }}
                            </h2>
                            <p class="user-message">
                                {{ $applicablePlan->msg_investor }}
                                <!-- <span class="highlight">{{ __('messages.' . $applicablePlan->name) }}</span>.
                                توفر لك هذه الباقة المزايا التالية: -->
                            </p>
                            
                           
                        </div>
                    </div>
                @endif


