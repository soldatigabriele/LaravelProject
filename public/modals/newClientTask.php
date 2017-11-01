<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    <h3 class="modal-title">Task Details</h3>
</div>
<div class="modal-body">
    <div class="col-md-12">
        <form action="/addtag" id="client-task-form">
            <label for="description">Task Name:</label>
            <input type="text" id="task-content" class="form-control" value="" name="content">
            <label for="description">Task Description:</label>
            <input type="text" id="task-description" class="form-control" value="" name="description">
<!--            <label for="instructions">Task Instructions:</label>-->
<!--            <input type="text" id="task-instructions" class="form-control" value="" name="instructions">-->
            <label for="description">URL:</label>
            <input type="text" id="task-url" class="form-control" value="" name="url">
            <hr>
            <div class="radio">
                <label><input type="radio" id="radio-0" value="0" name="tag" checked>General Task</label>
            </div>
            <div class="radio">
                <label><input type="radio" id="radio-4" value="4" name="tag">Development Sign-Off</label>
            </div>
            <div class="radio">
                <label><input type="radio" id="radio-5" value="5" name="tag">Design Sign-Off</label>
            </div>
            <div class="radio">
                <label><input type="radio" id="radio-1" value="1" name="tag">Upload a file</label>
            </div>
            <div class="radio">
                <label><input type="radio" id="radio-2" value="2" name="tag">Setup GoCardless Account</label>
            </div>
            <div class="radio">
                <label><input type="radio" id="radio-3" value="3" name="tag">Payment</label>
            </div>
            <div class="col-md-12" class="">
                <div class="col-md-3" class="form-group">
                    <label for="amount">Amount £:</label>
                </div>
                <div class="col-md-6" class="form-group">
                    <input type="text" id="payment-amount" class="form-control" value="" name="amount"
                           style="width:80px" maxlength="6">
                </div>
            </div>
            <div class="clearfix"></div>
            <br>
            <div class="col-md-4" class="form-group">
                <label for="active">Active:</label>
                <input type="checkbox" id="ct-active" name="active" value="1" class="">
            </div>
            <div class="col-md-4" class="form-group">
                <label for="visible">Visible:</label>
                <input type="checkbox" id="ct-visible" name="visible" value="1" class="" checked>
            </div>
        </form>
    </div>
</div>
<div class="clearfix"></div><br>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" id="task-confirm">Save changes</button>
</div>