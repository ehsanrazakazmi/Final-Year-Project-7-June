<?php $__env->startSection('name', '<?php echo e($product->title); ?>'); ?>
<?php $__env->startSection('content'); ?>
     <?php if(session()->has('addedToCart')): ?>
         <section class="pop-up">
            <div class="pop-up-box">
                <div class="pop-up-title">
                <?php echo e(session()->get('addedToCart')); ?>

            </div>
            <div class="pop-up-actions">
                <a href="<?php echo e(route('home')); ?>" class="btn btn-outlined">Browse More servies...</a>
                <a href="<?php echo e(route('cart')); ?>" class="btn btn-primary">order confirmation !</a>
            </div>
        </div>
         </section>
     <?php endif; ?>
    <div class="product-page">
        <div class="container">
            <div class="product-page-row">
                <section class="product-page-image">
                    <img src="<?php echo e(asset('storage/'.$product->image)); ?>" alt="">
                </section>
                <section class="product-page-details">
                    <p class="p-title"><?php echo e($product->title); ?></p>
                    <p class="p-price">Visit Charges : <?php echo e($product->price); ?></p>
                    <p class="p-category"><?php echo e($product->category->name); ?></p>
                    <p class="p-description"><?php echo e($product->description); ?></p>
                    <form action="<?php echo e(route('addToCart',$product->id)); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="p-form">
                            <div class="p-colors">
                                <label for="availability">Availability</label>
                                <select name="availability" id="availability" required>
                                    <option value="">-- Select Timing --</option>
                                    <?php $__currentLoopData = $product->availabilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $availability): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($availability->id); ?>"><?php echo e($availability->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="p-quantity">
                                <label for="quantity">No. of Task</label>
                                <input type="number"name="quantity" min="1" max="100" value=1 required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-cart"> Order Now</button>
                    </form>

                </section>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\FYP-7-6-2023\resources\views/pages/product.blade.php ENDPATH**/ ?>