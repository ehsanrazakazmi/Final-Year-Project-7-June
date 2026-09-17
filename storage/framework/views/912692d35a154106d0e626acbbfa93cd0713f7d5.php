<section  class="product-box">
    <div class="image">
        <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="N/A">
        <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->user()->wishlist->contains($product)): ?>
                <form action="<?php echo e(route('removeFromWishlist', $product->id)); ?>"method='post'>
                    <?php echo csrf_field(); ?>
                    <button class="add-to-wishlist" type="submit">Remove From Wishlist</button>
                </form>               
            <?php else: ?>
                <form action="<?php echo e(route('addToWishlist', $product->id)); ?>"method='post'>
                    <?php echo csrf_field(); ?>
                    <button class="add-to-wishlist" type="submit">Add to Wishlist</button>
                </form>                 
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <a href="<?php echo e(route('product', $product->id)); ?>">
        <div class="product-title"><?php echo e($product->title); ?></div>
        <div class="product-title">
           <?php echo e($product->category->name); ?>

        </div>
     <div class="color-plateletes">
        <?php $__currentLoopData = $product->availabilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $availability): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>    
        <div >
                <?php echo e($availability->available_from); ?> to <?php echo e($availability->available_to); ?> 
        </div>
        <div style="clear: both">

        </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
     </div>
     <div class="product-category"></div>
     <div class="product-price">Rs <?php echo e($product->price); ?></div>
    </a>
     
    </section>

<?php /**PATH C:\xampp\htdocs\FYP-7-6-2023\resources\views/components/product-box.blade.php ENDPATH**/ ?>