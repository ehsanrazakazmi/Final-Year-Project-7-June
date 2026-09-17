<?php $__env->startSection('title', 'Cart'); ?>
<?php $__env->startSection('content'); ?>
    <header class="page-header">
        <h1>Cart</h1>
        <h3 class="cart-amount"></h3>
    </header>
    
    <main class="cart-page">
        <div class="container">
            <div class="cart-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Servicess</th>
                            <th>Time (slected)</th>
                            <th>Charges (Approx)</th>
                            <th>Number of Tasks</th>
                            <th>Category id</th>
                            <th>Action</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php if(session()->has('cart') && count(session()->get('cart')) > 0): ?>
                        <?php $__currentLoopData = session()->get('cart'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                            <tr>
                                <td>
                                    <a href="<?php echo e(route('product', $item['product']['id'])); ?>" class="cart-item-title">
                                        <img src="<?php echo e(asset('storage/'. $item['product']['image'])); ?>" alt="">
                                        <p><?php echo e($item['product']['title']); ?></p>
                                    </a>
                                </td>
                                <td><?php echo e($item['availability']['name']); ?></td>
                                <td>$<?php echo e($item['product']['price'] / 100); ?></td>
                                <td><?php echo e($item['quantity']); ?></td>
                                <td>$<?php echo e($item['category_id']); ?></td>
                                <td>
                                    <form action="<?php echo e(route('removeFromCart', $key)); ?>" method="post">
                                        <?php echo csrf_field(); ?>
                                        <button class="btn btn-primary">Delete</button>
                                    </form>
                                </td>

                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="cart-actions">
                <a href="<?php echo e(route('checkout')); ?>" class="btn btn-primary">Place order</a>
            </div>
        </div>
    </main>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\FYP-7-6-2023\resources\views/pages/cart.blade.php ENDPATH**/ ?>