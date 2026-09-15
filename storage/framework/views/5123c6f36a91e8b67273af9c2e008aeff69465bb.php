

<?php $__env->startSection('content'); ?>

<div>

           <div class="container" style="background-color: rgb(172, 245, 221)">
                <div class="row">
                   <div class="col-md-6 offser-md-1">
                    <form action="<?php echo e(route ('sendmail')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="title">
                           <h2>Mail info</h2>
                         </div>
                         <div class="info">
                        <label for="email"></label>
                        <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Send to"> 
                       
                        <label for="cc"></label>
                        <input type="email" name="cc" class="form-control" id="cc" aria-describedby="emailHelp" placeholder="cc">
                       
                        <label for="bcc"></label>
                        <input type="email" name="bcc" class="form-control" id="bcc" aria-describedby="emailHelp" placeholder=" bcc">
                       
                        <label for="subject"></label>
                        <input type="text" name="subject" class="form-control" id="subject" placeholder="Enter your subject here">
                       
                        <label for="mail"></label>
                        <textarea  class="form-control" name="body" id="mail" rows="8" placeholder="Type your content here ....."></textarea>
                        
                        <div class="form-group mt-3 text-end">
                           <button type="submit" class="btn btn-primary">Send</button>
                       </div>                       
                       </div>
                        </form>
                   </div>
                </div>
        </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user_type.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\FYP-7-6-2023\resources\views/mail/contact.blade.php ENDPATH**/ ?>