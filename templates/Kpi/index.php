
<?php 
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Task;

  $NotComplted = 0;
  $Completed = 0;
  //$this->disableAutoLayout();
  $query = TableRegistry::getTableLocator()->get('Tasks')->find();
  $query->contain(['Users']);
  $query->contain(['Status']);
  $query->contain(['Clients']);
  // $navData = array(
  //   array('name'=>'Completed', 'value'=>0, 'dueDate'=>'', 'itemStyle'=>array('color'=>'green')),
  //   array('name'=>'Not Completed', 'value'=>0,'dueDate'=>'', 'itemStyle'=>array('color'=>'#b80c3c')),
  // );
  $navData = Array();
  foreach ($query as $task) {
      echo $task['due_data'];
      if($task->status->name=='Completed'){
        $Completed += 1;
        array_push($navData, array('name'=>'Completed', 'value'=>1, 'dueDate'=> $task->due_date));
      }else{
        $NotComplted += 1;
        array_push($navData, array('name'=>'Not Completed', 'value'=>1, 'dueDate'=> $task->due_date));
      }
  }
  ?>
<!DOCTYPE html>
<html></html>
<title> KPI </title>

<head>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->Html->meta('icon') ?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
</head>
<link rel="stylesheet" href="webroot/css/kanban.css">
<link rel="stylesheet" href="webroot/css/tasks.css">
<link rel="stylesheet" href="webroot/css/custom.css">
<link rel="stylesheet" href="webroot/css/buttons.css">
<style>
  #total{
    width:100%;
    height:600px;
  }
</style>
<body>
  <div id="total"></div>
  <script>
    var navData = <?php echo json_encode($navData) ?>; //get data 
    // console.log(navData)
    function process(arr) {
      const cache = [];
      for (const t of arr) { 
          if (cache.find(c => c.name === t.name && c.dueDate === t.dueDate)) {   //delete repeat things
            cache.find(c => c.name === t.name && c.dueDate === t.dueDate).value += 1
          }else{
            cache.push(t);
          }
      }
      return cache;
  }
  var formatData = process(navData)   
  formatData.forEach(item=>{    //add default value 
    if(item.name == "Completed"){
      item['Completed'] = item.value
    }else{
      item['NotCompleted'] = item.value
    }
  })
  var NewformatData = newProcess(formatData)  //according to date delete repeat thing again
  function newProcess(arr) {
      const cache = [];
      for (const t of arr) {
          if (cache.find(c => c.dueDate === t.dueDate)) {
            if(cache.find(c => c.dueDate === t.dueDate).name == 'Completed'){
              cache.find(c => c.dueDate === t.dueDate)['NotCompleted']  = t.NotCompleted
            }else{
              cache.find(c => c.dueDate === t.dueDate)['Completed']  = t.Completed
            }
          }else{
            cache.push(t);
          }
      }
      return cache;
    }
    var dom = document.getElementById("total");
        var myChart = echarts.init(dom);
        var option;

        option = {
            title: {
                text: 'total tasks',
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['Completed', 'Not Completed']
            },
            calculable: true,
            xAxis: [
                {
                    type: 'category',
                    data: NewformatData.map(i=>i.dueDate)
                }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],
            series: [
                {
                    name: 'Completed',
                    type: 'bar',
                    data: NewformatData.map(i=>i.Completed || 0)
                },
                {
                    name: 'Not Completed',
                    type: 'bar',
                    data: NewformatData.map(i=>i.NotCompleted || 0)
                }
            ]
        };

        if (option && typeof option === 'object') {
            myChart.setOption(option);
        }
  </script>
</body>