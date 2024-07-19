<?php

/** @var yii\web\View $this */

$this->title = 'HOMESELECT';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row justify-content-center">
            <div class="col-lg-3 mb-3 text-center">
                <h2>Apartamentos</h2>
                <p>Administra tus apartamentos fácilmente. Añade nuevos apartamentos, edita detalles existentes y gestiona tarifas de manera eficiente.</p>
                <p><a class="btn btn-outline-primary" href="<?= Yii::$app->urlManager->createUrl(['apartamento/index']) ?>">Ver Apartamentos &raquo;</a></p>
            </div>
            <div class="col-lg-3 mb-3 text-center">
                <h2>Clientes</h2>
                <p>Gestiona la información de tus clientes. Añade nuevos clientes y mantén actualizada su información de contacto.</p>
                <p><a class="btn btn-outline-primary" href="<?= Yii::$app->urlManager->createUrl(['cliente/index']) ?>">Ver Clientes &raquo;</a></p>
            </div>
            <div class="col-lg-3 mb-3 text-center">
                <h2>Reservas</h2>
                <p>Administra las reservas de tus apartamentos. Crea, edita y gestiona todas las reservas desde un solo lugar.</p>
                <p><a class="btn btn-outline-primary" href="<?= Yii::$app->urlManager->createUrl(['reserva/index']) ?>">Ver Reservas &raquo;</a></p>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-12 text-center">
                <h2>Más Información</h2>
                <p>Encuentra documentación, soporte y extensiones para mejorar tu experiencia con Yii.</p>
            </div>
        </div>

    </div>
</div>

<?php
$this->registerCss("
    .jumbotron {
        background-image: url('https://example.com/your-background-image.jpg');
        background-size: cover;
        color: white;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
    }
    .btn-outline-primary {
        border-color: #007BFF;
        color: #007BFF;
    }
    .btn-outline-primary:hover {
        background-color: #007BFF;
        color: white;
    }
    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }
    .site-index .row {
        margin-top: 20px;
    }
    .body-content {
        padding-top: 50px;
    }
");
?>
