@extends('layouts.app')

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div>
            @include('breadcrumbs')

            <div class="py-6 leading-normal">
                @include ('threads._question')

                <replies @added="repliesCount++" @removed="repliesCount--"></replies>
            </div>
        </div>
    </thread-view>
@endsection
