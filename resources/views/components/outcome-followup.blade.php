<div class="mb-2 quotation-follow-up">
    <button class="__add" type="button" @click="followup_show = !followup_show; followup_channel = (followup_show ? 'Email' : '')">
        <span>Add Follow-Up</span>
        <img x-show="!followup_show" src="{{ asset('assets/img/icon-plus-circle.svg') }}">
        <img x-show="followup_show" src="{{ asset('assets/img/icon-minus-circle.svg') }}">
    </button>
    <div class="quotation-follow-up__content" x-show="followup_show" x-cloak>
        <span class="__sep"></span>
        <div class="mb-2 d-flex gap-3">
            <label class="form-label">Channel</label>
            <div class="d-flex gap-3">
                <input type="hidden" name="followup_channel" value="">
                <label class="form-check">
                    <input type="radio" name="followup_channel" x-model="followup_channel" class="form-check-input" value="Email">
                    <div class="form-check-label">Email</div>
                </label>
                <label class="form-check">
                    <input type="radio" name="followup_channel" x-model="followup_channel" class="form-check-input" value="Call">
                    <div class="form-check-label">Call</div>
                </label>
                <label class="form-check">
                    <input type="radio" name="followup_channel" x-model="followup_channel" class="form-check-input" value="Text">
                    <div class="form-check-label">Text</div>
                </label>
            </div>
        </div>
        <div class="mb-2">
            <label class="form-label">Feedback <span style="color:#B80000">*</span></label>
            <select name="followup_feedback" class="form-select" x-model="followup_feedback" @change="conditional_feedback">
                <option value="">Select Option</option>
                <optgroup label="Positive">
                    <option value="Requested revisions">Requested revisions</option>
                    <option value="Interested - Awaiting decision">Interested - Awaiting decision</option>
                    <option value="Client comparing proposals">Client comparing proposals</option>
                    <option value="Sent updated quote">Sent updated quote</option>
                    <option value="Won - Confirmed booking">Won - Confirmed booking</option>
                </optgroup>
                <optgroup label="Neutral">
                    <option value="Follow-up made - No response yet">Follow-up made - No response yet</option>
                    <option value="Call scheduled / Follow-up planned">Call scheduled / Follow-up planned</option>
                    <option value="Cargo is not ready yet">Cargo is not ready yet</option>
                    <option value="Client delayed decision">Client delayed decision</option>
                    <option value="Other (specify in comment)">Other (specify in comment)</option>
                </optgroup>
                <optgroup label="Negative">
                    <option value="Price too high">Price too high</option>
                    <option value="Received quote too late">Received quote too late</option>
                    <option value="Chose another provider">Chose another provider</option>
                    <option value="Canceled shipment">Canceled shipment</option>
                    <option value="Lost - Other reason">Lost - Other reason</option>
                </optgroup>
            </select>
        </div>
        <div class="mb-2" x-show="followup_comment_show">
            <label class="form-label">Comment <span style="color:#B80000" x-text="followup_comment_required ? '*' : ''"></span></label>
            <input type="text" class="form-control" name="followup_comment" x-model="followup_comment">
        </div>
    </div>
</div>
