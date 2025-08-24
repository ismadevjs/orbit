<section class="blog-area space-top space-extra-bottom">
    <div class="container">
        <div class="row gx-30">
            <div class="col-lg-8 col-md-12">
                <div class="@if (Route::is('frontend.blog.posts')) blog-details-wrap @endif">
                    {{ $slot }}
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <aside class="sidebar-area">

                    <div class="widget widget_search">
                        <h3 class="widget_title">ابحث هنا</h3>
                        <form class="search-form" method="GET" action="{{ route('frontend.blog.filter') }}">
                            <input type="text" name="search" placeholder="ابحث.."
                                value="{{ request()->query('search') }}" required>
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>

                    <div class="widget widget_categories">
                        <h3 class="widget_title">الفئة</h3>
                        <ul>
                            @if (getTablesLimit('categories', 1))
                                @php
                                    $categories = getCategorie('blog');
                                @endphp
                                @if (is_iterable($categories))
                                    @foreach ($categories as $category)
                                        @if ($category)
                                            <li>
                                                <a
                                                    href="{{ route('frontend.blog.filter', ['category' => $category->id]) }}">
                                                    {{ $category->name }}
                                                    {{-- Uncomment the following if image support is needed
                                                                @if ($category->image)
                                                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                                                                @endif --}}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                @else
                                    <li>لم يتم العثور على فئات</li>
                                @endif
                            @endif
                        </ul>
                    </div>

                    <!-- ويدجت المشاركات الأخيرة -->
                    <div class="widget">
                        <h3 class="widget_title">أحدث المدونات</h3>
                        <div class="recent-post-wrap">
                            @if (getTablesLimit('posts', 1))
                                @foreach (getTables('posts') as $post)
                                    <div class="recent-post">
                                        <div class="recent-post-meta">
                                            <a href="{{ route('frontend.blog.posts', ['id' => $post->id]) }}">
                                                <img src="{{ asset('front/img/icon/calender.svg') }}"
                                                    alt="أيقونة التقويم">
                                                {{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="post-title">
                                                <a class="text-inherit"
                                                    href="{{ route('frontend.blog.posts', ['id' => $post->id]) }}">
                                                    {!! \Illuminate\Support\Str::limit($post->title, 50) !!}
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- ويدجت العلامات -->
                    <div class="widget widget_tag_cloud">
                        <h3 class="widget_title">العلامات الشائعة</h3>
                        <div class="tagcloud">
                            @if (getTablesLimit('tags', 1))
                                @foreach (getTablesLimit('tags', 10) as $tag)
                                    <a
                                        href="{{ route('frontend.blog.filter', ['tag' => $tag->id]) }}">{{ $tag->name }}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
