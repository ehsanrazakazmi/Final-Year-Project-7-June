

<?php $__env->startSection('content'); ?>

<div>
    <div class="alert alert-secondary mx-4" role="alert">
        <span class="text-white">
            <strong>View the Orders to Change their status and update them to Transfer to Technicians...  here!</strong>
        </span>
    </div>

    <div class="row">
        <div class="col-12" >
            <div class="card mb-4 mx-4 p-3" style="background-color: rgb(172, 245, 221)">
                <div class="card-header pb-0">
                    
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id = 'myTable'>
                            <thead>
                                <tr style="font-style: oblique">
                                    <th>Orders ID</th>
                                    <th>By</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($order->id); ?></td>
                                    <td><?php echo e($order->name); ?></td>
                                    <td>3</td>
                                    <td><?php echo e($order->total / 100); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($order->created_at)->format('d/m/Y')); ?></td>
                                    <td>
                                        <span class="badge bg-<?php if($order->status == 'pending'): ?>warning
                                            <?php elseif($order->status == 'processing'): ?>info
                                            <?php elseif($order->status == 'shipped'): ?>success
                                            <?php elseif($order->status == 'cancelled'): ?>danger <?php endif; ?>
                                            " >
                                            <?php echo e($order->status); ?>

                                        </span>
                                    </td>
                                    <td>
                                         <div class="d-flex mt-2" style="gap: 5px">
                                            <a href="<?php echo e(route('orders.view',$order->id)); ?>" class="btn btn-secondary">View</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.user_type.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\FYP-7-6-2023\resources\views/laravel-examples/orders/index.blade.php ENDPATH**/ ?>