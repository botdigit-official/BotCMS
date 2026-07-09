<div class="border-t border-slate-800 pt-6 space-y-4">
    <h3 class="text-sm font-semibold uppercase tracking-wider text-indigo-400 mb-4">E-Commerce Product Specifications (SQL Table Injection)</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- SKU -->
        <div>
            <label for="sku" class="block text-sm font-medium text-slate-300">Product SKU</label>
            <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" placeholder="e.g. SKU-12345"
                   class="mt-1 block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
        </div>
        
        <!-- Price -->
        <div>
            <label for="price" class="block text-sm font-medium text-slate-300">Price (USD)</label>
            <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $product->price) }}" placeholder="0.00"
                   class="mt-1 block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
        </div>

        <!-- Stock -->
        <div>
            <label for="stock_quantity" class="block text-sm font-medium text-slate-300">Stock Quantity</label>
            <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" placeholder="0"
                   class="mt-1 block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
        </div>
    </div>

    <!-- Featured Checkbox -->
    <div class="flex items-center space-x-3 pt-2">
        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
               class="h-4 w-4 rounded bg-slate-900 border-slate-700 text-blue-600 focus:ring-blue-500 focus:ring-offset-slate-900">
        <label for="is_featured" class="text-sm text-slate-300">Highlight this product in the public shop directory</label>
    </div>
</div>
