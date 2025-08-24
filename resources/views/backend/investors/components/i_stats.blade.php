
<div class="row">
                    <!-- Row #2 -->
                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                            <div class="block-content block-content-full">
                                <div class="py-5 text-center">
                                    <div class="fs-2 fw-bold text-primary">{{ currency($investor->user->wallet->capital) }}
                                    </div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">المحفضة</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                            <div class="block-content block-content-full">
                                <div class="py-5 text-center">
                                    <div class="fs-2 fw-bold text-success">{{ currency($investor->user->wallet->profit) }}
                                    </div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">الأرباح</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                            <div class="block-content block-content-full text-end">
                                <div class="py-5 text-center">
                                    <div class="fs-2 fw-bold text-info">
                                        {{ currency($investor->user->transactions->where('status', 'completed')->sum('amount')) }}
                                    </div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">الإجمالي</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                            <div class="block-content block-content-full text-end">
                                <div class="py-5 text-center">
                                    <div class="fs-2 fw-bold text-warning">
                                        {{ \Carbon\Carbon::parse($investor->user->kycRequest->created_at)->locale('ar')->diffForHumans() }}
                                    </div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">تاريخ التسجيل</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- END Row #2 -->
                </div>

