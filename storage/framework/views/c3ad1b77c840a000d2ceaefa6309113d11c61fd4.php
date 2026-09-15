

<?php $__env->startSection('content'); ?>

<div >
    <div class="alert alert-secondary mx-4" role="alert">
        <span class="text-white">
            <strong>Add, Edit, Delete the Users (Technicians) here!</strong>
        </span>
    </div>

    <div class="row" >
        <div class="col-12" >
            <div class="card mb-4 mx-4 p-3" style="background-color: rgb(172, 245, 221)">
                <div class="card-header pb-0" style="background-color: rgb(172, 245, 221)">
                    <div class="mb-3 d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0" style="font-size: 24px">All Technicians</h5>
                        </div>
                        <a href="<?php echo e(route('adminpanel.technicians.create')); ?>" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp;Add Technician</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id = 'myTable'>
                            <thead>
                                <tr>
                                    <th >
                                        ID
                                    </th>
                                    <th >
                                        Photo
                                    </th>
                                    <th >
                                        Title
                                    </th>
                                    <th >
                                        Basic Pakage
                                    </th>
                                    <th >
                                        role
                                    </th>
                                    <th>
                                        Availability Time
                                    </th>
                                    <th >
                                        Creation Date
                                    </th>
                                    <th >
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $prroducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($product->id); ?></p>
                                    </td>
                                    
                                    <td>
                                        <div>
                                            <img src="<?php echo e(asset('storage/'. $product->image)); ?>" alt="img N/A" class="avatar avatar-sm me-3">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($product->title); ?></p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($product->price); ?></p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($product->category->name); ?></p>
                                    </td>
                                    <td>
                                        <?php $__currentLoopData = $product->colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <div ><?php echo e($color->code); ?> to <?php echo e($color->code1); ?> </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                    
                                    <td><?php echo e(\Carbon\Carbon::parse($product->created_at)->format('d/m/Y')); ?></td>
                                    <td>
                                         <div class="d-flex" style="gap: 5px">
                                            <a href="<?php echo e(route('adminpanel.technicians.edit',$product->id)); ?>" class="btn btn-secondary">Edit</a>
                                        <form action="<?php echo e(route('adminpanel.technicians.destroy', $product->id)); ?>" method="post">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger">Del</button>                                       
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
<?php echo $__env->make('layouts.user_type.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\FYP-7-6-2023\resources\views/laravel-examples/user-management.blade.php ENDPATH**/ ?>