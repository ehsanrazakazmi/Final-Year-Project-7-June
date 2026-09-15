

<?php $__env->startSection('content'); ?>

<div>
    
    <h1 class="page-title">Show Order</h1>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 p-3">
                <div class="card-header pb-0">
                    
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id = 'myTable' style="background-color:rgb(90, 88, 245)"  id = 'myTable'>
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>name</th>
                                    <th>address</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $order; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($order->id); ?></td>
                                    <td><?php echo e($order->name); ?></td>
                                    <td><?php echo e($order->address); ?></td>
                                    <td>
                                        <span class="badge bg-<?php if($order->status == 'accepted'): ?>success
                                             <?php endif; ?>
                                            ">
                                            <?php echo e($order->status); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <form action="<?php echo e(route('technicianpanel.status.update',$order->id)); ?>" method="post">
                                            <?php echo method_field('PUT'); ?>
                                            <?php echo csrf_field(); ?>
                                           
                                            
                                            <div class="d-flex mt-2" >
                                                <button type="submit"  class="btn btn-secondary">Accept Order</button>
                                            </div>
                                        </form>
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





















































































<?php echo $__env->make('layouts.technician', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\FYP-7-6-2023\resources\views/technician/pages/dashboard.blade.php ENDPATH**/ ?>