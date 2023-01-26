<div class="modal fade" id="MaskImagesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" style="max-width: 90vw;" role="document">
        <div class="points"></div>
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="info">
                <p class="points-info"></p>
                <input type="hidden" placeholder="Image URL">
            </div>
            <div class="scroll_body">
                <div class="output">

                    <div class="zoom d-none"></div>
                </div>
            </div>
            <button class="btn btn-white" id="putMask" style="display: flex; justify-content: center">Обновить</button>
        </div>
    </div>
</div>



<style>
    canvas {
        width: 100%;
        height: 100%;
        display: inline-block;
        border: 3px solid #333;
        background-size: cover;
        cursor: crosshair;
    }

    .zoom {
        width: 100px;
        height: 100px;
        border: 3px solid #333;
        background-size: fill;
        border-radius: 50px;
        cursor: crosshair;
    }

    .magnify {
        width: 100px;
        height: 100px;
        background: black;
        background-size: 400%;
        border: 2px solid #333;
    }
    .magnify-dot {

    }
</style>

<script>

</script>
