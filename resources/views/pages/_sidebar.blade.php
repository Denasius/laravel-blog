<div class="col-md-4" data-sticky_column>
    <div class="primary-sidebar">
        
        <aside class="widget news-letter">
            <h3 class="widget-title text-uppercase text-center">Get Newsletter</h3>
            @include('admin.errors')

            <form action="/subscribe" method="post">
                {{csrf_field()}}
                <input type="email" placeholder="Your email address" name="email">
                <input type="submit" value="Subscribe Now"
                       class="text-uppercase text-center btn btn-subscribe">
            </form>

        </aside>
        @if ($popularPosts)            
            <aside class="widget">
                <h3 class="widget-title text-uppercase text-center">Popular Posts</h3>
                @foreach ($popularPosts as $popular)
                    <div class="popular-post">


                        <a href="{{route('post.show', $popular->slug)}}" class="popular-img"><img src="{{$popular->getImage()}}" alt="{{$popular->title}}">

                            <div class="p-overlay"></div>
                        </a>

                        <div class="p-content">
                            <a href="{{route('post.show', $popular->slug)}}" class="text-uppercase">{{$popular->title}}</a>
                            <span class="p-date">{{$popular->getDate()}}</span>

                        </div>
                    </div>
                @endforeach
            </aside>
        @endif

        @if ($featuredPosts)
            <aside class="widget">
                <h3 class="widget-title text-uppercase text-center">Featured Posts</h3>
                
                <div id="widget-feature" class="owl-carousel">
                    @foreach ($featuredPosts as $featured)
                        <div class="item">
                            <div class="feature-content">
                                <img src="{{$featured->getImage()}}" alt="{{$featured->title}}">

                                <a href="{{route('post.show', $featured->slug)}}" class="overlay-text text-center">
                                    <h5 class="text-uppercase">{{$featured->title}}</h5>

                                   {!! $featured->description !!}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </aside>
        @endif
        
        @if ($recentPosts)
            <aside class="widget pos-padding">
                <h3 class="widget-title text-uppercase text-center">Recent Posts</h3>
                @foreach ($recentPosts as $recent)
                    <div class="thumb-latest-posts">


                        <div class="media">
                            <div class="media-left">
                                <a href="{{route('post.show', $recent->slug)}}" class="popular-img"><img src="{{$recent->getImage()}}" alt="{{$recent->title}}">
                                    <div class="p-overlay"></div>
                                </a>
                            </div>
                            <div class="p-content">
                                <a href="{{route('post.show', $recent->slug)}}" class="text-uppercase">{{$recent->title}}</a>
                                <span class="p-date">{{$recent->getDate()}}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </aside>
        @endif
        
        @if ($categories)
            <aside class="widget border pos-padding">
                <h3 class="widget-title text-uppercase text-center">Categories</h3>
                <ul>
                    @foreach ($categories as $cat)
                        <li>
                            <a href="{{route('category.show', $cat->slug)}}">{{$cat->title}}</a>
                            <span class="post-count pull-right"> {{$cat->posts()->count()}}</span>
                        </li>
                    @endforeach
                </ul>
            </aside>
        @endif
    </div>
</div>