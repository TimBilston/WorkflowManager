<!-- in /templates/Users/login.php -->
<?php

    $this->disableAutoLayout();

?>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake', 'custom', 'login']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<div class="parent clearfix">
    <div class="bg-illustration">
        <div style="display: flex; justify-content: center">
            <img src=<?php echo $this->Url->image('logo.png')?> alt="logo">
        </div>
    </div>

    <div class="login">
        <div class="container">
            <?= $this->Flash->render() ?>
            <h1 style="font-family: 'Book Antiqua'">Login to access to<br />the Dashboard</h1>

            <div class="login-form">

                    <?= $this->Form->create() ?>
                    <?= $this->Form->control('email', ['required' => true, 'placeholder' => 'E-mail Address', 'label' => false]) ?>
                    <?= $this->Form->control('password', ['required' => true, 'placeholder' => 'Password', 'label' => false]) ?>


                    <button type="submit"> Login </button>
                    <?= $this->Form->end() ?>


            </div>

        </div>
    </div>
</div>
