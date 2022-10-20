<div class="modal fade" id="ModeratorConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Вы точно хотите одобрить?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('application.approve') }}">
                @csrf
                <input type="hidden" class="applicationToBeApproved" name="appId">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Нет</button>
                    <button type="submit" class="btn btn-primary">Да</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="table-modal modal fade" id="ModeratorRejectionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectAppTitle"> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">


                <form method="POST" action="" id="messageForm">
                    @csrf
                    <input type="hidden" class="applicationToBeApproved" name="appId">
                    <input type="hidden" name="moderator" value="true">
                    <div class="scroll-modal mt-11px">
                        <div class="table-modal-head">
                            <input type="hidden" name="type" value="storage">
                            <label class="field-style">
                                <span>Причина</span>
                                <textarea rows="4" cols="55" name="message" id="message"></textarea>
                            </label>

                        </div>

                    </div>
                    <div class="w-100 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary mb-3">OK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


