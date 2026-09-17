<?php $__env->startSection('content'); ?>

<div>

    <div class="row">
        <div class="col-12">
            <h1 class="page-title">Edit Availability</h1>
<div class="container" >
    <div class="row mb-5">
        <div class="col-md-6 offser-md-3">
            <div class="card" style="background-color: rgb(172, 245, 221)">
                <div class="card-header" style="background-color: rgb(172, 245, 221)">
                    <h5>Create Timing (Availibility)</h5>
                </div>
                <div class="card-body" >
                    <form action="<?php echo e(route('adminpanel.availability.store')); ?>" method="post"> 
                        <?php echo csrf_field(); ?>
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name')); ?>">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> 
                        </div>
                        <div class="form-group mb-3">
                            <label for="code">Available From</label>
                            <input type="time" name="available_from" id="available_from" class="form-control <?php $__errorArgs = ['available_from'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('available_from')); ?>">
                            <?php $__errorArgs = ['available_from'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> 
                        </div>
                        <div class="form-group mb-3">
                            <label for="code1">Available To</label>
                            <input type="time" name="available_to" id="available_to" class="form-control <?php $__errorArgs = ['available_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('available_to')); ?>">
                            <?php $__errorArgs = ['available_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> 
                        </div>
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background-color: rgb(172, 245, 221)">
                    <h5>Availibility</h5>
                </div>
                <div class="card-body" style="background-color: rgb(172, 245, 221)">
                    <table class="table table-stripped" id="myTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Published</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $availabilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $availability): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                           
                            <tr>
                                <td><?php echo e($availability->id); ?></td>
                                <td><?php echo e($availability->name); ?></td>
                                <td>
                                    <div style="display: flex; align-items:center; gap:10px">
                                    <?php echo e($availability->available_from); ?> <span style="display: inline-block; width:30px; border-radius:50%; height:30px; background: <?php echo e($availability->available_from); ?>;"></span>
                                </div>
                                </td>

                                <td><?php echo e($availability->available_to); ?></td>
                                
                                <td><?php echo e(\Carbon\Carbon::parse($availability->created_at)->format('d/m/Y')); ?></td>
                                <td>
                                    <form action="<?php echo e(route('adminpanel.availability.destroy', $availability->id)); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
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
    </div>
</div>
 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user_type.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\FYP-7-6-2023\resources\views/laravel-examples/availibility/index.blade.php ENDPATH**/ ?>