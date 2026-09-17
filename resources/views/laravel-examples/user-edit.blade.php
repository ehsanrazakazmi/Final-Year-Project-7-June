@extends('layouts.user_type.auth')

@section('content')

<div>

    <div class="row">
        <div class="col-12">
            <h1 class="page-title">Edit Technicians</h1>
<div class="container">
  <div class="row mb-5">
      <div class="col-12">
          <div class="card">
              <div class="card-header">
                  <h5>Edit Technicians</h5>
              </div>
              <div class="card-body">
                  <form action="{{route('adminpanel.technicians.edit', $asdf->id)}}" method="post" enctype="multipart/form-data">
                      @csrf
                      @method('PUT')
                      <div class="row mb-3">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name">TiTle</label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is_invalid @enderror" value="{{$asdf->title}}">
                                @error('title')
                                <span class="invalid-feedback">
                                    <strong>{{$message}}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name">Price</label>
                                <input type="number" name="price" id="price" class="form-control @error('price') is_invalid @enderror" value="{{$asdf->price}}">
                                @error('price')
                                <span class="invalid-feedback">
                                    <strong>{{$message}}</strong> 
                                </span>
                                @enderror
                            </div>
                        </div>
                      </div> 
                      <!-- .row  -->

                      <div class="row mb-3">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                    <option value="">Select Option</option>
                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}" {{$asdf->category_id == $category->id ? 'selected' : ''}}>{{$category->name}}</option>       
                                    @endforeach                               
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback">
                                        <strong>{{$message}}</strong>
                                    </span>                                   
                                @enderror
                                
                            </div> 
                        </div>


                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="image">Image</label>
                                <input type="file" name="image" class="form-control @error('image') is_invalid @enderror">
                                @error('image')
                                    <span class="invalid-feedback">
                                        <strong>{{$message}}</strong>
                                    </span>                                   
                                @enderror
                                <img src="{{asset('storage/' .$asdf->image)}}" width="100px" height="100px">
                            </div> 
                        </div>

                      </div> 
                       <!-- .row  -->


                       <div class="row mb-3">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="availabilities">Availability (Timing)</label> <br>
                                @foreach ($availabilities as $availability)
                                     <div class="form-check form-check-inline">
                                        <input type="checkbox" name="availabilities[]" class="form-check-input" value="{{$availability->id}}" {{in_array($availability->id, $asdf->availabilities->pluck('id')->toArray())?'checked' : ''}} >
                                        <label for="{{$availability->name}}"class="form-check-label" >{{$availability->name}}</label>
                                  </div>
                                @endforeach
                                @error('availabilities')
                                    <span class="invalid-feedback">
                                        <strong>{{$message}}</strong>
                                    </span>                                   
                                @enderror                                                              
                            </div> 
                        </div>
                      </div> 
                       <!-- .row  -->


                       <div class="row mb-3">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" cols="30" rows="10" class="form-control @error('description') is_invalid @enderror" placeholder="Description about technicians ........">{{$asdf->description}}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">
                                        <strong>{{$message}}</strong>
                                    </span>                                   
                                @enderror                                                             
                            </div> 
                        </div>
                      </div> 

                      <div class="form-group text-end">
                          <button type="submit" class="btn btn-primary">Update</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>

  </div>
        </div>
    </div>
</div>
 
@endsection