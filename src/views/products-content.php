<?php
// Products Page Main Content Partial
?>

<!-- Header Section with Search -->
<div class="w-full bg-[#30442B] py-6">
  <div class="container mx-auto px-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold text-white">Discover Our Menu</h1>
        <p class="text-gray-200 text-sm md:text-base">Find something that suits your taste</p>
      </div>
      <!-- Search Input -->
      <div class="relative w-full md:w-96">
        <input type="text" id="product-search"
          class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent backdrop-blur-sm transition-all duration-300"
          placeholder="Search our menu..." />
        <svg class="w-6 h-6 text-white/70 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none"
          stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
    </div>
  </div>
</div>

<!-- Main Content Area -->
<div class="container mx-auto px-4 py-8">
  <div class="flex flex-col lg:flex-row gap-8">
    <!-- Category Sidebar -->
    <div class="w-full lg:w-80 xl:w-96 mb-8 lg:mb-0">
      <nav
        class="lg:sticky lg:top-28 bg-white rounded-xl shadow-xl p-6 lg:max-h-[calc(100vh-140px)] lg:overflow-y-auto border border-gray-100">
        <!-- Drinks Section -->
        <div class="space-y-8 mb-12">
          <div class="mb-4"> <!-- All Products moved above the Drinks header -->
            <button
              class="cursor-pointer category-btn group w-full flex items-center px-6 py-5 rounded-xl transition-all duration-300 hover:bg-[#30442B]/5 text-gray-700 hover:text-[#30442B] bg-[#30442B]/5"
              data-category="all">
              <span class="flex items-center gap-4 category-label">
                <span class="category-icon">🌟</span>
                <span class="category-text">All Products</span>
              </span>
            </button>
          </div>
          <h3 class="text-[#30442B] font-bold text-3xl px-2 mb-6">Drinks</h3>
          <div class="space-y-4">
            <button
              class="cursor-pointer category-btn group w-full flex items-center px-6 py-4 rounded-xl transition-all duration-300 hover:bg-[#30442B]/5 text-gray-700 hover:text-[#30442B]"
              data-category="hot-coffee">
              <span class="flex items-center gap-4 category-label">
                <span class="category-icon">☕</span>
                <span class="category-text">Hot Coffee</span>
              </span>
            </button>
            <button
              class="cursor-pointer category-btn group w-full flex items-center px-6 py-4 rounded-xl transition-all duration-300 hover:bg-[#30442B]/5 text-gray-700 hover:text-[#30442B]"
              data-category="iced-coffee">
              <span class="flex items-center gap-4 category-label">
                <span class="category-icon">🧊</span>
                <span class="category-text">Iced Coffee</span>
              </span>
            </button>
            <button
              class="cursor-pointer category-btn group w-full flex items-center px-6 py-4 rounded-xl transition-all duration-300 hover:bg-[#30442B]/5 text-gray-700 hover:text-[#30442B]"
              data-category="frappe">
              <span class="flex items-center gap-4 category-label">
                <span class="category-icon">🥤</span>
                <span class="category-text">Frappe</span>
              </span>
            </button>
            <button
              class="cursor-pointer category-btn group w-full flex items-center px-6 py-4 rounded-xl transition-all duration-300 hover:bg-[#30442B]/5 text-gray-700 hover:text-[#30442B]"
              data-category="non-coffee">
              <span class="flex items-center gap-3 category-label">
                <span class="category-icon">🍵</span>
                <span class="category-text">Non-Coffee</span>
              </span>
            </button>
          </div>
        </div>

        <!-- Divider -->
        <div class="h-px bg-gray-200"></div>

        <!-- Pastries Section -->
        <div class="space-y-6">
          <h3 class="text-[#30442B] font-bold text-2xl px-2 mb-6">Pastries & Desserts</h3>
          <div class="space-y-4">
            <button
              class="cursor-pointer category-btn group w-full flex items-center px-6 py-4 rounded-xl transition-all duration-300 hover:bg-[#30442B]/5 text-gray-700 hover:text-[#30442B]"
              data-category="pastries">
              <span class="flex items-center gap-3 category-label">
                <span class="category-icon">🥐</span>
                <span class="category-text">Pastries</span>
              </span>
            </button>
            <button
              class="cursor-pointer category-btn group w-full flex items-center px-6 py-4 rounded-xl transition-all duration-300 hover:bg-[#30442B]/5 text-gray-700 hover:text-[#30442B]"
              data-category="cakes">
              <span class="flex items-center gap-3 category-label">
                <span class="category-icon">🍰</span>
                <span class="category-text">Cakes</span>
              </span>
            </button>
            <button
              class="cursor-pointer category-btn group w-full flex items-center px-6 py-4 rounded-xl transition-all duration-300 hover:bg-[#30442B]/5 text-gray-700 hover:text-[#30442B]"
              data-category="buns">
              <span class="flex items-center gap-3 category-label">
                <span class="category-icon">🥖</span>
                <span class="category-text">Buns</span>
              </span>
            </button>
          </div>
        </div>

        <!-- Mobile Indicator -->
        <div class="lg:hidden text-sm text-gray-400 text-center">
          <span>Scroll horizontally to see more categories →</span>
        </div>
      </nav>
    </div>

    <!-- Products Grid -->
    <div class="flex-1">
      <div class="grid products-equal-rows grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8" id="products-grid">
        <?php
        // Fetch products from backend (server-side render)
        $ROOT = dirname(__DIR__, 2);
        $pdo = require $ROOT . '/backend/db.php';
        require_once $ROOT . '/backend/models/Product.php';
        require_once $ROOT . '/backend/configs/cloudinary.php';

        // Helpers
        $slug = function ($text) {
          $s = strtolower(trim((string) $text));
          $s = preg_replace('/[^a-z0-9]+/i', '-', $s);
          return trim($s, '-');
        };
        // Map known categories to consistent slugs; fallback to slugified text
        $categorySlugFor = function ($raw) use ($slug) {
          $key = strtolower(trim((string) $raw));
          $map = [
            'hot coffee' => 'hot-coffee',
            'iced coffee' => 'iced-coffee',
            'frappe' => 'frappe',
            'non-coffee' => 'non-coffee',
            'pastries' => 'pastries',
            'pastry' => 'pastries',
            'cakes' => 'cakes',
            'cake' => 'cakes',
            'buns' => 'buns',
            'bun' => 'buns',
            'all products' => 'all',
            'all' => 'all',
          ];
          if (isset($map[$key]))
            return $map[$key];
          return $slug($raw);
        };
        // Try multiple possible keys for category (case/alias tolerant)
        $extractCategory = function (array $row) use ($categorySlugFor) {
          $candidates = ['category', 'Category', 'CATEGORY', 'category_slug', 'CategorySlug', 'categorySlug', 'type', 'Type', 'TYPE', 'product_category', 'ProductCategory', 'productCategory'];
          foreach ($candidates as $k) {
            if (isset($row[$k]) && trim((string) $row[$k]) !== '') {
              return $categorySlugFor($row[$k]);
            }
          }
          // If nothing found, default to 'all'
          return 'all';
        };
        $imageUrlFor = function ($row) {
          if (!empty($row['image_url'])) {
            $url = $row['image_url'];
            if (strpos($url, 'res.cloudinary.com') !== false) {
              return cld_url_with($url, ['w' => 800]);
            }
            return $url;
          }
          if (!empty($row['cloudinary_public_id'])) {
            $built = cld_public_url($row['cloudinary_public_id'], ['w' => 800]);
            if ($built)
              return $built;
          }
          return '/public/assets/americano.png';
        };

        $products = Product::all($pdo);
        ?>
        <?php if (empty($products)): ?>
            <div
              class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-dashed border-[#30442B]/30 bg-white/70 py-20 text-center">
              <p class="text-xl font-semibold text-[#30442B]">Products coming soon.</p>
              <p class="mt-2 text-sm text-neutral-500">Please check back later while we brew something special.</p>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <?php
                $category = $extractCategory($product);
                $img = $imageUrlFor($product);
                ?>
                <div
                  class="product-card group flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition-all duration-300 hover:shadow-lg"
                  data-category="<?php echo htmlspecialchars($category); ?>"
                  data-id="<?php echo (int) ($product['id'] ?? 0); ?>">
                  <div class="relative h-72 overflow-hidden bg-[#30442B]">
                    <div class="absolute inset-0 flex items-center justify-center p-8">
                      <img src="<?php echo htmlspecialchars($img); ?>"
                        alt="<?php echo htmlspecialchars($product['name'] ?? ''); ?>"
                        class="max-h-48 w-auto transform drop-shadow-xl transition-transform duration-500 group-hover:scale-110"
                        loading="lazy" decoding="async" />
                    </div>
                    <div
                      class="absolute inset-0 bg-linear-to-r from-[#30442B]/80 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                    </div>
                  </div>
                  <div class="flex flex-1 flex-col bg-white p-6">
                    <div class="mb-2">
                      <h3
                        class="font-playfair text-xl font-bold text-gray-800 transition-colors duration-300 group-hover:text-[#30442B]">
                        <?php echo htmlspecialchars($product['name'] ?? ''); ?>
                      </h3>
                    </div>
                    <p class="mb-4 line-clamp-2 text-sm leading-relaxed text-gray-600">
                      <?php echo htmlspecialchars($product['description'] ?? ''); ?>
                    </p>
                    <div class="mt-auto flex items-center justify-between pt-2">
                      <span
                        class="text-xl font-bold text-[#30442B]">₱<?php echo number_format((float) ($product['price'] ?? 0), 2); ?></span>
                      <button
                        class="add-to-cart flex cursor-pointer items-center gap-2 rounded-full bg-[#30442B] px-4 py-2 text-sm font-medium text-white transition-colors duration-300 hover:bg-[#405939]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                          </path>
                        </svg>
                        Add to Cart
                      </button>
                    </div>
                  </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>