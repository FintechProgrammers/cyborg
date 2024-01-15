<div class="card">
    <div class="card-body p-4">
        <div class="row">
            <div class="col-lg-12">
                <div>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Title</label>
                        <input class="form-control" type="text" name="title" placeholder=""
                            value="{{ isset($news) ? $news->title : null }}" id="example-text-input">
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">News Body</label>
                        <textarea name="news_body" class="form-control" id="" cols="30" rows="10">
                            {{ isset($news) ? $news->content : null }}
                        </textarea>
                        @error('news_body')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Photo</label>
                        @if (isset($news) && !empty($news->image))
                            <div class="card mb-3 col-lg-6">
                                <img class="card-img img-fluid" src="{{ $news->image }}" alt="Card image">
                            </div>
                        @endif
                        <input class="form-control" type="file" name="photo">
                        @error('photo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-lg">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
