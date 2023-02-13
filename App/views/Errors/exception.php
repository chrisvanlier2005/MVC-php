<?php
$trace = $error->getTrace();
$errorMessage = $error->getMessage();

?>
<div class="message">
    <h1>Exception</h1>
    <h3>
        <strong>Message:</strong> <?= $errorMessage ?>
    </h3>
    <p>
        <strong>File:</strong> <?= $error->getFile() ?>
    </p>
    <p>
        <strong>Line:</strong> <?= $error->getLine() ?>
    </p>
</div>

<ul class="trace">
    <?php foreach ($trace as $item): ?>
        <li>
            <p>
                <strong>File:</strong> <?= $item['file'] ?>
            </p>
            <p>
                <strong>Line:</strong> <?= $item['line'] ?>
            </p>
            <p>
                <strong>Function:</strong> <?= $item['function'] ?>
            </p>
            <?php if (isset($item['args'])): ?>
                <p>
                    <strong>Arguments:</strong>
                </p>
                <ul>
                    <?php foreach ($item['args'] as $arg): ?>
                        <li>
                            <p>
                                <strong>Type:</strong> <?= gettype($arg) ?>
                            </p>
                            <p>
                                <strong>Value:</strong> <?= $arg ?>
                            </p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>

<style>
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: sans-serif;
    }

    .trace{
        padding: 20px;
    }
    .trace li{
        list-style: none;
        padding: 20px;
        border: 1px solid #ccc;
        margin-bottom: 20px;
    }
    .message h1{
        text-align: center;
        padding: 20px;
        color: red;
    }
    .message h3{
        text-align: center;
        padding: 20px;
        color: #151515;
    }
    .message p{
        text-align: center;
        padding: 20px;
        color: #151515;
    }

</style>