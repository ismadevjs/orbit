@if ($investor->user && $investor->user->referrals && $investor->user->referrals->count() > 0)
                    <!-- Colleagues -->
                    <h2 class="content-heading d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">المدعوين عن طريق رابط الاحالة</span>
                    </h2>
                    <div class="row">

                        @foreach ($investor->user->referrals as $referral)
                            <a href="{{ route('investors.details.id', ['id' => $referral->investor->id]) }}">
                                <div class="col-md-6 col-xl-3">
                                    <div
                                        class="block block-rounded block-link-shadow
                                                                                                                                                                                    @if ($referral->active) bg-success @else bg-gd-leaf @endif">
                                        <div class="block-content block-content-full text-center">
                                            <img class="img-avatar img-avatar96 img-avatar-thumb"
                                                src="{{ asset($referral->avatar ? 'storage/' . $referral->avatar : 'assets/img/team/user.png') }}"
                                                alt="">
                                            <div class="text-white fw-semibold mt-2">{{ $referral->name ?? 'مجهول' }}</div>
                                            <div class="text-white fs-sm">{{ $referral->role ?? 'دور غير معروف' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                    </div>
                    <!-- END Colleagues -->
                @endif
