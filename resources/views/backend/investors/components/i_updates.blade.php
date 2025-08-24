

@if($investor->user->kycRequest->additional_info)

<div class="block block-rounded block-fx-shadow">
    <div class="block-content bg-body-light text-center">
        <!-- Header Section -->
        <h3 class="block-title mb-3 text-primary text-center ">
            {{ $investor->user->kycRequest->additional_info }}
        </h3>

    </div>
</div>
@endif

@if($investor->incentiveInvestors->isNotEmpty())
<div class="card shadow-lg p-4 mb-4 rounded">
    <div class="card-body text-center">
        <!-- Title Section with animation -->
        <h3 class="card-title text-primary mb-3 animate__animated animate__fadeIn">
            {{ $investor->user->kycRequest->additional_info ?? 'لديك حوافز مثيرة في انتظارك!' }}
        </h3>

        <!-- Incentive Information Section -->
        <p class="card-text text-muted mb-4 animate__animated animate__fadeIn animate__delay-1s">
            أنت مؤهل للحصول على الحوافز التالية:
        </p>

        <!-- Incentive Wallet Section -->
        <div class="card bg-white p-4 rounded-lg shadow-sm mb-4">
            <div class="d-flex justify-content-center align-items-center mb-3">
                <!-- Wallet Icon -->
                <i class="fas fa-wallet fa-4x text-success"></i>
            </div>
            <h4 class="text-muted mb-2">رصيد الحوافز</h4>

            <!-- Display Available Bonus -->
            @foreach($investor->incentiveInvestors as $incentive)
                <h3 class="text-primary mb-4">
                    {{ number_format($investor->user->wallet->pending_bonus + $investor->user->wallet->bonus / 100, 2) }}$
                </h3>
            @endforeach

            <!-- Pending Bonus Section -->
            @if($investor->user->wallet->pending_bonus > 0)
                <p class="text-warning">قيمة الحوافز المعلقة:
                    {{ number_format($investor->user->wallet->pending_bonus, 2) }}$
                </p>
            @else
                <p class="text-muted">لا توجد حوافز معلقة في الوقت الحالي.</p>
            @endif


        </div>
    </div>
</div>
@endif
