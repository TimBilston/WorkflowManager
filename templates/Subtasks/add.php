<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Subtask $subtask
 * @var \Cake\Collection\CollectionInterface|string[] $tasks
 * @var \Cake\Collection\CollectionInterface|string[] $status
 */
?>

<!-- <script src="/jquery.min.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<style>
    .del_btn {
        margin-left: 20px;
    }
</style>

<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Subtasks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="subtasks form content">
            <?= $this->Form->create($subtask) ?>
            <fieldset>
                <legend><?= __('Add Subtask') ?></legend>
                <?php
                    echo $this->Form->control('title', ['value' => empty($_GET['title']) ? '' : $_GET['title'], 'required' => false, 'readonly' => true, 'disabled' => true]);
                    echo '<div id="z_js_wrap_item"></div>';
                    echo $this->Form->control('description', ['type' => 'textarea', 'required' => false, 'class' => 'z_js_textarea_content']);
                    echo $this->Form->button(__('Add'), ['type' => 'button', 'id' => 'z_js_btn_add_item']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['type' => 'button', 'id' => 'z_js_btn_submit']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script id="z_js_tpl_item" type="text/html">
    <div class="z_js_item">
        <input class="z_js_id" value="{id}" type="hidden">
        <input class="z_js_status" {status_checked} type="checkbox">
        <span class="z_js_content">{content}</span>
        <a href="javascript:" class="z_js_btn_del del_btn">Delete</a>
    </div>
</script>
<script>
    $(function () {
        var parentItemHtml = '';
        $('.z_js_sub_task_item', window.opener.document).each(function (i, e) {
            var statusChecked = $(this).find('.z_js_sub_task_status').prop('checked') ? 'checked' : '';
            var statusAdminChecked = $(this).find('.z_js_sub_task_status_admin').prop('checked') ? 'checked' : '';
            var content = $(this).find('.z_js_sub_task_content').val();
            var id = '';
            if ($(this).find('.z_js_sub_task_id').length > 0) {
                id = $(this).find('.z_js_sub_task_id').val();
            }
            var tpl = $('#z_js_tpl_item').html();
            tpl = tpl.replace('{id}', id);
            tpl = tpl.replace('{status_checked}', statusChecked);
            tpl = tpl.replace('{content}', content);
            tpl = tpl.replace('{status_admin_checked}', statusAdminChecked);
            parentItemHtml += tpl;
        });
        $('#z_js_wrap_item').html(parentItemHtml);

        $('#z_js_btn_add_item').click(function () {
            var content = $.trim($('.z_js_textarea_content').val());
            if (!content) {
                alert('Please enter the content');
                return false;
            }
            var tpl = $('#z_js_tpl_item').html();
            tpl = tpl.replace('{id}', '');
            tpl = tpl.replace('{status_checked}', '');
            tpl = tpl.replace('{content}', content);
            tpl = tpl.replace('{status_admin_checked}', '');
            $('.z_js_textarea_content').val('');
            $('#z_js_wrap_item').append(tpl);
        });

        $(document).on('click', '.z_js_btn_del', function () {
            $(this).parent().remove();
        });

        $('#z_js_btn_submit').click(function () {
            var itemHtml = '';
            $('.z_js_item').each(function (i, e) {
                var statusChecked = $(this).find('.z_js_status').prop('checked') ? ' checked' : '';
                var statusAdminChecked = $(this).find('.z_js_status_admin').prop('checked') ? ' checked' : '';
                var id =  $(this).find('.z_js_id').val();
                var content =  $(this).find('.z_js_content').text();
                itemHtml += '<div class="z_js_sub_task_item">';
                itemHtml += '<input class="z_js_sub_task_id" value="' + id + '" name="sub_task_id[]" type="hidden" />';
                itemHtml += '<input class="z_js_sub_task_status" value="1" name="sub_task_status_' + i + '"' + statusChecked + ' type="checkbox" />';
                itemHtml += '<span class="sub_task_content">' + content + '</span>';
                itemHtml += '<input class="z_js_sub_task_content" name="sub_task_content[]" value="' + content + '" type="hidden" />';
                itemHtml += '<input class="z_js_sub_task_status_admin" value="1" name="sub_task_status_admin_' + i + '"' + statusAdminChecked + ' type="checkbox" style="display: none" />';
                itemHtml += '</div>';
            });
            $('#z_js_wrap_sub_task_item', window.opener.document).html(itemHtml);
            window.close();
        });
    });
</script>
