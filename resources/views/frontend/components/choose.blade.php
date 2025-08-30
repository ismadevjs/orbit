<div class="footer2 _relative" style="margin-top: -70px">
    <div class="container">
        <div class="row">
            <div class="footer-icon-box-all">
                @forelse (getTables('brands') as $brand)
                    <div class="footer-icon-box">
                        <div>
                            <img src="{{ asset('storage/' . $brand->image) }}" width="80" height="80" alt="{{ $brand->name }}" />
                        </div>
                        <div class="headding">
                            <p>{{ $brand->name }}</p>
                        </div>
                    </div>
                @empty
                    <p>No brands available.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="choose2 pb120 mt-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 m-auto text-center">
                <div class="headding2 mb-5">
                    <span class="span">{{ getHeading('plan')->title ?? '' }}</span>
                    <h2 class="text-anime-style-3">{{ getHeading('plan')->name ?? '' }}</h2>
                    <p class="mt-3 text-muted">
                        {{ getHeading('plan')->description ?? '' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="row" id="plan-container">
            @forelse (getTables('pricing_plans') as $key => $service)
                <div class="plan-card-wrapper mb-4 col-lg-6 col-md-6 {{ $key > 1 ? 'd-none' : '' }}"
                     data-aos="fade-up" data-aos-duration="{{ 700 + $key * 100 }}">
                    <div class="plan-card text-center p-4 h-100 shadow-sm rounded">
                        @if ($key === 0)
                            <div class="most-wanted-ribbon">Most Wanted</div>
                        @endif
                        @if ($service->image)
                            <div class="plan-img mb-3">
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}"
                                     class="img-fluid">
                            </div>
                        @endif
                        <h4 class="plan-title mb-2">{{ $service->name ?? '' }}</h4>
                        <p class="plan-desc text-muted">{{ $service->description ?? '' }}</p>
                        @if ($service->features)
                            <ul class="list-unstyled text-start mt-3 mb-4">
                                @foreach (json_decode($service->features, true) ?? [] as $ft)
                                    <li class="d-flex align-items-center mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2" aria-hidden="true"></i>
                                        <span>{{ $ft }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <a href="{{ url('checkout?plan=' . $service->id) }}" class="theme-btn2 mt-auto w-100">
                            Get This Plan
                        </a>
                    </div>
                </div>
            @empty
                <p>No plans available.</p>
            @endforelse
        </div>

        @if (count(getTables('pricing_plans')) > 2)
            <div class="text-center mt-4">
                <button id="loadMoreBtn" class="theme-btn2">Load More</button>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const loadMoreBtn = document.getElementById("loadMoreBtn");
    if (!loadMoreBtn) return;

    loadMoreBtn.addEventListener("click", function() {
        const allPlans = document.querySelectorAll(".plan-card-wrapper");
        let count = 0;

        // Update all shown plans to 3-per-row
        allPlans.forEach(plan => {
            if (!plan.classList.contains("d-none") || count < 3) {
                plan.classList.remove("col-md-6", "col-lg-6");
                plan.classList.add("col-md-4", "col-lg-4");
            }
            if (plan.classList.contains("d-none") && count < 3) {
                plan.classList.remove("d-none");
                count++;
            }
        });

        // Hide button if no hidden plans remain
        if (!document.querySelector(".plan-card-wrapper.d-none")) {
            loadMoreBtn.style.display = "none";
        }
    });
});
</script>

<style>
.plan-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.plan-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}

.plan-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #222;
    margin-bottom: 10px;
}

.plan-desc {
    font-size: 0.95rem;
    color: #666;
    margin-top: 10px;
    min-height: 70px;
}

.plan-img img {
    max-height: 80px;
    object-fit: contain;
}

.plan-card::before {
    content: "";
    position: absolute;
    top: -40px;
    right: -40px;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    border-radius: 50%;
    opacity: 0.12;
}

.most-wanted-ribbon {
    position: absolute;
    top: 15px;
    right: -40px;
    width: 150px;
    text-align: center;
    background: linear-gradient(45deg, #ff416c, #ff4b2b);
    color: #fff;
    font-weight: 700;
    font-size: 0.85rem;
    padding: 6px 0;
    transform: rotate(45deg);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    z-index: 20;
}

@media (max-width: 991px) {
    #plan-container .plan-card-wrapper {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

@media (max-width: 575px) {
    #plan-container .plan-card-wrapper {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>
