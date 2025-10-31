<!-- Hero -->
<section class="relative h-screen">
  <!-- Hero Background overlay -->
  <div class="absolute inset-0 z-10 bg-black/40"></div>
  <div class="absolute inset-0">
    <img src="/Coffee_St/public/assets/home_head.png" alt="Coffee Shop" class="h-full w-full object-cover" />
  </div>

  <!-- Hero Content -->
  <div class="relative z-20 flex h-full items-center">
    <div class="mx-auto ml-4 max-w-2xl md:ml-12 lg:ml-24 xl:ml-32">
      <div class="text-left">
        <span
          class="font-poppins mb-6 inline-block rounded-full border border-white/20 bg-white/10 px-6 py-2.5 text-sm tracking-wide text-white uppercase backdrop-blur-sm">
          Welcome to Coffee St.
        </span>
        <h1
          class="font-playfair mb-8 text-4xl leading-tight font-bold tracking-tight text-white sm:text-5xl md:text-6xl lg:text-7xl">
          Experience the<br />
          <span class="text-[#d4b78f]">Art of Coffee</span>
        </h1>
        <p
          class="font-poppins mb-10 max-w-xl text-base leading-relaxed tracking-wide text-white/90 sm:text-lg md:text-xl">
          Discover our carefully curated selection of premium coffee beans and
          artisanal brews, crafted just for you.
        </p>
        <div class="flex flex-col gap-5 sm:flex-row">
          <a href="/Coffee_St/public/pages/products.php"
            class="font-poppins inline-flex transform items-center rounded-full bg-[#30442B] px-8 py-4 text-base font-medium tracking-wide text-white shadow-lg transition-all duration-300 hover:scale-105 hover:bg-[#3a533a]">
            View Menu
            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </a>
          <a href="/Coffee_St/public/pages/about.php"
            class="font-poppins inline-flex transform items-center rounded-full border-2 border-white/20 bg-white/10 px-8 py-4 text-base font-medium tracking-wide text-white backdrop-blur-sm transition-all duration-300 hover:scale-105 hover:bg-white/20">
            About Us
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Featured Products Section -->
<section class="bg-white pt-20 pb-16">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <!-- Section Header -->
    <div class="mb-16 text-center">
      <h2 class="font-playfair mb-4 text-4xl font-bold text-[#30442B]">
        Featured Delights
      </h2>
      <p class="mx-auto max-w-2xl text-lg text-gray-600">
        Discover our handcrafted signature drinks and delectable treats
      </p>
    </div>
    <!-- Products Carousel -->
    <div class="featured-products-carousel relative">
      <div class="swiper-container overflow-hidden">
        <div class="swiper-wrapper">
          <!-- Product Card 1 -->
          <div class="swiper-slide p-4">
            <div
              class="group flex h-[420px] flex-col overflow-hidden rounded-2xl shadow-lg transition-all duration-300 hover:shadow-xl">
              <div class="relative shrink-0 bg-[#30442B] pt-4 pb-8">
                <div class="relative mx-auto h-48 w-48">
                  <img src="/Coffee_St/public/assets/cafe_late.png" alt="Caramel Latte"
                    class="h-full w-full rounded-lg object-contain transition-transform duration-500 group-hover:scale-105" />
                </div>
              </div>
              <div class="flex grow flex-col border-t border-gray-100 bg-white p-6">
                <h3 class="font-playfair mb-2 text-xl font-bold text-[#30442B]">
                  Caramel Latte
                </h3>
                <p class="mb-4 line-clamp-2 text-gray-600">
                  Rich espresso with steamed milk and caramel drizzle
                </p>
              </div>
            </div>
          </div>
          <!-- Product Card 2 -->
          <div class="swiper-slide p-4">
            <div
              class="group flex h-[420px] flex-col overflow-hidden rounded-2xl shadow-lg transition-all duration-300 hover:shadow-xl">
              <div class="relative shrink-0 bg-[#30442B] pt-4 pb-8">
                <div class="relative mx-auto h-48 w-48">
                  <img src="/Coffee_St/public/assets/cheesecake.png" alt="Blueberry Cheesecake"
                    class="h-full w-full rounded-lg object-contain transition-transform duration-500 group-hover:scale-105" />
                </div>
              </div>
              <div class="flex grow flex-col border-t border-gray-100 bg-white p-6">
                <h3 class="font-playfair mb-2 text-xl font-bold text-[#30442B]">
                  Blueberry Delight
                </h3>
                <p class="mb-4 line-clamp-2 text-gray-600">
                  Fresh blueberry parfait with vanilla cream
                </p>
              </div>
            </div>
          </div>
          <!-- Product Card 3 -->
          <div class="swiper-slide p-4">
            <div
              class="group flex h-[420px] flex-col overflow-hidden rounded-2xl shadow-lg transition-all duration-300 hover:shadow-xl">
              <div class="relative shrink-0 bg-[#30442B] pt-4 pb-8">
                <div class="relative mx-auto h-48 w-48">
                  <img src="/Coffee_St/public/assets/white_mocha.png" alt="Artisan Latte"
                    class="h-full w-full rounded-lg object-contain transition-transform duration-500 group-hover:scale-105" />
                </div>
              </div>
              <div class="flex grow flex-col border-t border-gray-100 bg-white p-6">
                <h3 class="font-playfair mb-2 text-xl font-bold text-[#30442B]">
                  Artisan Latte
                </h3>
                <p class="mb-4 line-clamp-2 text-gray-600">
                  Hand-crafted latte with signature leaf art design
                </p>
              </div>
            </div>
          </div>
          <!-- Product Card 4 -->
          <div class="swiper-slide p-4">
            <div
              class="group flex h-[420px] flex-col overflow-hidden rounded-2xl shadow-lg transition-all duration-300 hover:shadow-xl">
              <div class="relative shrink-0 bg-[#30442B] pt-4 pb-8">
                <div class="relative mx-auto h-48 w-48">
                  <img src="/Coffee_St/public/assets/cinammon.png" alt="Artisan Pastries"
                    class="h-full w-full rounded-lg object-contain transition-transform duration-500 group-hover:scale-105" />
                </div>
              </div>
              <div class="flex grow flex-col border-t border-gray-100 bg-white p-6">
                <h3 class="font-playfair mb-2 text-xl font-bold text-[#30442B]">
                  Almond Chocolate Croissant
                </h3>
                <p class="mb-4 line-clamp-2 text-gray-600">
                  Buttery croissant filled with chocolate and almonds
                </p>
              </div>
            </div>
          </div>
        </div>
        <!-- Navigation Buttons -->
        <div class="absolute top-1/2 -left-4 z-10 -translate-y-1/2 transform">
          <button
            class="swiper-button-prev flex h-12 w-12 items-center justify-center rounded-full bg-white text-[#30442B] shadow-lg transition-all duration-300 hover:bg-[#30442B] hover:text-white">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
          </button>
        </div>
        <div class="absolute top-1/2 -right-4 z-10 -translate-y-1/2 transform">
          <button
            class="swiper-button-next flex h-12 w-12 items-center justify-center rounded-full bg-white text-[#30442B] shadow-lg transition-all duration-300 hover:bg-[#30442B] hover:text-white">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="transform pt-16 pb-20 opacity-0 translate-y-10" id="split-screen-section">
  <div class="mx-auto max-w-7xl">
    <div class="flex flex-col items-center md:flex-row">
      <!-- Left Side - Image -->
      <div class="relative h-[600px] w-full overflow-hidden md:w-1/2">
        <div class="absolute inset-0 bg-[#30442B]/10"></div>
        <img src="/Coffee_St/public/assets/home_pastries.png" alt="Premium Coffee Experience"
          class="h-full w-full scale-105 transform object-cover transition-transform duration-700 hover:scale-100" />
      </div>

      <!-- Right Side - Content -->
      <div class="w-full px-8 py-12 md:w-1/2 md:py-0 lg:px-16">
        <div class="max-w-lg">
          <h2 class="font-playfair mb-6 text-4xl leading-tight font-bold text-[#30442B] lg:text-5xl">
            Crafting Moments of Pure Coffee Delight
          </h2>
          <p class="mb-8 text-lg leading-relaxed text-gray-600">
            Experience the artistry of coffee-making at its finest. Each cup
            tells a story of carefully selected beans, expert roasting, and
            passionate craftsmanship. Join us in celebrating the perfect brew.
          </p>
          <a href="/Coffee_St/public/pages/products.php"
            class="group inline-flex items-center rounded-full bg-[#30442B] px-8 py-3 text-white transition-colors duration-300 hover:bg-[#405939]">
            <span class="mr-2">Explore Our Coffee and Pastries</span>
            <svg class="h-5 w-5 transform transition-transform duration-300 group-hover:translate-x-1" fill="none"
              stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="bg-[#FDFBF6] px-4 py-16 md:px-8" id="benefits-grid">
  <div class="mx-auto max-w-6xl">
    <div class="mb-12 text-center">
      <h2 class="mb-4 text-3xl font-bold text-[#30442B] md:text-4xl">
        Why Choose Our Coffee Shop?
      </h2>
      <a href="/Coffee_St/public/pages/about.php#why-choose-us"
        class="group inline-flex items-center gap-2 text-[#30442B] transition-colors hover:text-[#967259]">
        <span class="text-lg">Learn more about us</span>
        <svg class="h-5 w-5 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg>
      </a>
    </div>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
      <!-- Freshly Roasted Coffee -->
      <div
        class="benefit-card transform rounded-xl bg-white p-6 opacity-0 shadow-md transition-all duration-300 hover:scale-105 hover:shadow-lg">
        <div class="icon-container mb-4 text-center">
          <svg class="mx-auto h-16 w-16 text-[#967259]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16m-7 6h7"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M12 8c0 1.657-1.343 3-3 3S6 9.657 6 8s1.343-3 3-3 3 1.343 3 3z"></path>
          </svg>
        </div>
        <h3 class="mb-2 text-center text-xl font-semibold text-[#30442B]">
          Freshly Roasted Coffee
        </h3>
        <p class="text-center text-gray-600">
          Our beans are roasted in small batches daily to ensure peak flavor and
          aroma.
        </p>
      </div>

      <!-- Handcrafted Pastries -->
      <div
        class="benefit-card transform rounded-xl bg-white p-6 opacity-0 shadow-md transition-all duration-300 hover:scale-105 hover:shadow-lg">
        <div class="icon-container mb-4 text-center">
          <svg class="mx-auto h-16 w-16 text-[#967259]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4"></path>
          </svg>
        </div>
        <h3 class="mb-2 text-center text-xl font-semibold text-[#30442B]">
          Handcrafted Pastries
        </h3>
        <p class="text-center text-gray-600">
          Each pastry is lovingly crafted by our expert bakers using traditional
          recipes.
        </p>
      </div>

      <!-- Ethically Sourced -->
      <div
        class="benefit-card transform rounded-xl bg-white p-6 opacity-0 shadow-md transition-all duration-300 hover:scale-105 hover:shadow-lg">
        <div class="icon-container mb-4 text-center">
          <svg class="mx-auto h-16 w-16 text-[#967259]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
            </path>
          </svg>
        </div>
        <h3 class="mb-2 text-center text-xl font-semibold text-[#30442B]">
          Ethically Sourced
        </h3>
        <p class="text-center text-gray-600">
          We partner directly with farmers to ensure fair prices and sustainable
          practices.
        </p>
      </div>

      <!-- Baked Daily -->
      <div
        class="benefit-card transform rounded-xl bg-white p-6 opacity-0 shadow-md transition-all duration-300 hover:scale-105 hover:shadow-lg">
        <div class="icon-container mb-4 text-center">
          <svg class="mx-auto h-16 w-16 text-[#967259]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <h3 class="mb-2 text-center text-xl font-semibold text-[#30442B]">
          Baked Daily
        </h3>
        <p class="text-center text-gray-600">
          Fresh batches of pastries are baked throughout the day for maximum
          freshness.
        </p>
      </div>

      <!-- Community Focus -->
      <div
        class="benefit-card transform rounded-xl bg-white p-6 opacity-0 shadow-md transition-all duration-300 hover:scale-105 hover:shadow-lg">
        <div class="icon-container mb-4 text-center">
          <svg class="mx-auto h-16 w-16 text-[#967259]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 009.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
            </path>
          </svg>
        </div>
        <h3 class="mb-2 text-center text-xl font-semibold text-[#30442B]">
          Community Focus
        </h3>
        <p class="text-center text-gray-600">
          We're proud to be a gathering place for our local community since
          2020.
        </p>
      </div>

      <!-- Expert Baristas -->
      <div
        class="benefit-card transform rounded-xl bg-white p-6 opacity-0 shadow-md transition-all duration-300 hover:scale-105 hover:shadow-lg">
        <div class="icon-container mb-4 text-center">
          <svg class="mx-auto h-16 w-16 text-[#967259]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
            </path>
          </svg>
        </div>
        <h3 class="mb-2 text-center text-xl font-semibold text-[#30442B]">
          Expert Baristas
        </h3>
        <p class="text-center text-gray-600">
          Our certified baristas are passionate about crafting the perfect cup
          for you.
        </p>
      </div>
    </div>
  </div>
</section>


<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<!-- Initialize Swiper -->
<script>
  $(function () {
    new Swiper(".swiper-container", {
      slidesPerView: 1,
      spaceBetween: 32,
      loop: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      },
    });
  });
</script>