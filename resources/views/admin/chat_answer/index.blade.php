<?php foreach ($chat_answer as $key => $answer) { ?>

<div class="list-group-item list-group-item-action flex-column align-items-start mb-2">
    <div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1">Admin</h5>
      <small class="text-muted"><?php echo date('Y-m-d',strtotime($answer->date)); ?></small>
    </div>
    <p class="mb-1"><?php echo $answer->question_reply; ?></p>
</div>
<?php }?>