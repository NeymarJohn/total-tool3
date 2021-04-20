
        <div class="col-xs-10">
            <div class='rating-stars '>
                <ul>
                    <li class='star @if($rating->rating >= 1) selected @endif' title='Poor' data-value='1' >
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                    <li class='star @if($rating->rating >= 2) selected @endif' title='Fair' data-value='2'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                    <li class='star @if($rating->rating >= 3) selected @endif' title='Good' data-value='3'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                    <li class='star @if($rating->rating >= 4) selected @endif' title='Excellent' data-value='4'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                    <li class='star @if($rating->rating >= 5) selected @endif' title='WOW!!!' data-value='5'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                </ul>
            </div>

            <div class="font-light">
                {!! ucfirst(nl2br($rating->comment)) !!}
                <input type="hidden" id="ratingID" name="ratingID" @if(!is_null($rating)) value="{{ $rating->id }}" @endif>
            </div>
        </div>
        <div class="col-xs-2 ">
            <div class="pull-right">
                <a href="javascript:;"  @if(!is_null($rating)) onclick="editComment({{ $rating->id }});" @endif
                class="btn btn-sm btn-info btn-rounded btn-outline edit-type"><i
                            class="fa fa-edit"></i> @lang('app.edit')</a>
                <a href="javascript:;"   @if(!is_null($rating)) onclick="deleteComment({{ $rating->id }});" @endif
                class="btn btn-sm btn-danger btn-rounded btn-outline delete-type"><i
                            class="fa fa-times"></i> @lang('app.remove')</a>
            </div>
        </div>

    <!--/row-->


