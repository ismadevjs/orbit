

<div class="txm-wrapper">
                    <div class="txm-container">
                        <!-- Deposit Section -->
                        <div class="txm-header">
                            <h5 class="txm-header__title">المعاملات المعلقة (إيداع)</h5>
                            <p class="txm-header__subtitle">راجع وأدر جميع معاملات الإيداع المعلقة أدناه.</p>
                        </div>
                        <div class="txm-content">
                            @forelse ($investor->user->transactions->where('status', 'pending')->where('details.type', '!=', 'withdrawal') as $transaction)
                                <div class="txm-transaction txm-transaction--deposit">
                                    <div class="txm-transaction__header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="txm-transaction__id">المعاملة رقم #{{ $transaction->id }}</span>
                                            <span
                                                class="txm-transaction__amount">${{ number_format($transaction->details['base_amount'], 2) }}</span>
                                        </div>
                                    </div>
                                    @if ($transaction->details)
                                        <ul class="list-group p-3">
                                            @foreach(['type_payment', 'proof_of_payment', 'total_cost', 'tax_amount', 'base_amount'] as $key)
                                                @if(array_key_exists($key, $transaction->details))
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <strong>{{ __('messages.' . $key) }}:</strong>
                                                        @if ($key == 'proof_of_payment' && $transaction->details[$key])
                                                            <!-- Link for Lightbox -->
                                                            <a href="{{ asset('storage/' . $transaction->details[$key]) }}"
                                                                data-lightbox="proof-of-payment">
                                                                <img src="{{ asset('storage/' . $transaction->details[$key]) }}"
                                                                    alt="Proof of Payment" style="max-width: 100px; cursor: pointer;">
                                                            </a>
                                                        @else
                                                            <span>{{ $transaction->details[$key] }}</span>
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif




                                    <div class="txm-transaction__actions">
                                        <button class="txm-btn txm-btn--accept acceptButton" data-id="{{ $transaction->id }}">
                                            <i class="fas fa-check"></i>
                                            <span>قبول</span>
                                        </button>
                                        <button class="txm-btn txm-btn--decline declineButton" data-id="{{ $transaction->id }}">
                                            <i class="fas fa-times"></i>
                                            <span>رفض</span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="txm-empty">
                                    <div class="txm-empty__icon">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <p class="txm-empty__text">لا توجد معاملات إيداع معلقة.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Withdrawal Section -->
                <div class="txm-wrapper mt-4">
                    <div class="txm-container">
                        <div class="txm-header">
                            <h5 class="txm-header__title">المعاملات المعلقة (سحب)</h5>
                            <p class="txm-header__subtitle">راجع وأدر جميع معاملات السحب المعلقة أدناه.</p>
                        </div>
                        <div class="txm-content">
                            @forelse ($investor->user->transactions->where('status', 'pending')->where('details.type', 'withdrawal') as $transaction)
                                <div class="txm-transaction txm-transaction--withdrawal">
                                    <div class="txm-transaction__header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="txm-transaction__id">المعاملة رقم #{{ $transaction->id }}</span>
                                            <span
                                                class="txm-transaction__amount">${{ number_format($transaction->details['base_amount'], 2) }}</span>
                                        </div>
                                    </div>

                                    @if ($transaction->details)
                                        <ul class="list-group p-3">
                                            @foreach(['type_payment', 'total_cost', 'tax_amount', 'base_amount'] as $key)
                                                @if(array_key_exists($key, $transaction->details))
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <strong>{{ __('messages.' . $key) }}:</strong>
                                                        <span>{{ $transaction->details[$key] }}</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif


                                    <div class="txm-transaction__actions">
                                        <button class="txm-btn txm-btn--accept acceptButton" data-id="{{ $transaction->id }}">
                                            <i class="fas fa-check"></i>
                                            <span>قبول</span>
                                        </button>
                                        <button class="txm-btn txm-btn--decline declineButton" data-id="{{ $transaction->id }}">
                                            <i class="fas fa-times"></i>
                                            <span>رفض</span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="txm-empty">
                                    <div class="txm-empty__icon">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <p class="txm-empty__text">لا توجد معاملات سحب معلقة.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
