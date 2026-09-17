

<?php $__env->startSection('content'); ?>

<div>

    <div class="row">
        <div class="col-12">
            <h1 class="page-title">Create Technicians</h1>
<div class="container" >
  <div class="row mb-5">
      <div class="col-12">
          <div class="card" style="background-color: rgb(172, 245, 221)">
              <div class="card-header" style="background-color: rgb(172, 245, 221)">
                  <h5>Create Technicians</h5>
              </div>
              <div class="card-body">
                  <form action="<?php echo e(route('adminpanel.technicians.store')); ?>" method="post" enctype="multipart/form-data">
                      <?php echo csrf_field(); ?>
                      <div class="row mb-3">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name">Title</label>
                                <input type="text" name="title" id="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('title')); ?>">
                                <?php $__errorArgs = ['title'];
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
                        </div>


                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name">Price</label>
                                <input type="number" name="price" id="price" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('price')); ?>">
                                <?php $__errorArgs = ['price'];
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
                        </div>
                      </div> 
                      <!-- .row  -->

                      <div class="row mb-3">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id" class="form-control <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Select Option</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>       
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                               
                                </select>
                                <?php $__errorArgs = ['category_id'];
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
                        </div>


                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="image">Image</label>
                                <input type="file" name="image" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['image'];
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
                        </div>

                      </div> 
                       <!-- .row  -->


                       <div class="row mb-3">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="colors">Timinig</label>
                                <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <div class="form-check form-check-inline">
                                        <input type="checkbox" name="colors[]" class="form-check-input" value="<?php echo e($color->id); ?>">
                                        <label for="<?php echo e($color->name); ?>"class="form-check-label" ><?php echo e($color->name); ?></label>
                                  </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php $__errorArgs = ['colors'];
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
                        </div>
                      </div> 
                       <!-- .row  -->


                       <div class="row mb-3">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" cols="30" rows="10" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Description about technicians ........"></textarea>
                                <?php $__errorArgs = ['description'];
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
                        </div>
                      </div>


                      <div class="form-group text-end">
                          <button type="submit" class="btn btn-primary">Create</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>

  </div>
        </div>
    </div>
</div>
 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user_type.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\FYP-7-6-2023\resources\views/laravel-examples/user-create.blade.php ENDPATH**/ ?>