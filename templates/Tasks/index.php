<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task[]|\Cake\Collection\CollectionInterface $tasks
 */
use Cake\ORM\TableRegistry;
echo $this->Html->css(['collapsible']);
?>

<div class="row">
    <aside class="column">
        <div class="side-nav">
            <?= $this->Html->link(__('Add Task'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
            <h4 class="heading"> <?=__('List Tasks') ?></h4>
        </div>
    </aside>
    <div class="column-responsive column-80" style="min-width: 68vw;">
        <div class="tasks index content">
            <?= $this->Html->link(__('New Task'), ['action' => 'add'], ['class' => 'button float-right']) ?>
            <h3><?= __('Tasks') ?></h3>
            <div class="table-responsive">
                <button class="collapsible">Overdue Tasks (<?php
                    $query = TableRegistry::getTableLocator()->get('Tasks')->find()->where(['status_id'=>3])->count();
                    echo $query;
                    ?>)</button>
                <div class="test">
                    <?=$this->element('indexTable',['status' => '3']);?>
                </div>

                <button class="collapsible">Current Tasks (<?php
                    $query = TableRegistry::getTableLocator()->get('Tasks')->find()->where(['status_id'=>1])->count();
                    echo $query;
                    ?>)</button>
                <div class="test">
                    <?=$this->element('indexTable',['status' => '1']);?>
                </div>

                <button class="collapsible">Completed Tasks (<?php
                    $query = TableRegistry::getTableLocator()->get('Tasks')->find()->where(['status_id'=>2])->count();
                    echo $query;
                    ?>)</button>
                <div class="test">
                    <?=$this->element('indexTable',['status' => '2']);?>
                </div>
            </div>
            <div class="paginator">
                <ul class="pagination">
                    <?= $this->Paginator->first('<< ' . __('first')) ?>
                    <?= $this->Paginator->prev('< ' . __('previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('next') . ' >') ?>
                    <?= $this->Paginator->last(__('last') . ' >>') ?>
                </ul>
                <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Html->script(['collapsible']);?>
