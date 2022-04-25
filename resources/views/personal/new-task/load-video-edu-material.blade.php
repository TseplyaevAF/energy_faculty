<style>
    video {
        width: 100%    !important;
        height: auto   !important;
    }
</style>
<div class="form-group" style="width: 100%">
    <video id="videoPlayer" controls>
        @php
            $modelId = explode('/',$eduMaterial->task)[0];
            $mediaId = explode('/',$eduMaterial->task)[2];
            $filename = explode('/',$eduMaterial->task)[3];
        @endphp
        <source src="{{ route('personal.task.download', [$modelId, $mediaId, $filename]) }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>
