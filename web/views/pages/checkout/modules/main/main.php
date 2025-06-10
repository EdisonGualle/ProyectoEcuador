<!--=================================
MAIN
==================================-->

<div class="container-fluid p-0 position-relative" id="main">

    <figure class="position-absolute colorImage" style="top:0;left:0;width:100%">
        <img src="/views/assets/img/contest-bg.png" class="img-fluid w-100">
    </figure>

    <?php

    include "views/modules/countdown/countdown.php";

    ?>

    <div class="container position-relative" style="bottom:50px">

        <form method="POST" class="needs-validation" novalidate>

            <?php
            $numbersString = $_GET["numbers"] ?? '';
            $isDynamic = is_numeric($numbersString);
            $numbersArray = explode(",", $numbersString);
            ?>
            <input type="hidden" value="<?= $numbersString ?>" name="numbers">

            <input type="hidden" value="<?php echo $raffle->id_raffle ?>" name="raffle">

            <div class="row">

                <div class="offset-3 col-6">

                    <div class="position-relative bg p-1 rounded">

                        <p class="text-center mt-4 text-uppercase h4">COMPLETA LOS DATOS A CONTINUACIÓN</p>
                        <h1 class="text-center display-1 my-0"><i class="fas fa-angle-down"></i></h1>

                        <!--==============================================
                        NOMBRE Y APELLIDO
                        ================================================-->

                        <div class="row p-0">

                            <div class="col px-5 py-2 pt-3 input-group">


                                <span class="input-group-text">
                                    <i class="bi bi-person-fill"></i>
                                </span>


                                <input type="text" class="form-control rounded-end py-3" placeholder="Nombre(s)"
                                    onchange="validateJS(event,'text')" name="name" required>

                                <div class="invalid-feedback">Por favor llena este campo correctamente.</div>

                            </div>

                        </div>

                        <div class="row p-0">

                            <div class="col px-5 py-2 input-group">


                                <span class="input-group-text">
                                    <i class="bi bi-person-fill-add"></i>
                                </span>


                                <input type="text" class="form-control rounded-end py-3" placeholder="Apellido(s)"
                                    onchange="validateJS(event,'text')" name="surname" required>

                                <div class="invalid-feedback">Por favor llena este campo correctamente.</div>

                            </div>

                        </div>

                        <!--==============================================
                        NÚMERO WHATSAPP
                        ================================================-->

                        <div class="row">

                            <div class="col px-5 py-2 input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-whatsapp"></i>
                                </span>

                                <input id="phone-mask" type="text" class="form-control rounded-end py-3"
                                    placeholder="Número WhatsApp. Ej: +593 99 285 2752" name="whatsapp" required>
                                <div class="invalid-feedback">Por favor ingresa un número válido de WhatsApp en Ecuador.
                                </div>
                            </div>

                        </div>


                        <!--==============================================
                        CORREO ELECTRÓNICO
                        ================================================-->

                        <div class="row">

                            <div class="col px-5 py-2 input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>

                                <input type="email" class="form-control rounded-end py-3"
                                    placeholder="Correo Electrónico" onchange="validateJS(event,'email')" name="email"
                                    required>

                                <div class="invalid-feedback">Por favor llena este campo correctamente.</div>

                            </div>

                        </div>

                        <!--==============================================
                        CONFIRMA CORREO ELECTRÓNICO
                        ================================================-->

                        <div class="row">

                            <div class="col px-5 py-2 pb-3 input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-envelope-plus"></i>
                                </span>


                                <input type="email" class="form-control rounded-end py-3"
                                    placeholder="Confirma el Correo Electrónico"
                                    onchange="validateEmailx2(event,'email')" name="email_2" required>

                                <div class="invalid-feedback">Por favor llena este campo correctamente.</div>

                            </div>

                        </div>

                        <!--==============================================
                        DETALLES DE LA COMPRA
                        ================================================-->

                        <div class="row p-1">

                            <div class="col px-5 py-2">

                                <p class="pt-1">Detalles de la compra:</p>

                                <div class="row my-3 px-2">

                                    <div class="card border rounded w-100">

                                        <div class="d-flex justify-content-between pt-3">

                                            <div class="py-2 pl-3 josefin-sans-700 h6">Número(s) Elegido(s): </div>

                                            <div class="py-2 pl-3 josefin-sans-700 h6">
                                                <?php if ($isDynamic): ?>
                                                    Se generarán <strong><?= intval($numbersString) ?></strong> número(s)
                                                    aleatorios
                                                <?php else: ?>
                                                    <?= htmlspecialchars($numbersString) ?>
                                                <?php endif; ?>
                                            </div>


                                        </div>

                                        <div class="d-flex justify-content-between">

                                            <div class="py-2 pl-3 josefin-sans-700 h4">Total a pagar:</div>
                                            <div class="py-2 pr-3 josefin-sans-700 h4">
                                                $
                                                <?php
                                                $count = $isDynamic ? intval($numbersString) : count($numbers);
                                                echo number_format($count * $raffle->price_raffle, 2);
                                                ?> USD


                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!--==============================================
                        FORMA DE PAGO
                        ================================================-->

                            <div class="row p-1">

                                <div class="col px-5  pb-3">

                                    <p class="pt-1">Métodos de pago:</p>

                                    <div class="row row-cols-1 row-cols-sm-2">

                                        <div class="col pt-2 px-2">

                                            <div class="card rounded px-4 py-1">

                                                <div class="form-check px-2 mb-3">

                                                    <input type="radio" class="form-check-input mt-2 ml-1 changePaid"
                                                        id="radio1" name="optradio" value="paypal" checked
                                                        mode="paidPayPal">

                                                    <label for="radio1" class="form-check-label float-end mt-2">

                                                        <span>
                                                            PayPal
                                                            <img src="/views/assets/img/paypal.jpg" class="img-fluid"
                                                                style="width:200px">
                                                        </span>

                                                    </label>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="col pt-2 px-2">
                                            <div class="card rounded px-4 py-1">
                                                <div class="form-check px-2 mb-3">

                                                    <input type="radio" class="form-check-input mt-2 ml-1 changePaid"
                                                        id="radio3" name="optradio" value="transferencia"
                                                        mode="paidBank">

                                                    <label for="radio3" class="form-check-label float-end mt-2">

                                                        <span>
                                                            Transferencia / Depósito
                                                            <img src="/views/assets/img/transferencia.png"
                                                                class="img-fluid" style="width:180px">
                                                        </span>

                                                    </label>

                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                </div>

                            </div>

                            <!--==============================================
                        PAGO
                        ================================================-->

                            <div class="row">

                                <div class="col px-5 pb-3">

                                    <div class="card cardPaid rounded" id="paidPayPal">

                                        <div class="card-header mb-0 pb-0">

                                            <figure class="text-center"><small>Usarás</small> <img
                                                    src="/views/assets/img/paypal.png" style="width:80px;">
                                                <br><small>Todas
                                                    las transacciones son seguras y están encriptadas.</small>
                                            </figure>

                                        </div>

                                        <div class="card-body pb-0">

                                            <div class="px-3">

                                                <div class="small">

                                                    <div class="px-2 mb-2 text-center pb-2">
                                                        <small class="small">Luego de hacer clic en “Comprar ahora”,
                                                            serás
                                                            redirigido a PayPal.</small>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="card cardPaid rounded" id="paidBank"
                                        style="display: none; background-color: #FFFFFF;">
                                        <div class="card-header mb-0 pb-0 text-center">
                                            <figure class="text-center">
                                                <img src="/views/assets/img/transferencia.png" style="width:80px;">
                                                <br>
                                                <small>Pago mediante transferencia o depósito bancario</small>
                                            </figure>
                                        </div>

                                        <div class="card-body pb-0">
                                            <!-- Grid of Bank Logos -->
                                            <div class="row px-2 justify-content-around mb-4">
                                                <!-- BANCO PICHINCHA -->
                                                <div class="col-md-6 col-sm-6 mb-3">
                                                    <div class="bank-option border rounded p-3  text-center cursor-pointer"
                                                        data-bank="BANCO PICHINCHA" data-type="Cuenta Corriente"
                                                        data-number="2100247823">
                                                        <img src="/views/assets/img/pichincha_logo.png"
                                                            alt="Banco Pichincha" style="height: 28px;">
                                                    </div>
                                                </div>

                                                <!-- PRODUBANCO -->
                                                <div class="col-md-6 col-sm-6 mb-3">
                                                    <div class="bank-option border rounded p-3  text-center cursor-pointer"
                                                        data-bank="Produbanco" data-type="Cuenta Ahorro"
                                                        data-number="12183000841">
                                                        <img src="/views/assets/img/produbanco_logo.webp"
                                                            alt="Produbanco" style="height: 28px;">
                                                    </div>
                                                </div>

                                                <!-- PACÍFICO -->
                                                <div class="col-md-6 col-sm-6 mb-3">
                                                    <div class="bank-option border rounded p-1  text-center cursor-pointer"
                                                        data-bank="Banco del Pacífico" data-type="Cuenta Ahorro"
                                                        data-number="1053097335">
                                                        <img src="/views/assets/img/pacifico_logo.png"
                                                            alt="Banco del Pacífico" style="height: 52px;">
                                                    </div>
                                                </div>

                                                <!-- GUAYAQUIL -->
                                                <div class="col-md-6 col-sm-6 mb-3">
                                                    <div class="bank-option border rounded p-3  text-center cursor-pointer"
                                                        data-bank="Banco Guayaquil" data-type="Cuenta Corriente"
                                                        data-number="0012439045">
                                                        <img src="/views/assets/img/guayaquil_logo.png"
                                                            alt="Banco Guayaquil" style="height: 28px;">
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="bankDetails" class="p-3 rounded border mb-3"
                                                style="display: none; background-color: #ffffff !important; color: #111111 !important; font-weight: 500 !important;">

                                                <p class="mb-2 text-uppercase"
                                                    style="color: #111111 !important; font-weight: bold !important;">
                                                    <span id="bankName"></span>
                                                </p>

                                                <p class="mb-1" style="color: #111111 !important;">
                                                    💳 <span id="accountType"></span> –
                                                    <span id="accountNumber"
                                                        style="font-weight: bold !important;"></span>
                                                </p>

                                                <p class="mb-1" style="color: #111111 !important;">
                                                    🪪 Número de cédula: <strong
                                                        style="color: #111111 !important;">1721855912</strong>
                                                </p>

                                                <p class="mb-0" style="color: #111111 !important;">
                                                    📧 Correo: <strong
                                                        style="color: #111111 !important;">ventas@proyectoecuador.com</strong>
                                                </p>
                                            </div>

                                            <!-- Pasos para wp -->
                                            <div class="p-2 text-center mb-2">
                                                <small class="small"> Luego de hacer clic en “Comprar ahora”, se abrirá
                                                    WhatsApp para que envíes tu comprobante de pago y así procesar tu
                                                    solicitud.
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <!--==============================================
                        BOTÓN
                        ================================================-->
                            <div class="row">

                                <div class="col-12 px-5 pb-3">

                                    <button type="submit"
                                        class="btn btn-block w-100 b1 rounded py-3 josefin-sans-700 text-uppercase border-0">Comprar
                                        ahora</button>

                                </div>

                            </div>

                            <?php
                            require_once "controllers/orders.controller.php";
                            $order = new OrdersController();
                            $order->orderCreate();

                            ?>

                        </div>

                    </div>
                </div>

        </form>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const phoneInput = document.getElementById('phone-mask');

            if (phoneInput) {
                // Aplicar máscara con IMask para número de Ecuador: +593 99 999 9999
                const mask = IMask(phoneInput, {
                    mask: '+{593} 00 000 0000'
                });

                const form = document.querySelector("form.needs-validation");

                if (form) {
                    // Validación personalizada al enviar el formulario
                    form.addEventListener("submit", function (e) {
                        const phone = phoneInput.value.trim();

                        const isValidPhone = /^\+593 \d{2} \d{3} \d{4}$/.test(phone);

                        if (!isValidPhone) {
                            e.preventDefault();
                            e.stopPropagation();

                            phoneInput.classList.add("is-invalid");
                            phoneInput.classList.remove("is-valid");
                            phoneInput.parentElement.classList.add("was-validated");
                            phoneInput.nextElementSibling.innerText = "Número de WhatsApp incompleto.";
                        }
                    });

                    // Quitar error automáticamente al corregir
                    phoneInput.addEventListener("input", function () {
                        const phone = phoneInput.value.trim();

                        if (/^\+593 \d{2} \d{3} \d{4}$/.test(phone)) {
                            phoneInput.classList.remove("is-invalid");
                            phoneInput.classList.add("is-valid");
                            phoneInput.parentElement.classList.remove("was-validated");
                        } else {
                            phoneInput.classList.remove("is-valid");
                        }
                    });
                }
            }
        });

        document.querySelectorAll('.bank-option').forEach(el => {
            el.addEventListener('click', () => {
                const bankName = el.dataset.bank;
                const accountType = el.dataset.type;
                const accountNumber = el.dataset.number;

                document.getElementById('bankDetails').style.display = 'block';
                document.getElementById('bankName').textContent = bankName;
                document.getElementById('accountType').textContent = accountType;
                document.getElementById('accountNumber').textContent = accountNumber;

                // Opcional: marcar visualmente el banco activo
                document.querySelectorAll('.bank-option').forEach(b => b.classList.remove('border-success'));
                el.classList.add('border-success');
            });
        });
    </script>

</div>